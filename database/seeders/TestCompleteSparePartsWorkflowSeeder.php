<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SparePart;
use App\Models\SparePartSerial;
use App\Models\WarehouseInventory;
use App\Models\SparePartTransaction;
use App\Models\Location;
use App\Models\Equipment;
use Illuminate\Support\Facades\DB;

class TestCompleteSparePartsWorkflowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        echo "🚀 بداية اختبار سيناريو كامل لقطع الغيار...\n";

        // البحث عن المستودع والمعدة
        $warehouse = Location::find(40);
        $equipment = Equipment::where('status', 'active')->first();

        // إنشاء معدة تجريبية إذا لم تكن موجودة
        if (!$equipment) {
            $equipment = Equipment::create([
                'name' => 'حفار تجريبي - للاختبار',
                'type' => 'excavator',
                'model' => 'TEST-MODEL',
                'manufacturer' => 'CAT',
                'serial_number' => 'SN-TEST-' . time(),
                'status' => 'available',
                'location_id' => $warehouse ? $warehouse->id : null,
                'purchase_date' => '2020-01-01',
                'purchase_price' => 250000.00,
            ]);
            echo "🏗️ تم إنشاء معدة تجريبية: {$equipment->name}\n";
        }

        if (!$warehouse) {
            echo "❌ المستودع 40 غير موجود!\n";
            return;
        }

        echo "📦 المستودع: {$warehouse->name}\n";
        echo "🚜 المعدة: {$equipment->name}\n";

        DB::beginTransaction();

        try {
            // 1. إنشاء قطعة غيار جديدة (سيناريو شراء من فاتورة)
            $newSparePart = SparePart::firstOrCreate([
                'name' => 'فلتر هيدروليكي - اختبار كامل',
                'spare_part_type_id' => 1,
            ], [
                'code' => SparePart::generateCode(),
                'description' => 'قطعة غيار جديدة لاختبار السيناريو الكامل',
                'unit_price' => 450.75,
                'is_active' => true,
                'source' => 'new',
            ]);

            echo "✅ 1. قطعة جديدة: {$newSparePart->name} (الكود: {$newSparePart->code})\n";

            // 2. إنتاج 3 أرقام تسلسلية للقطع الجديدة
            for ($i = 0; $i < 3; $i++) {
                $serialNumber = $newSparePart->generateSerialNumber();
                $barcode = $newSparePart->generateBarcode();

                SparePartSerial::create([
                    'spare_part_id' => $newSparePart->id,
                    'serial_number' => $serialNumber,
                    'barcode' => $barcode,
                    'location_id' => $warehouse->id,
                    'status' => 'available',
                ]);

                echo "   📋 رقم تسلسلي: {$serialNumber}\n";
            }

            // 3. إنشاء مخزون للقطع الجديدة
            $newInventory = WarehouseInventory::firstOrCreate([
                'location_id' => $warehouse->id,
                'spare_part_id' => $newSparePart->id,
            ], [
                'current_stock' => 0,
                'total_value' => 0,
            ]);

            $newInventory->current_stock += 3;
            $newInventory->total_value += (3 * 450.75);
            $newInventory->save();

            // 4. معاملة استلام القطع الجديدة
            SparePartTransaction::create([
                'location_id' => $warehouse->id,
                'spare_part_id' => $newSparePart->id,
                'transaction_type' => 'استلام',
                'quantity' => 3,
                'unit_price' => 450.75,
                'total_amount' => 3 * 450.75,
                'notes' => 'استلام من فاتورة للاختبار الكامل',
                'user_id' => 3,
                'transaction_date' => now(),
                'additional_data' => [
                    'source' => 'new_invoice',
                    'invoice_number' => 'FULL-TEST-001',
                    'supplier_id' => '1',
                ],
            ]);

            echo "✅ 2. تم استلام 3 قطع جديدة في المخزون\n";

            // 5. تصدير قطعة واحدة للمعدة (محاكاة الاستبدال)
            $serialToExport = SparePartSerial::where('spare_part_id', $newSparePart->id)
                ->where('location_id', $warehouse->id)
                ->where('status', 'available')
                ->first();

            if ($serialToExport) {
                $serialToExport->update([
                    'status' => 'assigned',
                    'assigned_to_equipment_id' => $equipment->id,
                    'assigned_to_employee_id' => 3,
                    'assigned_date' => now()->toDateString(),
                ]);

                // تحديث المخزون
                $newInventory->current_stock -= 1;
                $newInventory->total_value -= 450.75;
                $newInventory->save();

                // معاملة التصدير
                SparePartTransaction::create([
                    'location_id' => $warehouse->id,
                    'spare_part_id' => $newSparePart->id,
                    'equipment_id' => $equipment->id,
                    'transaction_type' => 'تصدير',
                    'quantity' => 1,
                    'unit_price' => 450.75,
                    'total_amount' => 450.75,
                    'notes' => 'تصدير للمعدة لاستبدال قطعة تالفة',
                    'user_id' => 3,
                    'transaction_date' => now(),
                    'additional_data' => [
                        'technician_employee_id' => 3,
                        'work_order_number' => 'WO-TEST-001',
                        'replacement_reason' => 'breakdown',
                        'old_part_condition' => 'damaged',
                    ],
                ]);

                echo "✅ 3. تم تصدير قطعة واحدة للمعدة ({$equipment->name})\n";
            }

            // 6. إنشاء قطعة تالفة (القطعة القديمة المُستبدَلة)
            $damagedPart = SparePart::firstOrCreate([
                'name' => 'فلتر هيدروليكي - اختبار كامل (تالفة)',
                'spare_part_type_id' => 1,
            ], [
                'code' => SparePart::generateCode(),
                'description' => 'قطعة تالفة تم استبدالها من اختبار السيناريو الكامل',
                'unit_price' => 0,
                'is_active' => false,
                'source' => 'returned', // استخدام القيمة المتاحة في enum
            ]);

            // إنتاج رقم تسلسلي للقطعة التالفة
            $damagedSerial = $damagedPart->generateSerialNumber();
            $damagedBarcode = $damagedPart->generateBarcode();

            SparePartSerial::create([
                'spare_part_id' => $damagedPart->id,
                'serial_number' => $damagedSerial,
                'barcode' => $damagedBarcode,
                'location_id' => $warehouse->id,
                'status' => 'damaged',
                'notes' => 'قطعة تالفة تم استبدالها من المعدة - السبب: تآكل',
                'assigned_to_employee_id' => 3,
                'returned_date' => now()->toDateString(),
                'assigned_to_equipment_id' => $equipment->id,
            ]);

            // إنشاء مخزون للقطعة التالفة
            $damagedInventory = WarehouseInventory::firstOrCreate([
                'location_id' => $warehouse->id,
                'spare_part_id' => $damagedPart->id,
            ], [
                'current_stock' => 0,
                'total_value' => 0,
            ]);

            $damagedInventory->current_stock += 1;
            $damagedInventory->save();

            // معاملة استلام القطعة التالفة
            SparePartTransaction::create([
                'location_id' => $warehouse->id,
                'spare_part_id' => $damagedPart->id,
                'equipment_id' => $equipment->id,
                'transaction_type' => 'استلام',
                'quantity' => 1,
                'unit_price' => 0,
                'total_amount' => 0,
                'notes' => 'قطعة تالفة مُستبدَلة - تالفة بسبب التآكل',
                'user_id' => 3,
                'transaction_date' => now(),
                'additional_data' => [
                    'source' => 'damaged_replacement',
                    'technician_employee_id' => 3,
                    'work_order_number' => 'WO-TEST-001',
                    'original_part_id' => $newSparePart->id,
                    'damage_condition' => 'worn_out',
                ],
            ]);

            echo "✅ 4. تم استلام قطعة تالفة مُستبدَلة (رقم: {$damagedSerial})\n";

            DB::commit();
            echo "\n🎉 تم إنجاز السيناريو الكامل بنجاح!\n";

            // تقرير نهائي
            $totalActiveItems = WarehouseInventory::where('location_id', $warehouse->id)->sum('current_stock');
            $totalActiveTypes = WarehouseInventory::where('location_id', $warehouse->id)->count();
            $totalDamagedItems = WarehouseInventory::where('location_id', $warehouse->id)
                ->whereHas('sparePart', function ($query) {
                    $query->where('source', 'damaged_replacement');
                })
                ->sum('current_stock');

            echo "\n📈 التقرير النهائي:\n";
            echo "   📦 إجمالي القطع في المخزون: {$totalActiveItems}\n";
            echo "   🔧 إجمالي أنواع القطع: {$totalActiveTypes}\n";
            echo "   💔 القطع التالفة: {$totalDamagedItems}\n";
        } catch (\Exception $e) {
            DB::rollBack();
            echo "❌ خطأ: {$e->getMessage()}\n";
            echo "Stack trace: {$e->getTraceAsString()}\n";
        }
    }
}

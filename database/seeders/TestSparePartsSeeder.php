<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SparePart;
use App\Models\SparePartSerial;
use App\Models\WarehouseInventory;
use App\Models\SparePartTransaction;
use App\Models\Location;
use Illuminate\Support\Facades\DB;

class TestSparePartsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        echo "بداية إنشاء بيانات تجريبية...\n";
        
        // البحث عن المستودع 40
        $warehouse = Location::find(40);
        if (!$warehouse) {
            echo "❌ المستودع 40 غير موجود!\n";
            return;
        }
        
        echo "📦 المستودع: {$warehouse->name}\n";

        DB::beginTransaction();
        
        try {
            // إنشاء قطعة غيار تجريبية
            $sparePart = SparePart::firstOrCreate([
                'name' => 'فلتر زيت تجريبي - Seeder',
                'spare_part_type_id' => 1,
            ], [
                'code' => SparePart::generateCode(),
                'description' => 'قطعة غيار تجريبية من Seeder للاختبار',
                'unit_price' => 280.50,
                'is_active' => true,
                'source' => 'new',
            ]);

            echo "✅ قطعة الغيار: {$sparePart->name} (الكود: {$sparePart->code})\n";

            // إنتاج 5 أرقام تسلسلية
            for ($i = 0; $i < 5; $i++) {
                $serialNumber = $sparePart->generateSerialNumber();
                $barcode = $sparePart->generateBarcode();
                
                SparePartSerial::create([
                    'spare_part_id' => $sparePart->id,
                    'serial_number' => $serialNumber,
                    'barcode' => $barcode,
                    'location_id' => $warehouse->id,
                    'status' => 'available',
                ]);
                
                echo "📋 رقم تسلسلي: {$serialNumber} | باركود: {$barcode}\n";
            }

            // إنشاء/تحديث المخزون
            $inventory = WarehouseInventory::firstOrCreate([
                'location_id' => $warehouse->id,
                'spare_part_id' => $sparePart->id,
            ], [
                'current_stock' => 0,
                'total_value' => 0,
            ]);

            $inventory->current_stock += 5;
            $inventory->total_value += (5 * 280.50);
            $inventory->save();

            echo "📊 المخزون: الكمية = {$inventory->current_stock}, القيمة = {$inventory->total_value}\n";

            // إنشاء المعاملة
            SparePartTransaction::create([
                'location_id' => $warehouse->id,
                'spare_part_id' => $sparePart->id,
                'transaction_type' => 'استلام',
                'quantity' => 5,
                'unit_price' => 280.50,
                'total_amount' => 5 * 280.50,
                'notes' => 'بيانات تجريبية من TestSparePartsSeeder',
                'user_id' => 3, // افتراض وجود user بهذا ID
                'transaction_date' => now(),
                'additional_data' => [
                    'source' => 'test_seeder',
                    'invoice_number' => 'SEED-001',
                    'supplier_id' => '1',
                ],
            ]);

            echo "💼 تم إنشاء المعاملة بنجاح\n";

            DB::commit();
            echo "🎉 تم إنشاء جميع البيانات التجريبية بنجاح!\n";
            
            // التحقق النهائي
            $totalItems = WarehouseInventory::where('location_id', $warehouse->id)->count();
            echo "📈 إجمالي أنواع قطع الغيار في المستودع: {$totalItems}\n";
            
        } catch (\Exception $e) {
            DB::rollBack();
            echo "❌ خطأ: {$e->getMessage()}\n";
            echo "Stack trace: {$e->getTraceAsString()}\n";
        }
    }
}

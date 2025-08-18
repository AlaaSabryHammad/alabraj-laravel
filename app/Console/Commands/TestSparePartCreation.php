<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Location;
use App\Models\SparePart;
use App\Models\WarehouseInventory;
use App\Models\SparePartTransaction;
use Illuminate\Support\Facades\DB;

class TestSparePartCreation extends Command
{
    protected $signature = 'test:spare-part-creation {warehouse_id=41}';
    protected $description = 'Test spare part creation process';

    public function handle()
    {
        $warehouseId = $this->argument('warehouse_id');

        $this->info("=== اختبار حفظ قطعة غيار جديدة ===");
        $this->line("");

        try {
            // البحث عن المستودع
            $warehouse = Location::find($warehouseId);
            if (!$warehouse) {
                $this->error("المستودع غير موجود!");
                return;
            }

            $this->info("المستودع: {$warehouse->name}");

            // إنشاء بيانات تجريبية
            $testData = [
                'code' => 'TEST-' . time(),
                'name' => 'قطعة اختبار - ' . date('Y-m-d H:i:s'),
                'description' => 'قطعة اختبار للتحقق من النظام',
                'category' => 'اختبار',
                'brand' => 'TEST',
                'model' => 'MODEL-1',
                'unit_price' => 100.00,
                'unit_type' => 'قطعة',
                'minimum_stock' => 5,
                'supplier' => 'مورد تجريبي',
                'location_shelf' => 'الرف A1',
                'source' => 'new'
            ];

            DB::beginTransaction();

            $this->info("محاولة إنشاء قطعة غيار...");
            $sparePart = SparePart::create($testData);
            $this->info("✅ تم إنشاء قطعة الغيار بنجاح! ID: {$sparePart->id}");

            // إنشاء مخزون
            $this->info("محاولة إنشاء سجل المخزون...");
            $inventory = WarehouseInventory::create([
                'spare_part_id' => $sparePart->id,
                'location_id' => $warehouse->id,
                'current_stock' => 10,
                'available_stock' => 10,
                'average_cost' => 100.00,
                'total_value' => 1000.00,
                'last_transaction_date' => now(),
                'location_shelf' => 'الرف A1',
            ]);
            $this->info("✅ تم إنشاء سجل المخزون بنجاح! ID: {$inventory->id}");

            // إنشاء معاملة
            $this->info("محاولة إنشاء المعاملة...");
            $transaction = SparePartTransaction::create([
                'spare_part_id' => $sparePart->id,
                'location_id' => $warehouse->id,
                'user_id' => 1,
                'transaction_type' => 'استلام',
                'quantity' => 10,
                'unit_price' => 100.00,
                'total_amount' => 1000.00,
                'notes' => 'اختبار النظام',
                'transaction_date' => now(),
            ]);
            $this->info("✅ تم إنشاء المعاملة بنجاح! ID: {$transaction->id}");

            DB::commit();

            $this->line("");
            $this->info("=== تم الاختبار بنجاح ===");

            // عرض البيانات المحفوظة
            $this->line("");
            $this->table(
                ['الحقل', 'القيمة'],
                [
                    ['كود القطعة', $sparePart->code],
                    ['اسم القطعة', $sparePart->name],
                    ['الكمية الحالية', $inventory->current_stock],
                    ['القيمة الإجمالية', $inventory->total_value],
                ]
            );
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("حدث خطأ: " . $e->getMessage());
            $this->line("Stack trace:");
            $this->line($e->getTraceAsString());
        }
    }
}

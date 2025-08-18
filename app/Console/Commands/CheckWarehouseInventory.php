<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Location;
use App\Models\WarehouseInventory;
use App\Models\SparePart;

class CheckWarehouseInventory extends Command
{
    protected $signature = 'check:warehouse-inventory {warehouse_id=41}';
    protected $description = 'Check warehouse inventory';

    public function handle()
    {
        $warehouseId = $this->argument('warehouse_id');

        $this->info("=== فحص مخزون المستودع ===");
        $this->line("");

        // البحث عن المستودع
        $warehouse = Location::find($warehouseId);
        if (!$warehouse) {
            $this->error("المستودع غير موجود!");
            return;
        }

        $this->info("المستودع: {$warehouse->name}");
        $this->line("");

        // الحصول على جميع قطع الغيار في المستودع
        $inventory = WarehouseInventory::with('sparePart')
            ->where('location_id', $warehouseId)
            ->get();

        if ($inventory->isEmpty()) {
            $this->warn("لا توجد قطع غيار في هذا المستودع");
            return;
        }

        $this->info("عدد أصناف قطع الغيار: " . $inventory->count());
        $this->line("");

        $tableData = [];
        foreach ($inventory as $item) {
            $tableData[] = [
                $item->id,
                $item->sparePart->code ?? 'غير محدد',
                $item->sparePart->name ?? 'غير محدد',
                $item->current_stock,
                $item->available_stock,
                number_format($item->total_value, 2),
                $item->location_shelf ?? 'غير محدد',
                $item->last_transaction_date ? $item->last_transaction_date->format('Y-m-d') : 'غير محدد'
            ];
        }

        $this->table([
            'ID المخزون',
            'كود القطعة',
            'اسم القطعة',
            'الكمية الحالية',
            'الكمية المتاحة',
            'القيمة الإجمالية',
            'الرف',
            'آخر معاملة'
        ], $tableData);

        // إحصائيات
        $totalValue = $inventory->sum('total_value');
        $totalItems = $inventory->sum('current_stock');

        $this->line("");
        $this->info("إجمالي عدد القطع: " . number_format($totalItems));
        $this->info("إجمالي القيمة: " . number_format($totalValue, 2) . " ريال");
    }
}

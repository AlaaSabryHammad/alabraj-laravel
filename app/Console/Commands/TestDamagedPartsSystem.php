<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DamagedPartsReceipt;
use App\Models\Project;
use App\Models\Equipment;
use App\Models\SparePart;
use App\Models\Employee;
use App\Models\Warehouse;

class TestDamagedPartsSystem extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:damaged-parts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'اختبار نظام إدارة القطع التالفة';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔧 اختبار نظام إدارة القطع التالفة');
        $this->info('=====================================');

        try {
            // التحقق من وجود البيانات المطلوبة
            $this->checkRequiredData();

            // إنشاء استلام قطعة تالفة تجريبية
            $this->createTestDamagedReceipt();

            // عرض الإحصائيات
            $this->showStatistics();

            $this->info('✅ اختبار النظام مكتمل بنجاح!');
        } catch (\Exception $e) {
            $this->error('❌ خطأ في اختبار النظام: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }

    private function checkRequiredData()
    {
        $this->info('📊 التحقق من البيانات المطلوبة...');

        $projects = Project::count();
        $equipment = Equipment::count();
        $spareParts = SparePart::count();
        $employees = Employee::count();
        $warehouses = Warehouse::count();

        $this->table([
            'النوع',
            'العدد',
            'الحالة'
        ], [
            ['المشاريع', $projects, $projects > 0 ? '✅' : '❌'],
            ['المعدات', $equipment, $equipment > 0 ? '✅' : '❌'],
            ['قطع الغيار', $spareParts, $spareParts > 0 ? '✅' : '❌'],
            ['الموظفين', $employees, $employees > 0 ? '✅' : '❌'],
            ['المخازن', $warehouses, $warehouses > 0 ? '✅' : '❌'],
        ]);

        if ($projects == 0 || $spareParts == 0 || $employees == 0 || $warehouses == 0) {
            throw new \Exception('يجب توفر بيانات أساسية قبل اختبار النظام');
        }
    }

    private function createTestDamagedReceipt()
    {
        $this->info('🆕 إنشاء استلام قطعة تالفة تجريبية...');

        // الحصول على بيانات تجريبية
        $project = Project::first();
        $equipment = Equipment::first();
        $sparePart = SparePart::first();
        $employee = Employee::first();
        $warehouse = Warehouse::first();

        $receipt = DamagedPartsReceipt::create([
            'receipt_date' => now()->toDateString(),
            'receipt_time' => now()->format('H:i'),
            'project_id' => $project->id,
            'equipment_id' => $equipment ? $equipment->id : null,
            'spare_part_id' => $sparePart->id,
            'quantity_received' => 2,
            'damage_condition' => collect(['repairable', 'non_repairable', 'replacement_needed', 'for_evaluation'])->random(),
            'damage_description' => 'تلف ناتج عن الاستخدام المكثف في الموقع',
            'damage_cause' => 'ظروف العمل القاسية',
            'technician_notes' => 'تحتاج لفحص دقيق لتحديد إمكانية الإصلاح',
            'received_by' => $employee->id,
            'warehouse_id' => $warehouse->id,
            'storage_location' => 'A-01-25',
            'estimated_repair_cost' => 500.00,
            'replacement_cost' => 1200.00,
            'processing_status' => 'received'
        ]);

        $this->info("✅ تم إنشاء استلام رقم: {$receipt->receipt_number}");
        $this->info("📦 القطعة: {$receipt->sparePart->name}");
        $this->info("🏗️ المشروع: {$receipt->project->name}");
        $this->info("🔧 الحالة: {$receipt->damage_condition_text}");
        $this->info("📍 الموقع: {$receipt->storage_location}");
    }

    private function showStatistics()
    {
        $this->info('📈 إحصائيات النظام:');

        $totalReceipts = DamagedPartsReceipt::count();
        $byCondition = DamagedPartsReceipt::selectRaw('damage_condition, COUNT(*) as count')
            ->groupBy('damage_condition')
            ->get();

        $byStatus = DamagedPartsReceipt::selectRaw('processing_status, COUNT(*) as count')
            ->groupBy('processing_status')
            ->get();

        $this->info("📊 إجمالي الاستلامات: {$totalReceipts}");

        if ($byCondition->isNotEmpty()) {
            $this->info('📋 حسب حالة التلف:');
            foreach ($byCondition as $item) {
                $conditionText = match ($item->damage_condition) {
                    'repairable' => 'قابلة للإصلاح',
                    'non_repairable' => 'غير قابلة للإصلاح',
                    'replacement_needed' => 'تحتاج لاستبدال',
                    'for_evaluation' => 'تحتاج لتقييم',
                    default => $item->damage_condition
                };
                $this->line("  • {$conditionText}: {$item->count}");
            }
        }

        if ($byStatus->isNotEmpty()) {
            $this->info('🔄 حسب حالة المعالجة:');
            foreach ($byStatus as $item) {
                $statusText = match ($item->processing_status) {
                    'received' => 'تم الاستلام',
                    'under_evaluation' => 'تحت التقييم',
                    'approved_repair' => 'موافقة على الإصلاح',
                    'approved_replace' => 'موافقة على الاستبدال',
                    'disposed' => 'تم التخلص منها',
                    'returned_fixed' => 'تم إرجاعها بعد الإصلاح',
                    default => $item->processing_status
                };
                $this->line("  • {$statusText}: {$item->count}");
            }
        }
    }
}

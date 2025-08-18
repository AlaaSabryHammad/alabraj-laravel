<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EquipmentMaintenance;
use App\Models\Equipment;
use App\Models\User;

class EquipmentMaintenanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // التأكد من وجود معدات ومستخدمين
        $equipment = Equipment::take(5)->get();
        $users = User::take(3)->get();

        if ($equipment->isEmpty() || $users->isEmpty()) {
            $this->command->warn('يجب إنشاء معدات ومستخدمين أولاً قبل تشغيل هذا الـ seeder');
            return;
        }

        // بيانات تجريبية للصيانة الداخلية
        $maintenanceData = [
            [
                'equipment_id' => $equipment->random()->id,
                'user_id' => $users->random()->id,
                'maintenance_date' => now()->subDays(10),
                'maintenance_type' => 'internal',
                'status' => 'completed',
                'description' => 'صيانة دورية - تغيير زيت وفلاتر',
                'notes' => 'تمت الصيانة بنجاح دون مشاكل',
                'expected_completion_date' => now()->subDays(8),
                'actual_completion_date' => now()->subDays(7),
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(7),
            ],
            [
                'equipment_id' => $equipment->random()->id,
                'user_id' => $users->random()->id,
                'maintenance_date' => now()->subDays(15),
                'maintenance_type' => 'external',
                'status' => 'completed',
                'description' => 'إصلاح عطل في نظام الكهرباء',
                'notes' => 'تم الإصلاح في مركز الخدمة المعتمد',
                'expected_completion_date' => now()->subDays(12),
                'actual_completion_date' => now()->subDays(10),
                'external_cost' => 1500.00,
                'maintenance_center' => 'مركز الخدمة المتقدم',
                'invoice_number' => 'INV-2024-001',
                'created_at' => now()->subDays(15),
                'updated_at' => now()->subDays(10),
            ],
            [
                'equipment_id' => $equipment->random()->id,
                'user_id' => $users->random()->id,
                'maintenance_date' => now()->subDays(5),
                'maintenance_type' => 'internal',
                'status' => 'in_progress',
                'description' => 'فحص شامل وصيانة وقائية',
                'notes' => 'جاري العمل على الصيانة الشاملة',
                'expected_completion_date' => now()->addDays(2),
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],
            [
                'equipment_id' => $equipment->random()->id,
                'user_id' => $users->random()->id,
                'maintenance_date' => now()->subDays(3),
                'maintenance_type' => 'external',
                'status' => 'in_progress',
                'description' => 'إصلاح تسرب في النظام الهيدروليكي',
                'notes' => 'تم إرسال المعدة لمركز الصيانة المتخصص',
                'expected_completion_date' => now()->addDays(5),
                'external_cost' => 2800.00,
                'maintenance_center' => 'مركز الهيدروليك المتخصص',
                'invoice_number' => 'INV-2024-002',
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3),
            ],
            [
                'equipment_id' => $equipment->random()->id,
                'user_id' => $users->random()->id,
                'maintenance_date' => now()->subDays(20),
                'maintenance_type' => 'internal',
                'status' => 'completed',
                'description' => 'تنظيف وفحص عام للمعدة',
                'notes' => 'صيانة روتينية تمت بنجاح',
                'expected_completion_date' => now()->subDays(19),
                'actual_completion_date' => now()->subDays(18),
                'created_at' => now()->subDays(20),
                'updated_at' => now()->subDays(18),
            ],
            [
                'equipment_id' => $equipment->random()->id,
                'user_id' => $users->random()->id,
                'maintenance_date' => now()->subDays(1),
                'maintenance_type' => 'external',
                'status' => 'in_progress',
                'description' => 'استبدال قطع غيار رئيسية',
                'notes' => 'في انتظار وصول القطع المطلوبة',
                'expected_completion_date' => now()->addDays(7),
                'external_cost' => 4200.00,
                'maintenance_center' => 'مركز قطع الغيار الأصلية',
                'invoice_number' => 'INV-2024-003',
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1),
            ],
        ];

        foreach ($maintenanceData as $data) {
            EquipmentMaintenance::create($data);
        }

        $this->command->info('تم إنشاء ' . count($maintenanceData) . ' سجل صيانة تجريبي بنجاح.');
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FuelTruck;
use App\Models\FuelDistribution;
use App\Models\Equipment;
use App\Models\User;

class FuelDistributionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // الحصول على التانكرات الموجودة
        $fuelTrucks = FuelTruck::all();

        if ($fuelTrucks->isEmpty()) {
            echo "لا توجد تانكرات في قاعدة البيانات!\n";
            return;
        }

        // الحصول على المعدات الأخرى (غير التانكرات) لتوزيع المحروقات عليها
        $targetEquipments = Equipment::whereNotIn('id', $fuelTrucks->pluck('equipment_id'))
            ->where('status', '!=', 'out_of_order')
            ->get();

        if ($targetEquipments->isEmpty()) {
            echo "لا توجد معدات مستهدفة للتوزيع!\n";
            return;
        }

        $user = User::first();
        if (!$user) {
            echo "لا يوجد مستخدم في قاعدة البيانات!\n";
            return;
        }

        foreach ($fuelTrucks as $fuelTruck) {
            // إنشاء توزيعات متعددة لكل تانكر
            $quantities = [50, 75, 100, 150, 200];
            $days = [1, 3, 5, 7, 10, 15, 20, 25, 30];

            for ($i = 1; $i <= 3; $i++) {
                $targetEquipment = $targetEquipments->random();
                $quantity = $quantities[($i - 1) % count($quantities)];

                // توزيعات معتمدة
                FuelDistribution::create([
                    'fuel_truck_id' => $fuelTruck->id,
                    'target_equipment_id' => $targetEquipment->id,
                    'distributed_by' => $user->id,
                    'fuel_type' => $fuelTruck->fuel_type,
                    'quantity' => $quantity,
                    'distribution_date' => now()->subDays($days[$i % count($days)]),
                    'approval_status' => 'approved',
                    'approved_by' => $user->id,
                    'approved_at' => now()->subDays($days[($i + 2) % count($days)]),
                    'approval_notes' => 'موافقة تلقائية - بيانات تجريبية',
                    'notes' => "توزيع {$quantity} لتر من {$fuelTruck->fuel_type}"
                ]);

                // توزيعات في انتظار الموافقة
                if ($i == 1) {
                    FuelDistribution::create([
                        'fuel_truck_id' => $fuelTruck->id,
                        'target_equipment_id' => $targetEquipments->random()->id,
                        'distributed_by' => $user->id,
                        'fuel_type' => $fuelTruck->fuel_type,
                        'quantity' => $quantities[2], // 100 لتر
                        'distribution_date' => now()->subDays(2),
                        'approval_status' => 'pending',
                        'notes' => 'في انتظار موافقة المدير'
                    ]);
                }
            }
        }

        echo "تم إنشاء بيانات التوزيعات التجريبية بنجاح!\n";
        echo "التوزيعات المعتمدة: " . FuelDistribution::where('approval_status', 'approved')->count() . "\n";
        echo "التوزيعات المعلقة: " . FuelDistribution::where('approval_status', 'pending')->count() . "\n";
    }
}

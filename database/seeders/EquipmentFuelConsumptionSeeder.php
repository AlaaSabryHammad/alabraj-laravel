<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EquipmentFuelConsumption;
use App\Models\Equipment;
use App\Models\User;
use Faker\Factory as Faker;

class EquipmentFuelConsumptionSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('ar_SA');

        // التأكد من وجود معدات ومستخدمين
        $equipment = Equipment::take(5)->get();
        $users = User::take(3)->get();

        if ($equipment->isEmpty() || $users->isEmpty()) {
            $this->command->warn('يجب إنشاء معدات ومستخدمين أولاً');
            return;
        }

        // بيانات تجريبية لاستهلاك المحروقات
        $consumptionData = [];

        foreach ($equipment as $eq) {
            // ديزل
            for ($i = 1; $i <= $faker->numberBetween(3, 6); $i++) {
                $consumptionData[] = [
                    'equipment_id' => $eq->id,
                    'user_id' => $users->random()->id,
                    'fuel_type' => 'diesel',
                    'quantity' => $faker->numberBetween(20, 100),
                    'consumption_date' => now()->subDays($faker->numberBetween(1, 30)),
                    'notes' => 'استهلاك ديزل - تشغيل عادي',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // زيت ماكينة
            if ($faker->numberBetween(1, 2) == 1) {
                $consumptionData[] = [
                    'equipment_id' => $eq->id,
                    'user_id' => $users->random()->id,
                    'fuel_type' => 'engine_oil',
                    'quantity' => $faker->numberBetween(5, 15),
                    'consumption_date' => now()->subDays($faker->numberBetween(1, 15)),
                    'notes' => 'تغيير زيت ماكينة',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // زيت هيدروليك
            if ($faker->numberBetween(1, 3) == 1) {
                $consumptionData[] = [
                    'equipment_id' => $eq->id,
                    'user_id' => $users->random()->id,
                    'fuel_type' => 'hydraulic_oil',
                    'quantity' => $faker->numberBetween(3, 8),
                    'consumption_date' => now()->subDays($faker->numberBetween(1, 20)),
                    'notes' => 'إضافة زيت هيدروليك',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // ماء ردياتير
            if ($faker->numberBetween(1, 4) == 1) {
                $consumptionData[] = [
                    'equipment_id' => $eq->id,
                    'user_id' => $users->random()->id,
                    'fuel_type' => 'radiator_water',
                    'quantity' => $faker->numberBetween(10, 25),
                    'consumption_date' => now()->subDays($faker->numberBetween(1, 10)),
                    'notes' => 'تعبئة ماء ردياتير',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        foreach ($consumptionData as $data) {
            EquipmentFuelConsumption::create($data);
        }

        $this->command->info('تم إنشاء ' . count($consumptionData) . ' سجل استهلاك محروقات تجريبي.');
    }
}

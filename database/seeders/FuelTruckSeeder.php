<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Equipment;
use App\Models\FuelTruck;

class FuelTruckSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إنشاء تانكر ديزل
        $dieselTruck = Equipment::create([
            'name' => 'تانكر دعم الوقود رقم 1',
            'type' => 'تانكر دعم الديزل',
            'model' => 'Mercedes Actros 2024',
            'manufacturer' => 'Mercedes-Benz',
            'serial_number' => 'TANK001',
            'status' => 'available',
            'location_id' => 1,
            'purchase_date' => now()->subMonths(6),
            'purchase_price' => 450000.00,
            'description' => 'سيارة تانكر لدعم المعدات بالمحروقات',
            'category' => 'support_vehicle',
            'user_id' => 1
        ]);

        FuelTruck::create([
            'equipment_id' => $dieselTruck->id,
            'fuel_type' => 'diesel',
            'capacity' => 10000.00,
            'current_quantity' => 8500.00,
            'notes' => 'تانكر محمل بالديزل جاهز للتوزيع'
        ]);

        // إنشاء تانكر بنزين
        $gasolineTruck = Equipment::create([
            'name' => 'تانكر دعم البنزين رقم 2',
            'type' => 'تانكر دعم البنزين',
            'model' => 'Volvo FH16 2023',
            'manufacturer' => 'Volvo',
            'serial_number' => 'TANK002',
            'status' => 'available',
            'location_id' => 1,
            'purchase_date' => now()->subMonths(4),
            'purchase_price' => 380000.00,
            'description' => 'سيارة تانكر لدعم المعدات بالبنزين',
            'category' => 'support_vehicle',
            'user_id' => 1
        ]);

        FuelTruck::create([
            'equipment_id' => $gasolineTruck->id,
            'fuel_type' => 'gasoline',
            'capacity' => 8000.00,
            'current_quantity' => 6200.00,
            'notes' => 'تانكر محمل بالبنزين'
        ]);

        // إنشاء تانكر زيوت
        $oilTruck = Equipment::create([
            'name' => 'تانكر الزيوت رقم 3',
            'type' => 'تانكر زيوت ماكينات',
            'model' => 'Scania R450 2023',
            'manufacturer' => 'Scania',
            'serial_number' => 'TANK003',
            'status' => 'available',
            'location_id' => 2,
            'purchase_date' => now()->subMonths(3),
            'purchase_price' => 420000.00,
            'description' => 'سيارة تانكر للزيوت والسوائل',
            'category' => 'support_vehicle',
            'user_id' => 1
        ]);

        FuelTruck::create([
            'equipment_id' => $oilTruck->id,
            'fuel_type' => 'engine_oil',
            'capacity' => 5000.00,
            'current_quantity' => 3200.00,
            'notes' => 'تانكر محمل بزيت الماكينات'
        ]);

        echo "تم إنشاء 3 تانكرات مع بيانات المحروقات بنجاح!\n";
    }
}

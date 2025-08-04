<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExternalTruck;

class ExternalTruckSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $trucks = [
            [
                'plate_number' => 'أ ب ج 1234',
                'driver_name' => 'أحمد محمد السعدي',
                'driver_phone' => '0551234567',
                'loading_type' => 'box',
                'capacity_volume' => 25.50,
                'capacity_weight' => null,
                'notes' => 'شاحنة حديثة للنقليات العامة',
                'status' => 'active'
            ],
            [
                'plate_number' => 'د ه و 5678',
                'driver_name' => 'خالد عبدالله الأحمد',
                'driver_phone' => '0509876543',
                'loading_type' => 'tank',
                'capacity_volume' => null,
                'capacity_weight' => 15.00,
                'notes' => 'تانك للمواد السائلة',
                'status' => 'active'
            ],
            [
                'plate_number' => 'ز ح ط 9012',
                'driver_name' => 'محمد صالح القحطاني',
                'driver_phone' => '0558765432',
                'loading_type' => 'box',
                'capacity_volume' => 30.00,
                'capacity_weight' => null,
                'notes' => 'شاحنة كبيرة للنقليات الثقيلة',
                'status' => 'maintenance'
            ],
            [
                'plate_number' => 'ي ك ل 3456',
                'driver_name' => 'عبدالرحمن فيصل النمر',
                'driver_phone' => '0554321098',
                'loading_type' => 'tank',
                'capacity_volume' => null,
                'capacity_weight' => 20.50,
                'notes' => 'تانك متخصص للمواد الكيميائية',
                'status' => 'active'
            ],
            [
                'plate_number' => 'م ن س 7890',
                'driver_name' => 'سعد إبراهيم الدوسري',
                'driver_phone' => '0556789012',
                'loading_type' => 'box',
                'capacity_volume' => 18.75,
                'capacity_weight' => null,
                'notes' => 'شاحنة متوسطة الحجم للنقليات المحلية',
                'status' => 'inactive'
            ]
        ];

        foreach ($trucks as $truck) {
            ExternalTruck::create($truck);
        }
    }
}

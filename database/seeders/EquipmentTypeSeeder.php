<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\EquipmentType;

class EquipmentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $equipmentTypes = [
            [
                'name' => 'حفارات',
                'description' => 'معدات الحفر والأعمال الترابية',
                'is_active' => true
            ],
            [
                'name' => 'بلدوزر',
                'description' => 'معدات الدفع والتسوية',
                'is_active' => true
            ],
            [
                'name' => 'رافعات',
                'description' => 'رافعات البرج والمتنقلة',
                'is_active' => true
            ],
            [
                'name' => 'شاحنات',
                'description' => 'شاحنات النقل والصهاريج',
                'is_active' => true
            ],
            [
                'name' => 'معدات خرسانة',
                'description' => 'خلاطات وأبراج الصب',
                'is_active' => true
            ],
            [
                'name' => 'معدات لحام',
                'description' => 'أجهزة اللحام والقطع',
                'is_active' => true
            ],
            [
                'name' => 'مولدات كهرباء',
                'description' => 'مولدات الطاقة الكهربائية',
                'is_active' => true
            ],
            [
                'name' => 'ضواغط هواء',
                'description' => 'ضواغط الهواء المتنقلة',
                'is_active' => true
            ],
            [
                'name' => 'أدوات يدوية',
                'description' => 'أدوات العمل اليدوية والكهربائية',
                'is_active' => true
            ],
            [
                'name' => 'معدات مساحة',
                'description' => 'أجهزة المساحة والقياس',
                'is_active' => false
            ]
        ];

        foreach ($equipmentTypes as $type) {
            EquipmentType::create($type);
        }
    }
}

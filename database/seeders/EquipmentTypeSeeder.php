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
        $types = [
            ['name' => 'حفار'],
            ['name' => 'رافعة'],
            ['name' => 'شاحنة'],
            ['name' => 'جرافة'],
            ['name' => 'سيارة'],
            ['name' => 'سيارة محروقات'],
            ['name' => 'خلاطة خرسانة'],
            ['name' => 'مولد كهربائي'],
            ['name' => 'ضاغط هواء'],
            ['name' => 'كرين'],
            ['name' => 'قلاب'],
            ['name' => 'آلة لحام'],
            ['name' => 'مضخة مياه'],
            ['name' => 'لودر'],
            ['name' => 'بكهو'],
            ['name' => 'فوركليفت'],
            ['name' => 'تراكتور'],
            ['name' => 'رصاصة'],
            ['name' => 'مدادة'],
        ];

        foreach ($types as $type) {
            EquipmentType::firstOrCreate($type);
        }
    }
}
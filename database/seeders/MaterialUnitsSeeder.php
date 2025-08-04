<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MaterialUnit;

class MaterialUnitsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = [
            // Volume units
            [
                'name' => 'متر مكعب',
                'symbol' => 'م³',
                'type' => 'volume',
                'description' => 'وحدة قياس الحجم',
                'is_active' => true,
            ],
            [
                'name' => 'لتر',
                'symbol' => 'لتر',
                'type' => 'volume',
                'description' => 'وحدة قياس السوائل',
                'is_active' => true,
            ],

            // Weight units
            [
                'name' => 'طن',
                'symbol' => 'طن',
                'type' => 'weight',
                'description' => 'وحدة قياس الوزن الثقيل',
                'is_active' => true,
            ],
            [
                'name' => 'كيلوجرام',
                'symbol' => 'كجم',
                'type' => 'weight',
                'description' => 'وحدة قياس الوزن',
                'is_active' => true,
            ],

            // Length units
            [
                'name' => 'متر',
                'symbol' => 'م',
                'type' => 'length',
                'description' => 'وحدة قياس الطول',
                'is_active' => true,
            ],
            [
                'name' => 'سنتيمتر',
                'symbol' => 'سم',
                'type' => 'length',
                'description' => 'وحدة قياس الطول الصغير',
                'is_active' => true,
            ],

            // Area units
            [
                'name' => 'متر مربع',
                'symbol' => 'م²',
                'type' => 'area',
                'description' => 'وحدة قياس المساحة',
                'is_active' => true,
            ],

            // Count units
            [
                'name' => 'قطعة',
                'symbol' => 'قطعة',
                'type' => 'count',
                'description' => 'وحدة العد',
                'is_active' => true,
            ],
            [
                'name' => 'كيس',
                'symbol' => 'كيس',
                'type' => 'count',
                'description' => 'وحدة عد الأكياس',
                'is_active' => true,
            ],
            [
                'name' => 'عبوة',
                'symbol' => 'عبوة',
                'type' => 'count',
                'description' => 'وحدة عد العبوات',
                'is_active' => true,
            ],
        ];

        foreach ($units as $unit) {
            MaterialUnit::firstOrCreate(
                ['name' => $unit['name']],
                $unit
            );
        }
    }
}

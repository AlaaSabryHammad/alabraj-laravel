<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LocationType;

class LocationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            ['name' => 'خلاطة'],
            ['name' => 'كسارة'],
            ['name' => 'موقع'],
            ['name' => 'مكتب'],
            ['name' => 'ورشة'],
            ['name' => 'مستودع'],
        ];

        foreach ($types as $type) {
            LocationType::firstOrCreate($type);
        }
    }
}
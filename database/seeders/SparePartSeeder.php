<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SparePart;
use App\Models\Location;
use App\Models\WarehouseInventory;

class SparePartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create some spare parts
        $spareParts = [
            [
                'code' => 'SP001',
                'name' => 'فلتر زيت المحرك',
                'unit_price' => 45.50,
                'category' => 'محرك',
                'description' => 'فلتر زيت عالي الجودة مناسب لمحركات الديزل'
            ],
            [
                'code' => 'SP002',
                'name' => 'فلتر الهواء',
                'unit_price' => 35.00,
                'category' => 'محرك',
                'description' => 'فلتر هواء قابل للغسيل لضمان أداء المحرك'
            ],
            [
                'code' => 'SP003',
                'name' => 'بطارية 12 فولت',
                'unit_price' => 250.00,
                'category' => 'كهرباء',
                'description' => 'بطارية جافة 12 فولت 100 أمبير'
            ],
            [
                'code' => 'SP004',
                'name' => 'زيت هيدروليك',
                'unit_price' => 85.00,
                'category' => 'هيدروليك',
                'description' => 'زيت هيدروليك ISO VG 46 - 20 لتر'
            ],
            [
                'code' => 'SP005',
                'name' => 'أقراص فرامل',
                'unit_price' => 180.00,
                'category' => 'فرامل',
                'description' => 'أقراص فرامل سيراميك مقاومة للحرارة'
            ]
        ];

        foreach ($spareParts as $sparePartData) {
            SparePart::create($sparePartData);
        }

        // Add some inventory to warehouses
        $warehouses = Location::where('type', 'مستودع')->get();
        $allSpareParts = SparePart::all();

        foreach ($warehouses as $warehouse) {
            foreach ($allSpareParts as $sparePart) {
                if (rand(0, 1)) { // 50% chance to add to warehouse
                    $quantity = rand(5, 50);
                    WarehouseInventory::create([
                        'location_id' => $warehouse->id,
                        'spare_part_id' => $sparePart->id,
                        'current_stock' => $quantity,
                        'available_stock' => $quantity,
                        'average_cost' => $sparePart->unit_price,
                        'total_value' => $quantity * $sparePart->unit_price,
                        'last_transaction_date' => now()->toDateString(),
                    ]);
                }
            }
        }
    }
}

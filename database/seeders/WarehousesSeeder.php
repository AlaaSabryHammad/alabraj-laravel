<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Warehouse;

class WarehousesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $warehouses = [
            [
                'name' => 'المخزن الرئيسي',
                'code' => 'MAIN-001',
                'description' => 'المخزن الرئيسي للشركة - يحتوي على معظم قطع الغيار والمعدات',
                'address' => 'المكتب الرئيسي للشركة',
                'manager' => 'أحمد محمد',
                'phone' => '01234567890',
                'status' => 'active'
            ],
            [
                'name' => 'مخزن المشاريع',
                'code' => 'PROJ-001',
                'description' => 'مخزن خاص بقطع الغيار المخصصة للمشاريع',
                'address' => 'موقع المشاريع',
                'manager' => 'سارة أحمد',
                'phone' => '01234567891',
                'status' => 'active'
            ],
            [
                'name' => 'مخزن القطع التالفة',
                'code' => 'DMG-001',
                'description' => 'مخزن خاص باستقبال وفرز القطع التالفة من المشاريع',
                'address' => 'منطقة الصيانة',
                'manager' => 'محمد علي',
                'phone' => '01234567892',
                'status' => 'active'
            ],
            [
                'name' => 'مخزن الطوارئ',
                'code' => 'EMR-001',
                'description' => 'مخزن قطع الغيار للحالات الطارئة',
                'address' => 'مكتب الطوارئ',
                'manager' => 'فاطمة حسن',
                'phone' => '01234567893',
                'status' => 'active'
            ]
        ];

        foreach ($warehouses as $warehouse) {
            Warehouse::create($warehouse);
        }
    }
}

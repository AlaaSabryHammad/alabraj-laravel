<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Location;
use App\Models\Employee;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $manager = Employee::first();
        
        $warehouses = [
            [
                'name' => 'مستودع الرياض الرئيسي',
                'type' => 'مستودع',
                'address' => 'الرياض - حي الصناعية الأولى',
                'city' => 'الرياض',
                'region' => 'منطقة الرياض',
                'status' => 'نشط',
                'contact_phone' => '0112345678',
                'description' => 'المستودع الرئيسي لقطع الغيار والمعدات في العاصمة'
            ],
            [
                'name' => 'مستودع جدة التجاري',
                'type' => 'مستودع',
                'address' => 'جدة - المنطقة الصناعية الثانية',
                'city' => 'جدة',
                'region' => 'منطقة مكة المكرمة',
                'status' => 'نشط',
                'contact_phone' => '0126789012',
                'description' => 'مستودع فرعي لخدمة المنطقة الغربية'
            ],
            [
                'name' => 'مستودع الدمام الشرقي',
                'type' => 'مستودع',
                'address' => 'الدمام - الحي الصناعي الثالث',
                'city' => 'الدمام',
                'region' => 'المنطقة الشرقية',
                'status' => 'نشط',
                'contact_phone' => '0133456789',
                'description' => 'مستودع لخدمة المنطقة الشرقية والخليج'
            ]
        ];

        foreach ($warehouses as $warehouse) {
            Location::create(array_merge($warehouse, [
                'manager_id' => $manager->id,
                'manager_name' => $manager->name,
                'coordinates' => '24.7136,' . (46.6753 + rand(-1, 1)),
                'area_size' => rand(500, 2000),
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}

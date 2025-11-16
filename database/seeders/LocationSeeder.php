<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Location;
use App\Models\LocationType;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Location::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $locationTypes = LocationType::all();
        $employees = Employee::all();

        // If no employees exist, skip seeding locations
        if ($employees->isEmpty()) {
            $this->command->info('⚠️ لا توجد موظفين في قاعدة البيانات، يتم تخطي إنشاء المواقع');
            return;
        }

        $locations = [
            ['name' => 'Main Office - Riyadh'],
            ['name' => 'Jeddah Branch'],
            ['name' => 'Dammam Logistics Hub'],
            ['name' => 'Project Site Alpha - Neom'],
            ['name' => 'Project Site Beta - Qiddiya'],
            ['name' => 'Al Khobar Warehouse'],
            ['name' => 'Yanbu Petrochemical Plant'],
            ['name' => 'Jubail Industrial Area'],
            ['name' => 'Makkah Expansion Project'],
            ['name' => 'Madinah High-Speed Rail Station'],
            ['name' => 'Tabuk Regional Airport'],
            ['name' => 'Abha Mountain Resort'],
            ['name' => 'Jizan Economic City'],
            ['name' => 'Hail Agricultural Project'],
            ['name' => 'Buraidah Central Market'],
            ['name' => 'Al-Qassim University Campus'],
            ['name' => 'King Abdullah Financial District'],
            ['name' => 'Red Sea Tourism Site 1'],
            ['name' => 'Red Sea Tourism Site 2'],
            ['name' => 'Diriyah Historical Site'],
            ['name' => 'AlUla Heritage Village'],
            ['name' => 'Amaala Luxury Resort'],
            ['name' => 'King Salman Energy Park (SPARK)'],
            ['name' => 'Riyadh Metro Line 1'],
            ['name' => 'Riyadh Metro Line 2'],
            ['name' => 'Jeddah Tower Construction Site'],
            ['name' => 'Soudah Peaks Project'],
            ['name' => 'King Fahd Causeway Authority'],
            ['name' => 'King Khalid Military City'],
            ['name' => 'Prince Sultan Air Base'],
            ['name' => 'Al-Ahsa Oasis Site'],
            ['name' => 'Taif Cable Car Station'],
            ['name' => 'Gassim Date Factory'],
            ['name' => 'Abha Dam'],
            ['name' => 'Najran Cement Factory'],
        ];

        foreach ($locations as $location) {
            $manager = $employees->random();
            Location::create([
                'name' => $location['name'],
                'location_type_id' => $locationTypes->random()->id,
                'manager_id' => $manager->id,
                'manager_name' => $manager->name,
                'status' => 'نشط',
                'city' => 'الرياض',
                'region' => 'منطقة الرياض',
                'address' => 'العنوان تلقائي - ' . $location['name'],
                'area_size' => rand(1000, 10000) . '.00',
            ]);
        }

        // إضافة مستودعات مخصصة
        $warehouseType = $locationTypes->where('name', 'مستودع')->first();

        if ($warehouseType) {
            $warehouseLocations = [
                [
                    'name' => 'مستودع الرياض المركزي',
                    'city' => 'الرياض',
                    'region' => 'منطقة الرياض',
                    'address' => 'المنطقة الصناعية الثانية، طريق الملك فهد',
                    'area_size' => 5000.00,
                ],
                [
                    'name' => 'مستودع جدة للإمدادات',
                    'city' => 'جدة',
                    'region' => 'منطقة مكة المكرمة',
                    'address' => 'المنطقة الصناعية الأولى، شارع المدينة',
                    'area_size' => 3500.00,
                ],
                [
                    'name' => 'مستودع الدمام الشرقي',
                    'city' => 'الدمام',
                    'region' => 'المنطقة الشرقية',
                    'address' => 'مجمع الدمام الصناعي، طريق الملك عبدالعزيز',
                    'area_size' => 4200.00,
                ],
                [
                    'name' => 'مستودع المواد الإنشائية - أبها',
                    'city' => 'أبها',
                    'region' => 'منطقة عسير',
                    'address' => 'حي النهضة، طريق الملك خالد',
                    'area_size' => 2800.00,
                ],
                [
                    'name' => 'مستودع قطع الغيار والمعدات',
                    'city' => 'الرياض',
                    'region' => 'منطقة الرياض',
                    'address' => 'حي الصناعية، شارع التخصصي',
                    'area_size' => 6000.00,
                ],
            ];

            foreach ($warehouseLocations as $warehouseData) {
                $manager = $employees->random();
                Location::create([
                    'name' => $warehouseData['name'],
                    'location_type_id' => $warehouseType->id,
                    'manager_id' => $manager->id,
                    'manager_name' => $manager->name,
                    'status' => 'نشط',
                    'city' => $warehouseData['city'],
                    'region' => $warehouseData['region'],
                    'address' => $warehouseData['address'],
                    'area_size' => $warehouseData['area_size'],
                    'contact_phone' => '0' . rand(500000000, 599999999),
                    'description' => 'مستودع مخصص لتخزين المواد والمعدات اللازمة للمشاريع',
                ]);
            }
        }
    }
}

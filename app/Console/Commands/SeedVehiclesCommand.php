<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Equipment;
use App\Models\ExternalTruck;
use App\Models\Location;
use App\Models\Supplier;
use App\Models\Employee;

class SeedVehiclesCommand extends Command
{
    protected $signature = 'seed:vehicles';
    protected $description = 'إضافة بيانات تجريبية للمركبات والمواقع';

    public function handle()
    {
        $this->info('بدء إضافة البيانات التجريبية...');

        // إضافة مواقع
        $this->seedLocations();

        // إضافة موظفين (سائقين)
        $this->seedDrivers();

        // إضافة معدات (مركبات داخلية)
        $this->seedEquipment();

        // إضافة موردين
        $this->seedSuppliers();

        // إضافة شاحنات خارجية
        $this->seedExternalTrucks();

        $this->info('تم إنجاز إضافة البيانات التجريبية بنجاح!');
    }

    private function seedLocations()
    {
        $this->info('إضافة المواقع...');

        $locations = [
            ['name' => 'مستودع الرياض الرئيسي', 'address' => 'حي الملز، الرياض', 'city' => 'الرياض', 'status' => 'active'],
            ['name' => 'موقع مشروع برج الفيصلية', 'address' => 'طريق الملك فهد، الرياض', 'city' => 'الرياض', 'status' => 'active'],
            ['name' => 'مستودع جدة', 'address' => 'حي الروضة، جدة', 'city' => 'جدة', 'status' => 'active'],
            ['name' => 'موقع مشروع الدمام', 'address' => 'طريق الملك عبدالعزيز، الدمام', 'city' => 'الدمام', 'status' => 'active'],
        ];

        foreach ($locations as $location) {
            Location::firstOrCreate(
                ['name' => $location['name']],
                $location
            );
        }
    }

    private function seedDrivers()
    {
        $this->info('إضافة السائقين...');

        $drivers = [
            [
                'name' => 'أحمد محمد علي',
                'phone' => '0501234567',
                'email' => 'ahmed.ali@example.com',
                'position' => 'سائق',
                'national_id' => '1234567890'
            ],
            [
                'name' => 'محمد عبدالله الأحمد',
                'phone' => '0507654321',
                'email' => 'mohamed.ahmed@example.com',
                'position' => 'سائق',
                'national_id' => '2345678901'
            ],
            [
                'name' => 'علي حسن محمد',
                'phone' => '0509876543',
                'email' => 'ali.hassan@example.com',
                'position' => 'سائق',
                'national_id' => '3456789012'
            ],
            [
                'name' => 'حسن عبدالرحمن',
                'phone' => '0503456789',
                'email' => 'hassan.abdul@example.com',
                'position' => 'سائق',
                'national_id' => '4567890123'
            ],
        ];

        foreach ($drivers as $driver) {
            Employee::firstOrCreate(
                ['phone' => $driver['phone']],
                array_merge($driver, [
                    'employee_id' => 'DRV' . rand(1000, 9999),
                    'department' => 'النقل',
                    'status' => 'active',
                    'salary' => 3000.00,
                    'hire_date' => now()->subDays(rand(30, 365)),
                    'address' => 'الرياض، المملكة العربية السعودية'
                ])
            );
        }
    }

    private function seedEquipment()
    {
        $this->info('إضافة المعدات...');

        $locations = Location::all();
        $drivers = Employee::where('position', 'سائق')->get();

        $equipment = [
            ['name' => 'شاحنة رقم 001', 'type' => 'شاحنة', 'model' => 'هيونداي HD120'],
            ['name' => 'شاحنة رقم 002', 'type' => 'شاحنة', 'model' => 'إيسوزو NPR'],
            ['name' => 'قلاب رقم 001', 'type' => 'قلاب', 'model' => 'هينو 500'],
            ['name' => 'قلاب رقم 002', 'type' => 'قلاب', 'model' => 'فولفو FM'],
            ['name' => 'خلاطة خرسانة 001', 'type' => 'خلاطة خرسانة', 'model' => 'مرسيدس أكتروس'],
        ];

        foreach ($equipment as $index => $item) {
            Equipment::firstOrCreate(
                ['name' => $item['name']],
                array_merge($item, [
                    'serial_number' => 'EQ' . rand(100000, 999999),
                    'manufacturer' => 'متنوع',
                    'status' => 'available',
                    'location_id' => $locations->random()->id,
                    'driver_id' => $drivers->random()->id ?? null,
                    'purchase_date' => now()->subDays(rand(365, 1095)),
                    'purchase_price' => rand(50000, 200000),
                ])
            );
        }
    }

    private function seedSuppliers()
    {
        $this->info('إضافة الموردين...');

        $suppliers = [
            ['name' => 'شركة النقل السريع', 'contact_person' => 'خالد الأحمد', 'phone' => '0112345678'],
            ['name' => 'مؤسسة النقل الذهبي', 'contact_person' => 'عبدالله محمد', 'phone' => '0118765432'],
            ['name' => 'شركة الشاحنات المتقدمة', 'contact_person' => 'سامي علي', 'phone' => '0119876543'],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::firstOrCreate(
                ['name' => $supplier['name']],
                array_merge($supplier, [
                    'email' => strtolower(str_replace(' ', '', $supplier['name'])) . '@example.com',
                    'address' => 'الرياض، المملكة العربية السعودية',
                    'status' => 'نشط'
                ])
            );
        }
    }

    private function seedExternalTrucks()
    {
        $this->info('إضافة الشاحنات الخارجية...');

        $suppliers = Supplier::all();

        $trucks = [
            ['plate_number' => 'أ ب ج 1234', 'driver_name' => 'فهد العتيبي', 'driver_phone' => '0551234567'],
            ['plate_number' => 'د هـ و 5678', 'driver_name' => 'سعد القحطاني', 'driver_phone' => '0557654321'],
            ['plate_number' => 'ز ح ط 9012', 'driver_name' => 'عبدالعزيز الدوسري', 'driver_phone' => '0559876543'],
            ['plate_number' => 'ي ك ل 3456', 'driver_name' => 'ماجد الحربي', 'driver_phone' => '0553456789'],
        ];

        foreach ($trucks as $truck) {
            ExternalTruck::firstOrCreate(
                ['plate_number' => $truck['plate_number']],
                array_merge($truck, [
                    'supplier_id' => $suppliers->random()->id,
                    'loading_type' => ['box', 'tank'][rand(0, 1)],
                    'capacity_weight' => rand(5, 20),
                    'capacity_volume' => rand(10, 50),
                    'daily_rate' => rand(300, 800),
                    'status' => 'active',
                    'contract_start_date' => now()->subDays(rand(30, 180)),
                    'contract_end_date' => now()->addDays(rand(180, 365)),
                ])
            );
        }
    }
}

<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Create a test internal truck record
$truck = new \App\Models\InternalTruck();
$truck->plate_number = 'INT-' . rand(100, 999);
$truck->model = 'فورد ترانزيت';
$truck->brand = 'فورد';
$truck->year = 2023;
$truck->color = 'أبيض';
$truck->engine_number = 'ENG' . rand(100000, 999999);
$truck->chassis_number = 'CHS' . rand(100000, 999999);
$truck->load_capacity = 3.5;
$truck->fuel_type = 'ديزل';
$truck->license_expiry = '2025-12-31';
$truck->insurance_expiry = '2025-08-31';
$truck->notes = 'شاحنة نقل داخلي للاستخدام في المشاريع';
$truck->status = 'متاح';
$truck->user_id = 1;
$truck->save();

echo "Test internal truck created with ID: " . $truck->id . "\n";

// Check if equipment record was created automatically
$equipment = \App\Models\Equipment::where('truck_id', $truck->id)->first();
if ($equipment) {
    echo "Equipment record automatically created with ID: " . $equipment->id . "\n";
    echo "Equipment name: " . $equipment->name . "\n";
    echo "Equipment category: " . $equipment->category . "\n";
} else {
    echo "No equipment record found!\n";
}

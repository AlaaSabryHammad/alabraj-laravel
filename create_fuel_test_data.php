<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

use App\Models\Equipment;
use App\Models\FuelTruck;

try {
    // إنشاء معدة تانكر
    $equipment = Equipment::create([
        'name' => 'تانكر دعم الوقود رقم 1',
        'type' => 'تانكر دعم الديزل',
        'model' => 'Mercedes Actros 2024',
        'manufacturer' => 'Mercedes-Benz',
        'serial_number' => 'TANK001',
        'status' => 'active',
        'location_id' => 1,
        'purchase_date' => now()->subMonths(6),
        'purchase_price' => 450000.00,
        'description' => 'سيارة تانكر لدعم المعدات بالمحروقات',
        'category' => 'support_vehicle',
        'user_id' => 1
    ]);

    echo "تم إنشاء معدة التانكر بنجاح - ID: {$equipment->id}" . PHP_EOL;

    // إنشاء سجل المحروقات للتانكر
    $fuelTruck = FuelTruck::create([
        'equipment_id' => $equipment->id,
        'fuel_type' => 'diesel',
        'capacity' => 10000.00,
        'current_quantity' => 8500.00,
        'notes' => 'تانكر محمل بالديزل جاهز للتوزيع'
    ]);

    echo "تم إنشاء سجل المحروقات بنجاح - ID: {$fuelTruck->id}" . PHP_EOL;

    // إنشاء تانكر ثاني للبنزين
    $equipment2 = Equipment::create([
        'name' => 'تانكر دعم البنزين رقم 2',
        'type' => 'تانكر دعم البنزين',
        'model' => 'Volvo FH16 2023',
        'manufacturer' => 'Volvo',
        'serial_number' => 'TANK002',
        'status' => 'active',
        'location_id' => 2,
        'purchase_date' => now()->subMonths(4),
        'purchase_price' => 380000.00,
        'description' => 'سيارة تانكر لدعم المعدات بالبنزين',
        'category' => 'support_vehicle',
        'user_id' => 1
    ]);

    $fuelTruck2 = FuelTruck::create([
        'equipment_id' => $equipment2->id,
        'fuel_type' => 'gasoline',
        'capacity' => 8000.00,
        'current_quantity' => 6200.00,
        'notes' => 'تانكر محمل بالبنزين'
    ]);

    echo "تم إنشاء التانكر الثاني بنجاح - Equipment ID: {$equipment2->id}, FuelTruck ID: {$fuelTruck2->id}" . PHP_EOL;
    echo "تم إنشاء البيانات التجريبية بنجاح!" . PHP_EOL;
} catch (Exception $e) {
    echo "خطأ: " . $e->getMessage() . PHP_EOL;
}

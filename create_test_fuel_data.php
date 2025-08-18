<?php
require_once 'vendor/autoload.php';
require_once 'bootstrap/app.php';

use App\Models\EquipmentFuelConsumption;
use Carbon\Carbon;

try {
    // Create test fuel consumption records
    EquipmentFuelConsumption::create([
        'equipment_id' => 1,
        'user_id' => 1,
        'fuel_type' => 'diesel',
        'quantity' => 85.75,
        'consumption_date' => Carbon::now()->subDays(2),
        'notes' => 'تعبئة ديزل للتشغيل اليومي',
    ]);

    EquipmentFuelConsumption::create([
        'equipment_id' => 1,
        'user_id' => 1,
        'fuel_type' => 'engine_oil',
        'quantity' => 12.00,
        'consumption_date' => Carbon::now()->subDays(7),
        'notes' => 'تغيير زيت الماكينة',
    ]);

    echo "تم إنشاء سجلات تجريبية بنجاح\n";
} catch (Exception $e) {
    echo "خطأ: " . $e->getMessage() . "\n";
}

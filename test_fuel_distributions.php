<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

use App\Models\FuelTruck;
use App\Models\FuelDistribution;

try {
    echo "=== فحص التانكرات ===\n";
    $fuelTrucks = FuelTruck::with('equipment')->get();

    foreach ($fuelTrucks as $truck) {
        echo "تانكر ID: {$truck->id} - معدة: {$truck->equipment->name}\n";

        $distributions = FuelDistribution::with(['targetEquipment', 'distributedBy'])
            ->where('fuel_truck_id', $truck->id)
            ->get();

        echo "  عدد التوزيعات: {$distributions->count()}\n";

        foreach ($distributions as $dist) {
            echo "    - توزيع ID: {$dist->id}, المعدة: {$dist->targetEquipment->name}, الكمية: {$dist->quantity}, الحالة: {$dist->approval_status}\n";
        }
        echo "\n";
    }
} catch (Exception $e) {
    echo "خطأ: " . $e->getMessage() . "\n";
}

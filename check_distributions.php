<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

use App\Models\FuelDistribution;

try {
    echo "=== فحص التوزيعات ===\n";
    $distributions = FuelDistribution::with(['targetEquipment', 'distributedBy', 'approvedBy'])
        ->orderBy('created_at', 'desc')
        ->get();

    echo "عدد التوزيعات الكلي: " . $distributions->count() . "\n";

    if ($distributions->count() > 0) {
        foreach ($distributions as $distribution) {
            echo "ID: {$distribution->id}\n";
            echo "التانكر: {$distribution->fuel_truck_id}\n";
            echo "المعدة المستهدفة: {$distribution->targetEquipment->name}\n";
            echo "الكمية: {$distribution->quantity} لتر\n";
            echo "حالة الموافقة: {$distribution->approval_status}\n";
            echo "الموزع: {$distribution->distributedBy->name}\n";
            echo "تاريخ التوزيع: {$distribution->distribution_date}\n";
            echo "---\n";
        }
    } else {
        echo "لا توجد توزيعات في قاعدة البيانات!\n";
    }
} catch (Exception $e) {
    echo 'خطأ: ' . $e->getMessage() . "\n";
}

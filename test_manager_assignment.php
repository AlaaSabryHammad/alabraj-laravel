<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Employee;
use App\Models\Location;

echo "اختبار تعيين المدير المباشر وتحديث الموقع:\n";
echo "======================================\n\n";

// Get employee with ID 5
$employee = Employee::find(5);
if (!$employee) {
    echo "الموظف رقم 5 غير موجود\n";
    exit;
}

echo "الموظف: {$employee->name}\n";
echo "الدور: {$employee->role}\n";
echo "الموقع الحالي: ";
if ($employee->location_id) {
    $currentLocation = Location::find($employee->location_id);
    echo $currentLocation ? $currentLocation->name : "موقع غير معروف (ID: {$employee->location_id})";
} else {
    echo "لا يوجد موقع مخصص";
}
echo "\n";

echo "المدير المباشر الحالي: ";
if ($employee->direct_manager_id) {
    $currentManager = Employee::find($employee->direct_manager_id);
    echo $currentManager ? $currentManager->name : "مدير غير معروف (ID: {$employee->direct_manager_id})";
} else {
    echo "لا يوجد مدير مباشر";
}
echo "\n\n";

// Get all potential managers (employees who can be managers)
echo "المدراء المحتملين:\n";
echo "----------------\n";

$potentialManagers = Employee::where('status', 'active')
    ->where('id', '!=', $employee->id)
    ->get();

foreach ($potentialManagers as $manager) {
    $locationText = $manager->location_id ? ($manager->location ? $manager->location->name : "موقع غير معروف (ID: {$manager->location_id})") : "لا يوجد موقع";
    echo "- {$manager->name} (الدور: {$manager->role}) - الموقع: {$locationText}\n";
}

echo "\n";

// Check locations
echo "المواقع المتاحة:\n";
echo "---------------\n";
$locations = Location::where('status', 'active')->get();
foreach ($locations as $location) {
    echo "- {$location->name} (ID: {$location->id})\n";
}

echo "\n";

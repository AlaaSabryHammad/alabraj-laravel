<?php

// Set up Laravel environment
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI'] = '/';

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$request = Illuminate\Http\Request::capture();
$response = $kernel->handle($request);

use App\Models\Employee;

echo "=== تحقق من معرفات المواقع ===\n\n";

$employees = Employee::with('location')->get();

foreach ($employees as $employee) {
    $locationInfo = $employee->location ?
        "الموقع: {$employee->location->name} (ID: {$employee->location->id})" :
        "الموقع: غير محدد";

    echo "- {$employee->name} (ID: {$employee->id}) - location_id: {$employee->location_id} - {$locationInfo}\n";
}

echo "\n=== اختبار التصفية لعماد الحميدي ===\n";

$emadEmployee = Employee::find(11); // عماد الحميدي
echo "عماد الحميدي - location_id: {$emadEmployee->location_id}\n";

if ($emadEmployee->location) {
    echo "موقع عماد: {$emadEmployee->location->name} (ID: {$emadEmployee->location->id})\n";
}

// Test filtering for Emad specifically
$query = Employee::where('status', 'active');
if ($emadEmployee->location_id) {
    $query->where('location_id', $emadEmployee->location_id);
}
$query->where('id', '!=', $emadEmployee->id);

$filteredEmployees = $query->get();
echo "\nالموظفين المفلترين لعماد:\n";
foreach ($filteredEmployees as $employee) {
    $locationInfo = $employee->location ?
        "الموقع: {$employee->location->name} (ID: {$employee->location->id})" :
        "الموقع: غير محدد";
    echo "- {$employee->name} - location_id: {$employee->location_id} - {$locationInfo}\n";
}

<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Location;
use App\Models\Employee;

echo "اختبار عرض الموظفين في الموقع:\n";
echo "===============================\n\n";

// Check location with ID 4
$location = Location::find(4);
if (!$location) {
    echo "الموقع رقم 4 غير موجود\n";
    exit;
}

echo "الموقع: {$location->name}\n";
echo "النوع: " . ($location->locationType ? $location->locationType->name : 'غير محدد') . "\n\n";

// Get employees assigned to this location
$employees = Employee::where('location_id', $location->id)
    ->where('status', 'active')
    ->get();

echo "الموظفين المسجلين في هذا الموقع:\n";
echo "--------------------------------\n";

if ($employees->count() > 0) {
    foreach ($employees as $employee) {
        echo "- {$employee->name} (الدور: " . App\Models\Employee::roleToArabic($employee->role) . ")\n";
        echo "  المنصب: " . ($employee->position ?? 'غير محدد') . "\n";
        echo "  القسم: " . ($employee->department ?? 'غير محدد') . "\n";
        echo "  الهاتف: " . ($employee->phone ?? 'غير محدد') . "\n";
        echo "  الحالة: {$employee->status}\n\n";
    }
} else {
    echo "لا يوجد موظفين مسجلين في هذا الموقع\n\n";

    // Show all employees and their locations
    echo "جميع الموظفين ومواقعهم:\n";
    echo "----------------------\n";
    $allEmployees = Employee::where('status', 'active')->with('location')->get();
    foreach ($allEmployees as $emp) {
        $locationName = $emp->location ? $emp->location->name : 'لا يوجد موقع';
        echo "- {$emp->name}: {$locationName}\n";
    }
}

echo "\n";

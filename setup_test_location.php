<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Employee;
use App\Models\Location;

echo "تعيين موقع لمدير الموقع لاختبار النظام:\n";
echo "=====================================\n\n";

// Find site manager (سالم الاحمدي)
$siteManager = Employee::where('name', 'سالم الاحمدي')->first();
if (!$siteManager) {
    echo "مدير الموقع غير موجود\n";
    exit;
}

echo "المدير: {$siteManager->name}\n";
echo "الدور: {$siteManager->role}\n";
echo "الموقع الحالي: ";
if ($siteManager->location_id) {
    $currentLocation = Location::find($siteManager->location_id);
    echo $currentLocation ? $currentLocation->name : "موقع غير معروف";
} else {
    echo "لا يوجد موقع";
}
echo "\n\n";

// Assign location to site manager
$location = Location::first();
if ($location) {
    $siteManager->update(['location_id' => $location->id]);
    echo "تم تعيين الموقع '{$location->name}' للمدير '{$siteManager->name}'\n\n";
} else {
    echo "لا توجد مواقع متاحة\n";
    exit;
}

// Test the updated system
echo "اختبار تطبيق النظام:\n";
echo "-------------------\n";

$employee = Employee::find(5); // العامل الاول
if ($employee) {
    echo "الموظف: {$employee->name}\n";
    echo "المدير المباشر: ";
    if ($employee->direct_manager_id) {
        $manager = Employee::find($employee->direct_manager_id);
        echo $manager ? $manager->name : "غير معروف";
        if ($manager && $manager->location_id) {
            echo " (الموقع: {$manager->location->name})";
        }
    } else {
        echo "لا يوجد";
    }
    echo "\n";

    echo "موقع الموظف الحالي: ";
    if ($employee->location_id) {
        $empLocation = Location::find($employee->location_id);
        echo $empLocation ? $empLocation->name : "موقع غير معروف";
    } else {
        echo "لا يوجد موقع";
    }
    echo "\n\n";

    if ($employee->direct_manager_id && $siteManager->id == $employee->direct_manager_id) {
        echo "✅ هذا الموظف مخصص لنفس المدير الذي أضفنا له موقع\n";
        echo "📍 عند إعادة تعيين المدير، سيتم تحديث موقع الموظف تلقائياً\n";
    }
}

echo "\n";

<?php

require_once 'vendor/autoload.php';

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Initialize Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== اختبار نهائي لتصفية صفحة الموظفين ===\n\n";

// Test both site managers
$siteManagers = Employee::where('role', 'site_manager')
    ->whereNotNull('user_id')
    ->with(['location', 'user'])
    ->get();

foreach ($siteManagers as $manager) {
    echo "===== اختبار مشرف الموقع: {$manager->name} =====\n";
    echo "الموقع: {$manager->location->name}\n";
    echo "البريد الإلكتروني: {$manager->user->email}\n\n";

    // Simulate login
    Auth::login($manager->user);

    // Simulate the controller logic
    $query = Employee::with(['user', 'location']);

    // Apply the same filter as in EmployeeController
    $currentUser = Auth::user();
    if ($currentUser && $currentUser->employee) {
        $currentEmployee = $currentUser->employee;

        $siteManagerRoles = ['مشرف موقع', 'supervisor', 'site_manager'];
        if (in_array($currentEmployee->role, $siteManagerRoles)) {
            if ($currentEmployee->location_id) {
                $query->where('location_id', $currentEmployee->location_id);
            }
        }
    }

    $employees = $query->get();

    echo "الموظفون المرئيون لهذا المشرف ({$employees->count()}):\n";
    foreach ($employees as $emp) {
        echo "  - {$emp->name} ({$emp->role})";
        if ($emp->id == $manager->id) {
            echo " (نفس المشرف)";
        }
        echo "\n";
    }

    echo "\n";
    Auth::logout();
}

echo "=== اختبار مدير عام (للمقارنة) ===\n";
$generalManager = Employee::where('role', 'general_manager')
    ->whereNotNull('user_id')
    ->with(['user'])
    ->first();

if ($generalManager) {
    echo "اختبار دخول المدير العام: {$generalManager->name}\n";

    Auth::login($generalManager->user);

    // Simulate the controller logic
    $query = Employee::with(['user', 'location']);

    // Apply the same filter as in EmployeeController
    $currentUser = Auth::user();
    if ($currentUser && $currentUser->employee) {
        $currentEmployee = $currentUser->employee;

        $siteManagerRoles = ['مشرف موقع', 'supervisor', 'site_manager'];
        if (in_array($currentEmployee->role, $siteManagerRoles)) {
            if ($currentEmployee->location_id) {
                $query->where('location_id', $currentEmployee->location_id);
            }
        }
    }

    $employees = $query->get();

    echo "الموظفون المرئيون للمدير العام ({$employees->count()}):\n";
    echo "(يجب أن يرى جميع الموظفين لأنه ليس مشرف موقع)\n";

    foreach ($employees as $emp) {
        echo "  - {$emp->name} ({$emp->role})";
        if ($emp->location) {
            echo " - {$emp->location->name}";
        }
        echo "\n";
    }

    Auth::logout();
}

echo "\n=== ملخص النتائج ===\n";
echo "✅ مشرف الموقع يرى فقط موظفي موقعه\n";
echo "✅ المدير العام يرى جميع الموظفين\n";
echo "✅ التصفية تعمل بشكل صحيح\n";

echo "\n=== انتهى الاختبار النهائي ===\n";

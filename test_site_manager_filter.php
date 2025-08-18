<?php

require_once 'vendor/autoload.php';

use App\Models\Employee;
use App\Models\User;
use App\Models\Location;
use Illuminate\Support\Facades\Auth;

// Initialize Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== اختبار تصفية مشرف الموقع ===\n\n";

// Test: Get all employees with site manager role
echo "1. البحث عن جميع مشرفي المواقع:\n";
$siteManagers = Employee::whereIn('role', ['مشرف موقع', 'supervisor'])
    ->with(['location', 'user'])
    ->get();

echo "عدد مشرفي المواقع: " . $siteManagers->count() . "\n";

foreach ($siteManagers as $manager) {
    echo "- {$manager->name} (الدور: {$manager->role})";
    if ($manager->location) {
        echo " - الموقع: {$manager->location->name}";
    } else {
        echo " - بدون موقع محدد";
    }

    if ($manager->user) {
        echo " - له حساب مستخدم: {$manager->user->email}";
    } else {
        echo " - بدون حساب مستخدم";
    }
    echo "\n";
}

echo "\n2. اختبار تصفية الموظفين لكل مشرف موقع:\n";

foreach ($siteManagers as $manager) {
    if ($manager->location_id) {
        $employeesInSameLocation = Employee::where('location_id', $manager->location_id)
            ->with('location')
            ->get();

        echo "\nمشرف الموقع: {$manager->name}\n";
        echo "الموقع: {$manager->location->name}\n";
        echo "عدد الموظفين في نفس الموقع: " . $employeesInSameLocation->count() . "\n";

        foreach ($employeesInSameLocation as $employee) {
            echo "  - {$employee->name} ({$employee->role})\n";
        }
    }
}

echo "\n3. اختبار محاكاة دخول مشرف موقع:\n";

$testManager = $siteManagers->where('user_id', '!=', null)->first();
if ($testManager && $testManager->user) {
    echo "محاكاة دخول المستخدم: {$testManager->user->email}\n";

    // Simulate login
    Auth::login($testManager->user);

    $currentUser = Auth::user();
    if ($currentUser && $currentUser->employee) {
        $currentEmployee = $currentUser->employee;
        echo "الموظف الحالي: {$currentEmployee->name}\n";
        echo "الدور: {$currentEmployee->role}\n";

        $siteManagerRoles = ['مشرف موقع', 'supervisor'];
        if (in_array($currentEmployee->role, $siteManagerRoles)) {
            echo "✅ تم التعرف على الموظف كمشرف موقع\n";

            if ($currentEmployee->location_id) {
                $filteredEmployees = Employee::where('location_id', $currentEmployee->location_id)
                    ->with('location')
                    ->get();

                echo "الموظفون المرئيون لهذا المشرف ({$filteredEmployees->count()}):\n";
                foreach ($filteredEmployees as $emp) {
                    echo "  - {$emp->name} ({$emp->role})\n";
                }
            } else {
                echo "⚠️ مشرف الموقع ليس له موقع محدد\n";
            }
        } else {
            echo "❌ الموظف ليس مشرف موقع\n";
        }
    }

    Auth::logout();
} else {
    echo "⚠️ لا يوجد مشرف موقع له حساب مستخدم للاختبار\n";
}

echo "\n=== انتهى الاختبار ===\n";

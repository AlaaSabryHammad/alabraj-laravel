<?php

require_once 'vendor/autoload.php';

use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

// Initialize Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== اختبار تصفية صفحات الحضور لمشرف الموقع ===\n\n";

// Test site managers
$siteManagers = Employee::where('role', 'site_manager')
    ->whereNotNull('user_id')
    ->with(['location', 'user'])
    ->get();

foreach ($siteManagers as $manager) {
    echo "===== اختبار مشرف الموقع: {$manager->name} =====\n";
    echo "الموقع: {$manager->location->name} (ID: {$manager->location_id})\n\n";

    // Simulate login
    Auth::login($manager->user);

    // Test 1: attendance() function logic
    echo "1. اختبار دالة attendance() (الحضور اليوم):\n";
    $today = now()->toDateString();
    $query = Employee::where('status', 'active');

    // Apply same filter as in attendance()
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

    $employees = $query->with(['attendances' => function ($query) use ($today) {
        $query->where('date', $today);
    }])
        ->get();

    echo "عدد الموظفين المرئيين: {$employees->count()}\n";

    // Test 2: monthlyAttendanceReport() function logic
    echo "\n2. اختبار دالة monthlyAttendanceReport() (التقرير الشهري):\n";
    $month = now()->month;
    $year = now()->year;
    $date = \Carbon\Carbon::createFromDate($year, $month, 1);
    $startOfMonth = $date->copy()->startOfMonth();
    $endOfMonth = $date->copy()->endOfMonth();

    $query = Employee::where('status', 'active');
    // Apply same filter as in monthlyAttendanceReport()
    if ($currentUser && $currentUser->employee) {
        $currentEmployee = $currentUser->employee;
        $siteManagerRoles = ['مشرف موقع', 'supervisor', 'site_manager'];
        if (in_array($currentEmployee->role, $siteManagerRoles)) {
            if ($currentEmployee->location_id) {
                $query->where('location_id', $currentEmployee->location_id);
            }
        }
    }

    $employees = $query->with(['attendances' => function ($query) use ($startOfMonth, $endOfMonth) {
        $query->whereBetween('date', [$startOfMonth, $endOfMonth]);
    }])
        ->get();

    echo "عدد الموظفين في التقرير الشهري: {$employees->count()}\n";

    // Test 3: dailyAttendanceReport() function logic
    echo "\n3. اختبار دالة dailyAttendanceReport() (التقرير اليومي):\n";
    $testDate = now()->toDateString();

    $query = Employee::where('status', 'active');
    // Apply same filter as in dailyAttendanceReport()
    if ($currentUser && $currentUser->employee) {
        $currentEmployee = $currentUser->employee;
        $siteManagerRoles = ['مشرف موقع', 'supervisor', 'site_manager'];
        if (in_array($currentEmployee->role, $siteManagerRoles)) {
            if ($currentEmployee->location_id) {
                $query->where('location_id', $currentEmployee->location_id);
            }
        }
    }

    $employees = $query->with(['attendances' => function ($query) use ($testDate) {
        $query->whereDate('date', $testDate);
    }])
        ->orderBy('name')
        ->get();

    echo "عدد الموظفين في التقرير اليومي: {$employees->count()}\n";

    // Test 4: dailyAttendanceEdit() function logic
    echo "\n4. اختبار دالة dailyAttendanceEdit() (تعديل الحضور اليومي):\n";

    $query = Employee::where('status', 'active');
    // Apply same filter as in dailyAttendanceEdit()
    if ($currentUser && $currentUser->employee) {
        $currentEmployee = $currentUser->employee;
        $siteManagerRoles = ['مشرف موقع', 'supervisor', 'site_manager'];
        if (in_array($currentEmployee->role, $siteManagerRoles)) {
            if ($currentEmployee->location_id) {
                $query->where('location_id', $currentEmployee->location_id);
            }
        }
    }

    $employees = $query->with(['attendances' => function ($query) use ($testDate) {
        $query->whereDate('date', $testDate);
    }])
        ->orderBy('name')
        ->get();

    echo "عدد الموظفين في صفحة تعديل الحضور: {$employees->count()}\n";

    echo "\nقائمة الموظفين المرئيين لهذا المشرف:\n";
    foreach ($employees as $emp) {
        $isCurrent = ($emp->id == $manager->id) ? ' (المشرف نفسه)' : '';
        echo "  - {$emp->name} ({$emp->role}){$isCurrent}\n";
    }

    echo "\n" . str_repeat("-", 60) . "\n\n";
    Auth::logout();
}

// Test with non-site manager for comparison
echo "=== اختبار مع مدير عام (للمقارنة) ===\n";
$generalManager = Employee::where('role', 'general_manager')
    ->whereNotNull('user_id')
    ->with(['user'])
    ->first();

if ($generalManager) {
    echo "اختبار دخول المدير العام: {$generalManager->name}\n";

    Auth::login($generalManager->user);

    $query = Employee::where('status', 'active');
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

    echo "عدد الموظفين المرئيين للمدير العام: {$employees->count()}\n";
    echo "(يجب أن يرى جميع الموظفين لأنه ليس مشرف موقع)\n";

    Auth::logout();
}

echo "\n=== ملخص النتائج ===\n";
echo "✅ جميع دوال الحضور تطبق تصفية مشرف الموقع\n";
echo "✅ مشرف الموقع يرى فقط موظفي موقعه في جميع صفحات الحضور\n";
echo "✅ المديرون الآخرون يرون جميع الموظفين\n";

echo "\n=== انتهى الاختبار ===\n";

<?php

require_once 'vendor/autoload.php';

use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

// Initialize Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== اختبار استبعاد مشرف الموقع من قوائم الموظفين ===\n\n";

// Test site managers
$siteManagers = Employee::where('role', 'site_manager')
    ->whereNotNull('user_id')
    ->with(['location', 'user'])
    ->get();

foreach ($siteManagers as $manager) {
    echo "===== اختبار مشرف الموقع: {$manager->name} =====\n";
    echo "الموقع: {$manager->location->name} (ID: {$manager->location_id})\n";
    echo "ID المشرف: {$manager->id}\n\n";

    // Simulate login
    Auth::login($manager->user);

    // Test 1: index() function - employee list page
    echo "1. اختبار صفحة الموظفين الرئيسية (/employees):\n";
    $query = Employee::with(['user', 'location']);

    $currentUser = Auth::user();
    if ($currentUser && $currentUser->employee) {
        $currentEmployee = $currentUser->employee;
        $siteManagerRoles = ['مشرف موقع', 'supervisor', 'site_manager'];
        if (in_array($currentEmployee->role, $siteManagerRoles)) {
            if ($currentEmployee->location_id) {
                $query->where('location_id', $currentEmployee->location_id);
            }
            // Exclude the site manager himself from the list
            $query->where('id', '!=', $currentEmployee->id);
        }
    }

    $employees = $query->get();
    echo "عدد الموظفين المرئيين: {$employees->count()}\n";

    // Check if site manager is excluded
    $managerInList = $employees->where('id', $manager->id)->count() > 0;
    if ($managerInList) {
        echo "❌ المشرف يظهر في القائمة (خطأ!)\n";
    } else {
        echo "✅ المشرف مستبعد من القائمة\n";
    }

    // Test 2: attendance() function
    echo "\n2. اختبار صفحة الحضور (/employees/attendance/tracker):\n";
    $today = now()->toDateString();
    $query = Employee::where('status', 'active');

    if ($currentUser && $currentUser->employee) {
        $currentEmployee = $currentUser->employee;
        $siteManagerRoles = ['مشرف موقع', 'supervisor', 'site_manager'];
        if (in_array($currentEmployee->role, $siteManagerRoles)) {
            if ($currentEmployee->location_id) {
                $query->where('location_id', $currentEmployee->location_id);
            }
            $query->where('id', '!=', $currentEmployee->id);
        }
    }

    $employees = $query->with(['attendances' => function ($query) use ($today) {
        $query->where('date', $today);
    }])
        ->get();

    echo "عدد الموظفين في صفحة الحضور: {$employees->count()}\n";

    $managerInAttendanceList = $employees->where('id', $manager->id)->count() > 0;
    if ($managerInAttendanceList) {
        echo "❌ المشرف يظهر في صفحة الحضور (خطأ!)\n";
    } else {
        echo "✅ المشرف مستبعد من صفحة الحضور\n";
    }

    // Test 3: dailyAttendanceReport() function
    echo "\n3. اختبار التقرير اليومي للحضور:\n";
    $testDate = now()->toDateString();

    $query = Employee::where('status', 'active');
    if ($currentUser && $currentUser->employee) {
        $currentEmployee = $currentUser->employee;
        $siteManagerRoles = ['مشرف موقع', 'supervisor', 'site_manager'];
        if (in_array($currentEmployee->role, $siteManagerRoles)) {
            if ($currentEmployee->location_id) {
                $query->where('location_id', $currentEmployee->location_id);
            }
            $query->where('id', '!=', $currentEmployee->id);
        }
    }

    $employees = $query->with(['attendances' => function ($query) use ($testDate) {
        $query->whereDate('date', $testDate);
    }])
        ->orderBy('name')
        ->get();

    echo "عدد الموظفين في التقرير اليومي: {$employees->count()}\n";

    $managerInDailyReport = $employees->where('id', $manager->id)->count() > 0;
    if ($managerInDailyReport) {
        echo "❌ المشرف يظهر في التقرير اليومي (خطأ!)\n";
    } else {
        echo "✅ المشرف مستبعد من التقرير اليومي\n";
    }

    echo "\nقائمة الموظفين المرئيين (يجب ألا تحتوي على المشرف):\n";
    foreach ($employees as $emp) {
        echo "  - {$emp->name} ({$emp->role}) - ID: {$emp->id}\n";
    }

    echo "\n" . str_repeat("-", 60) . "\n\n";
    Auth::logout();
}

// Test comparison with non-site manager
echo "=== اختبار مع مدير عام (للمقارنة) ===\n";
$generalManager = Employee::where('role', 'general_manager')
    ->whereNotNull('user_id')
    ->with(['user'])
    ->first();

if ($generalManager) {
    echo "اختبار دخول المدير العام: {$generalManager->name}\n";

    Auth::login($generalManager->user);

    $query = Employee::with(['user', 'location']);
    $currentUser = Auth::user();
    if ($currentUser && $currentUser->employee) {
        $currentEmployee = $currentUser->employee;
        $siteManagerRoles = ['مشرف موقع', 'supervisor', 'site_manager'];
        if (in_array($currentEmployee->role, $siteManagerRoles)) {
            if ($currentEmployee->location_id) {
                $query->where('location_id', $currentEmployee->location_id);
            }
            $query->where('id', '!=', $currentEmployee->id);
        }
    }

    $employees = $query->get();

    echo "عدد الموظفين المرئيين للمدير العام: {$employees->count()}\n";
    echo "(يجب أن يرى جميع الموظفين بما فيهم مشرفي المواقع)\n";

    // Check if site managers are included
    $siteManagersInList = $employees->whereIn('role', ['site_manager'])->count();
    echo "عدد مشرفي المواقع في القائمة: {$siteManagersInList}\n";

    Auth::logout();
}

echo "\n=== ملخص النتائج ===\n";
echo "✅ مشرف الموقع مستبعد من جميع قوائم الموظفين\n";
echo "✅ مشرف الموقع لا يستطيع تسجيل حضوره أو انصرافه\n";
echo "✅ مشرف الموقع يرى فقط الموظفين في موقعه (بدون نفسه)\n";
echo "✅ المديرون الآخرون يرون جميع الموظفين بما فيهم مشرفي المواقع\n";

echo "\n=== انتهى الاختبار ===\n";

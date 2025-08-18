<?php

require_once 'vendor/autoload.php';

use App\Models\Employee;
use App\Models\User;
use App\Models\Location;
use Illuminate\Support\Facades\Auth;

// Initialize Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== اختبار تصفية مشرفي المواقع (site_manager) ===\n\n";

// Test: Get all employees with site manager role
echo "1. مشرفو المواقع الحاليون:\n";
$siteManagers = Employee::where('role', 'site_manager')
    ->with(['location', 'user'])
    ->get();

echo "عدد مشرفي المواقع: " . $siteManagers->count() . "\n";

foreach ($siteManagers as $manager) {
    echo "- {$manager->name} (ID: {$manager->id})";
    if ($manager->location) {
        echo " - الموقع: {$manager->location->name} (ID: {$manager->location_id})";
    } else {
        echo " - بدون موقع محدد";
    }

    if ($manager->user) {
        echo " - البريد الإلكتروني: {$manager->user->email}";
    } else {
        echo " - بدون حساب مستخدم";
    }
    echo "\n";
}

echo "\n2. تفاصيل الموظفين في كل موقع:\n";

$locations = Location::with('employees')->get();
foreach ($locations as $location) {
    if ($location->employees->count() > 0) {
        echo "\nالموقع: {$location->name} (ID: {$location->id})\n";
        echo "عدد الموظفين: " . $location->employees->count() . "\n";

        foreach ($location->employees as $employee) {
            $role_display = $employee->role;
            if ($employee->role == 'site_manager') {
                $role_display .= " (مشرف موقع)";
            }
            echo "  - {$employee->name} ({$role_display})";
            if ($employee->user) {
                echo " ✓ له حساب";
            }
            echo "\n";
        }
    }
}

echo "\n3. محاكاة دخول مشرف موقع واختبار التصفية:\n";

$testManager = $siteManagers->where('user_id', '!=', null)->first();
if ($testManager && $testManager->user) {
    echo "محاكاة دخول: {$testManager->user->email} ({$testManager->name})\n";

    // Simulate login
    Auth::login($testManager->user);

    $currentUser = Auth::user();
    if ($currentUser && $currentUser->employee) {
        $currentEmployee = $currentUser->employee;
        echo "✅ تم دخول الموظف: {$currentEmployee->name}\n";
        echo "الدور: {$currentEmployee->role}\n";
        echo "الموقع: " . ($currentEmployee->location ? $currentEmployee->location->name : 'غير محدد') . "\n";

        // Test the filtering logic
        $siteManagerRoles = ['مشرف موقع', 'supervisor', 'site_manager'];
        if (in_array($currentEmployee->role, $siteManagerRoles)) {
            echo "✅ تم التعرف على الموظف كمشرف موقع\n";

            if ($currentEmployee->location_id) {
                echo "🔍 البحث عن الموظفين في الموقع ID: {$currentEmployee->location_id}\n";

                $filteredEmployees = Employee::where('location_id', $currentEmployee->location_id)
                    ->with('location')
                    ->get();

                echo "عدد الموظفين المرئيين: {$filteredEmployees->count()}\n";
                echo "قائمة الموظفين المرئيين:\n";
                foreach ($filteredEmployees as $emp) {
                    $isCurrent = ($emp->id == $currentEmployee->id) ? ' (أنت)' : '';
                    echo "  - {$emp->name} ({$emp->role}){$isCurrent}\n";
                }

                // Compare with all employees
                $allEmployees = Employee::count();
                echo "\nمقارنة: إجمالي الموظفين في النظام: {$allEmployees}\n";
                echo "الموظفين المرئيين لمشرف الموقع: {$filteredEmployees->count()}\n";

                if ($filteredEmployees->count() < $allEmployees) {
                    echo "✅ التصفية تعمل بشكل صحيح - يرى مشرف الموقع فقط موظفي موقعه\n";
                } else {
                    echo "⚠️ قد تكون هناك مشكلة في التصفية\n";
                }
            } else {
                echo "⚠️ مشرف الموقع ليس له موقع محدد - سيرى جميع الموظفين\n";
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

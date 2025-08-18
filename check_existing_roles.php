<?php

require_once 'vendor/autoload.php';

use App\Models\Employee;

// Initialize Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== الأدوار الموجودة في النظام ===\n\n";

// Get all distinct roles
$roles = Employee::distinct()->pluck('role')->filter()->sort();

echo "جميع الأدوار الموجودة:\n";
foreach ($roles as $role) {
    $count = Employee::where('role', $role)->count();
    echo "- {$role}: {$count} موظف\n";
}

echo "\n=== البحث عن أي دور يحتوي على كلمة 'مشرف' ===\n";
$supervisorRoles = Employee::where('role', 'like', '%مشرف%')
    ->orWhere('role', 'like', '%supervisor%')
    ->distinct()
    ->pluck('role')
    ->filter();

if ($supervisorRoles->count() > 0) {
    echo "الأدوار التي تحتوي على 'مشرف':\n";
    foreach ($supervisorRoles as $role) {
        $count = Employee::where('role', $role)->count();
        echo "- {$role}: {$count} موظف\n";
    }
} else {
    echo "لا توجد أدوار تحتوي على كلمة 'مشرف'\n";
}

echo "\n=== الموظفين الذين لديهم حساب مستخدم ===\n";
$employeesWithUser = Employee::whereNotNull('user_id')
    ->with(['user', 'location'])
    ->get();

echo "عدد الموظفين الذين لديهم حساب: " . $employeesWithUser->count() . "\n";

foreach ($employeesWithUser as $employee) {
    echo "- {$employee->name} (الدور: {$employee->role})";
    if ($employee->location) {
        echo " - الموقع: {$employee->location->name}";
    }
    echo "\n";
}

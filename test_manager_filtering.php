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

echo "=== اختبار فلتر المدير ===\n\n";

// Check for managers in the database
$managerVariants = Employee::variantsForArabic('مدير');
echo "البحث عن أدوار المدير: " . implode(', ', $managerVariants) . "\n\n";

$managers = Employee::whereIn('role', $managerVariants)->get();
echo "المديرين الموجودين:\n";
foreach ($managers as $manager) {
    echo "- {$manager->name} (ID: {$manager->id}) - الدور: {$manager->role}\n";
}

if ($managers->count() > 0) {
    echo "\n=== اختبار تصفية المدير ===\n";

    $testManager = $managers->first();
    echo "اختبار مع المدير: {$testManager->name}\n\n";

    // Simulate manager filtering logic
    $query = Employee::where('status', 'active');

    // Manager filter: show all employees except managers and general managers
    $excludedRoles = array_merge(
        Employee::variantsForArabic('مدير'),
        Employee::variantsForArabic('مدير عام')
    );
    $query->whereNotIn('role', $excludedRoles);

    $filteredEmployees = $query->get();

    echo "الأدوار المستبعدة: " . implode(', ', $excludedRoles) . "\n\n";
    echo "عدد الموظفين المرئيين للمدير: " . $filteredEmployees->count() . "\n\n";
    echo "الموظفين المرئيين:\n";

    foreach ($filteredEmployees as $employee) {
        $locationName = $employee->location ? $employee->location->name : 'غير محدد';
        echo "- {$employee->name} ({$employee->role}) - الموقع: {$locationName}\n";
    }

    echo "\n=== الموظفين المستبعدين (المديرين) ===\n";
    $excludedEmployees = Employee::whereIn('role', $excludedRoles)->get();
    foreach ($excludedEmployees as $employee) {
        echo "- {$employee->name} ({$employee->role}) [مستبعد]\n";
    }
} else {
    echo "\nلا يوجد مديرين في قاعدة البيانات.\n";
    echo "سأتحقق من جميع الأدوار الموجودة:\n";

    $allRoles = Employee::select('role')->distinct()->pluck('role');
    foreach ($allRoles as $role) {
        echo "- {$role}\n";
    }
}

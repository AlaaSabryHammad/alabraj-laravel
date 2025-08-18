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

echo "=== اختبار فلتر مدير المشاريع ===\n\n";

// Check for project managers in the database
$projectManagerVariants = Employee::variantsForArabic('مدير مشاريع');
echo "البحث عن أدوار مدير المشاريع: " . implode(', ', $projectManagerVariants) . "\n\n";

$projectManagers = Employee::whereIn('role', $projectManagerVariants)->get();
echo "مديري المشاريع الموجودين:\n";

if ($projectManagers->count() > 0) {
    foreach ($projectManagers as $manager) {
        echo "- {$manager->name} (ID: {$manager->id}) - الدور: {$manager->role}\n";
    }

    echo "\n=== اختبار تصفية مدير المشاريع ===\n";

    $testManager = $projectManagers->first();
    echo "اختبار مع مدير المشاريع: {$testManager->name}\n\n";

    // Simulate project manager filtering logic
    $query = Employee::where('status', 'active');

    // Project Manager filter: show all employees except managers and general managers
    $excludedRoles = array_merge(
        Employee::variantsForArabic('مدير'),
        Employee::variantsForArabic('مدير عام')
    );
    $query->whereNotIn('role', $excludedRoles);

    $filteredEmployees = $query->get();

    echo "الأدوار المستبعدة: " . implode(', ', $excludedRoles) . "\n\n";
    echo "عدد الموظفين المرئيين لمدير المشاريع: " . $filteredEmployees->count() . "\n\n";
    echo "الموظفين المرئيين:\n";

    foreach ($filteredEmployees as $employee) {
        $locationName = $employee->location ? $employee->location->name : 'غير محدد';
        echo "- {$employee->name} ({$employee->role}) - الموقع: {$locationName}\n";
    }

    echo "\n=== الموظفين المستبعدين (الإدارة العليا) ===\n";
    $excludedEmployees = Employee::whereIn('role', $excludedRoles)->get();
    foreach ($excludedEmployees as $employee) {
        echo "- {$employee->name} ({$employee->role}) [مستبعد]\n";
    }
} else {
    echo "لا يوجد مديري مشاريع في قاعدة البيانات.\n";
    echo "سأقوم بإنشاء مدير مشاريع تجريبي...\n\n";

    // Create a test project manager
    $testProjectManager = Employee::create([
        'name' => 'أحمد مدير المشاريع',
        'email' => 'ahmed.projectmanager@example.com',
        'role' => 'project_manager',
        'position' => 'مدير مشاريع',
        'department' => 'إدارة المشاريع',
        'phone' => '1234567890',
        'status' => 'active'
    ]);

    echo "تم إنشاء مدير المشاريع: {$testProjectManager->name} (ID: {$testProjectManager->id})\n\n";

    echo "=== اختبار تصفية مدير المشاريع الجديد ===\n";

    // Test filtering with new project manager
    $query = Employee::where('status', 'active');

    $excludedRoles = array_merge(
        Employee::variantsForArabic('مدير'),
        Employee::variantsForArabic('مدير عام')
    );
    $query->whereNotIn('role', $excludedRoles);

    $filteredEmployees = $query->get();

    echo "عدد الموظفين المرئيين لمدير المشاريع: " . $filteredEmployees->count() . "\n\n";
    echo "الموظفين المرئيين:\n";

    foreach ($filteredEmployees as $employee) {
        $locationName = $employee->location ? $employee->location->name : 'غير محدد';
        echo "- {$employee->name} ({$employee->role}) - الموقع: {$locationName}\n";
    }
}

echo "\n=== جميع الأدوار في النظام ===\n";
$allRoles = Employee::select('role')->distinct()->pluck('role');
foreach ($allRoles as $role) {
    $count = Employee::where('role', $role)->count();
    echo "- {$role} ({$count} موظف)\n";
}

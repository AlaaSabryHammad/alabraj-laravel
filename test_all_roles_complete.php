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
use App\Models\Project;
use App\Models\Location;

echo "=== اختبار شامل لتصفية جميع الأدوار (مع مدير المشاريع) ===\n\n";

// Test different user roles
$testUsers = [
    ['name' => 'محمد الشهراني', 'role' => 'general_manager', 'id' => 1],
    ['name' => 'علاء صبري حماد رضوان', 'role' => 'manager', 'id' => 2],
    ['name' => 'خالد جمال منصور', 'role' => 'project_manager', 'id' => 12],
    ['name' => 'محمد السمادوني', 'role' => 'engineer', 'id' => 4],
    ['name' => 'سالم الاحمدي', 'role' => 'site_manager', 'id' => 3],
    ['name' => 'عماد الحميدي', 'role' => 'site_manager', 'id' => 7]
];

foreach ($testUsers as $testUser) {
    echo "=== المستخدم: {$testUser['name']} ({$testUser['role']}) ===\n";

    $currentEmployee = Employee::find($testUser['id']);
    if (!$currentEmployee) {
        echo "الموظف غير موجود!\n\n";
        continue;
    }

    echo "الموقع: " . ($currentEmployee->location ? $currentEmployee->location->name : 'غير محدد') . "\n";

    // Simulate the filtering logic from the controller
    $query = Employee::where('status', 'active');

    // Site manager filter
    $siteManagerRoles = ['مشرف موقع', 'supervisor', 'site_manager'];
    if (in_array($currentEmployee->role, $siteManagerRoles)) {
        echo "نوع التصفية: مشرف موقع\n";
        if ($currentEmployee->location_id) {
            $query->where('location_id', $currentEmployee->location_id);
        }
        $query->where('id', '!=', $currentEmployee->id);
    }

    // Engineer filter
    $engineerVariants = Employee::variantsForArabic('مهندس');
    if (in_array($currentEmployee->role, $engineerVariants)) {
        echo "نوع التصفية: مهندس\n";
        $managedProjectIds = Project::where('project_manager_id', $currentEmployee->id)->pluck('id');

        if ($managedProjectIds->isNotEmpty()) {
            $projectLocationIds = Location::whereIn('project_id', $managedProjectIds)->pluck('id');

            if ($projectLocationIds->isNotEmpty()) {
                $query->whereIn('location_id', $projectLocationIds);
            } else {
                $query->where('id', 0);
            }
        } else {
            $query->where('id', 0);
        }
    }

    // Manager filter
    $managerVariants = Employee::variantsForArabic('مدير');
    if (in_array($currentEmployee->role, $managerVariants)) {
        echo "نوع التصفية: مدير\n";
        $excludedRoles = array_merge(
            Employee::variantsForArabic('مدير'),
            Employee::variantsForArabic('مدير عام')
        );
        $query->whereNotIn('role', $excludedRoles);
    }

    // Project Manager filter
    $projectManagerVariants = Employee::variantsForArabic('مدير مشاريع');
    if (in_array($currentEmployee->role, $projectManagerVariants)) {
        echo "نوع التصفية: مدير مشاريع\n";
        $excludedRoles = array_merge(
            Employee::variantsForArabic('مدير'),
            Employee::variantsForArabic('مدير عام')
        );
        $query->whereNotIn('role', $excludedRoles);
    }

    // General manager - no filter applied
    $generalManagerVariants = Employee::variantsForArabic('مدير عام');
    if (in_array($currentEmployee->role, $generalManagerVariants)) {
        echo "نوع التصفية: مدير عام (بدون تصفية)\n";
    }

    // Get filtered employees
    $employees = $query->get();

    echo "عدد الموظفين المرئيين: " . $employees->count() . "\n";
    echo "الموظفين:\n";

    foreach ($employees as $employee) {
        $locationName = $employee->location ? $employee->location->name : 'غير محدد';
        echo "- {$employee->name} ({$employee->role}) - الموقع: {$locationName}\n";
    }

    echo "\n" . str_repeat("-", 50) . "\n\n";
}

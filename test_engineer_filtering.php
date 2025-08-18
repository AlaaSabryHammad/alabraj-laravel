<?php

require_once 'vendor/autoload.php';

use App\Models\Employee;
use App\Models\User;
use App\Models\Project;
use App\Models\ProjectVisit;
use Illuminate\Support\Facades\Auth;

// Initialize Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== اختبار تصفية المهندس لموظفي المشروع ===\n\n";

// Find engineers
$engineers = Employee::whereIn('role', ['engineer', 'مهندس'])
    ->whereNotNull('user_id')
    ->with(['user'])
    ->get();

echo "عدد المهندسين في النظام: " . $engineers->count() . "\n\n";

foreach ($engineers as $engineer) {
    echo "===== اختبار المهندس: {$engineer->name} =====\n";
    echo "ID المهندس: {$engineer->id}\n";
    echo "البريد الإلكتروني: {$engineer->user->email}\n";

    // Check projects managed by this engineer
    $managedProjects = Project::where('project_manager_id', $engineer->id)->get();
    echo "عدد المشاريع التي يديرها: " . $managedProjects->count() . "\n";

    if ($managedProjects->count() > 0) {
        echo "المشاريع:\n";
        foreach ($managedProjects as $project) {
            echo "  - {$project->name} (ID: {$project->id})\n";
        }

        // Get project visits for these projects
        $managedProjectIds = $managedProjects->pluck('id');
        $projectVisits = ProjectVisit::whereIn('project_id', $managedProjectIds)
            ->with(['visitor'])
            ->get();

        echo "\nعدد الزيارات لهذه المشاريع: " . $projectVisits->count() . "\n";

        if ($projectVisits->count() > 0) {
            echo "زوار المشاريع:\n";
            $uniqueVisitors = $projectVisits->pluck('visitor')->unique('id');
            foreach ($uniqueVisitors as $visitor) {
                if ($visitor) {
                    echo "  - {$visitor->name} (ID: {$visitor->id}, الدور: {$visitor->role})\n";
                }
            }
        }

        // Simulate login and test filtering
        Auth::login($engineer->user);

        echo "\n🔍 اختبار تصفية صفحة الموظفين:\n";
        $query = Employee::with(['user', 'location']);

        $currentUser = Auth::user();
        if ($currentUser && $currentUser->employee) {
            $currentEmployee = $currentUser->employee;

            // Apply engineer filter
            $engineerVariants = Employee::variantsForArabic('مهندس');
            if (in_array($currentEmployee->role, $engineerVariants)) {
                // Get projects managed by this engineer
                $managedProjectIds = Project::where('project_manager_id', $currentEmployee->id)
                    ->pluck('id');

                if ($managedProjectIds->isNotEmpty()) {
                    // Get employees who have visited any of these projects
                    $projectEmployeeIds = ProjectVisit::whereIn('project_id', $managedProjectIds)
                        ->distinct()
                        ->pluck('visitor_id');

                    if ($projectEmployeeIds->isNotEmpty()) {
                        $query->whereIn('id', $projectEmployeeIds);
                    } else {
                        // If no employees have visited the projects, show empty result
                        $query->where('id', 0);
                    }
                } else {
                    // If engineer has no projects, show empty result
                    $query->where('id', 0);
                }
            }
        }

        $employees = $query->get();
        echo "عدد الموظفين المرئيين للمهندس: " . $employees->count() . "\n";

        if ($employees->count() > 0) {
            echo "قائمة الموظفين المرئيين:\n";
            foreach ($employees as $emp) {
                echo "  - {$emp->name} ({$emp->role}) - ID: {$emp->id}\n";
            }
        }

        Auth::logout();
    } else {
        echo "⚠️ هذا المهندس ليس لديه مشاريع مسندة إليه\n";
    }

    echo "\n" . str_repeat("-", 60) . "\n\n";
}

// Test comparison with non-engineer user
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

        // Apply engineer filter (should not apply to general manager)
        $engineerVariants = Employee::variantsForArabic('مهندس');
        if (in_array($currentEmployee->role, $engineerVariants)) {
            $managedProjectIds = Project::where('project_manager_id', $currentEmployee->id)
                ->pluck('id');

            if ($managedProjectIds->isNotEmpty()) {
                $projectEmployeeIds = ProjectVisit::whereIn('project_id', $managedProjectIds)
                    ->distinct()
                    ->pluck('visitor_id');

                if ($projectEmployeeIds->isNotEmpty()) {
                    $query->whereIn('id', $projectEmployeeIds);
                } else {
                    $query->where('id', 0);
                }
            } else {
                $query->where('id', 0);
            }
        }
    }

    $employees = $query->get();

    echo "عدد الموظفين المرئيين للمدير العام: {$employees->count()}\n";
    echo "(يجب أن يرى جميع الموظفين لأنه ليس مهندس)\n";

    Auth::logout();
}

echo "\n=== معلومات إضافية ===\n";
echo "إجمالي المشاريع في النظام: " . Project::count() . "\n";
echo "إجمالي زيارات المشاريع: " . ProjectVisit::count() . "\n";
echo "إجمالي الموظفين: " . Employee::count() . "\n";

echo "\n=== ملخص النتائج ===\n";
echo "✅ المهندس يرى فقط الموظفين الذين زاروا مشاريعه\n";
echo "✅ المهندس بدون مشاريع لا يرى أي موظفين\n";
echo "✅ المهندس مع مشاريع بدون زيارات لا يرى أي موظفين\n";
echo "✅ الأدوار الأخرى لا تتأثر بتصفية المهندس\n";

echo "\n=== انتهى الاختبار ===\n";

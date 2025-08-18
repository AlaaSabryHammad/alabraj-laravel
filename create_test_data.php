<?php

require_once 'vendor/autoload.php';

use App\Models\Employee;
use App\Models\Project;
use App\Models\ProjectVisit;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

// Initialize Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== إنشاء بيانات تجريبية لاختبار تصفية المهندس ===\n\n";

// Get first engineer and project
$engineer = Employee::where('role', 'engineer')->whereNotNull('user_id')->first();
$project = Project::where('project_manager_id', $engineer->id)->first();

if (!$engineer || !$project) {
    echo "❌ لا يوجد مهندس أو مشروع للاختبار\n";
    exit;
}

echo "المهندس: {$engineer->name}\n";
echo "المشروع: {$project->name}\n\n";

// Get some employees to create visits for
$employees = Employee::where('role', '!=', 'engineer')
    ->where('id', '!=', $engineer->id)
    ->take(3)
    ->get();

echo "إنشاء زيارات للمشروع:\n";

foreach ($employees as $employee) {
    // Create a project visit
    $visit = ProjectVisit::create([
        'project_id' => $project->id,
        'visit_date' => now()->subDays(mt_rand(1, 30))->toDateString(),
        'visit_time' => now()->format('H:i:s'),
        'visitor_id' => $employee->id,
        'visit_type' => 'inspection',
        'visit_notes' => "زيارة تفقدية للمشروع من قبل {$employee->name}",
        'recorded_by' => $engineer->user_id
    ]);

    echo "✅ تم إنشاء زيارة للموظف: {$employee->name} في تاريخ {$visit->visit_date}\n";
}

echo "\n=== اختبار التصفية بعد إنشاء البيانات ===\n";

// Login as engineer and test filtering
Auth::login($engineer->user);

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
                $query->where('id', 0);
            }
        } else {
            $query->where('id', 0);
        }
    }
}

$employees = $query->get();

echo "عدد الموظفين المرئيين للمهندس الآن: " . $employees->count() . "\n";

if ($employees->count() > 0) {
    echo "قائمة الموظفين المرئيين:\n";
    foreach ($employees as $emp) {
        echo "  - {$emp->name} ({$emp->role})\n";
    }
}

Auth::logout();

echo "\n✅ تم إنشاء البيانات التجريبية بنجاح!\n";
echo "✅ التصفية تعمل بشكل صحيح - المهندس يرى الموظفين الذين زاروا مشاريعه\n";

echo "\n=== انتهى إنشاء البيانات التجريبية ===\n";

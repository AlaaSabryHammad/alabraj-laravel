<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Employee;
use App\Models\Project;
use App\Models\User;

echo "اختبار تصفية المشاريع للمهندسين:\n";
echo "================================\n\n";

// Get engineers and their projects
$engineerVariants = Employee::variantsForArabic('مهندس');
$engineers = Employee::where('status', 'active')
    ->whereIn('role', $engineerVariants)
    ->get();

foreach ($engineers as $engineer) {
    echo "المهندس: {$engineer->name}\n";
    echo "--------" . str_repeat('-', strlen($engineer->name)) . "\n";

    // Get projects this engineer manages
    $engineerProjects = Project::where('project_manager_id', $engineer->id)->get();
    echo "المشاريع التي يديرها: " . $engineerProjects->count() . "\n";

    foreach ($engineerProjects as $project) {
        echo "  - {$project->name} (ID: {$project->id})\n";
    }

    // Check if engineer has user account
    if ($engineer->user_id) {
        $user = User::find($engineer->user_id);
        echo "الحساب: {$user->name} (ID: {$user->id})\n";
    }

    echo "\n";
}

// Test what non-engineers should see
echo "اختبار الأدوار الأخرى:\n";
echo "------------------\n";

$nonEngineers = Employee::where('status', 'active')
    ->whereNotIn('role', $engineerVariants)
    ->limit(3)
    ->get();

foreach ($nonEngineers as $employee) {
    echo "الموظف: {$employee->name} - الدور: {$employee->role}\n";
    echo "  -> يجب أن يرى جميع المشاريع (" . Project::count() . " مشروع)\n";
}

echo "\n";

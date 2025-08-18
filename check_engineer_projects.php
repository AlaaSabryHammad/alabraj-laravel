<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Employee;
use App\Models\Project;

echo "اختبار تصفية المشاريع للمهندسين:\n";
echo "================================\n\n";

// Get all engineers
$engineerVariants = Employee::variantsForArabic('مهندس');
$engineers = Employee::where('status', 'active')
    ->whereIn('role', $engineerVariants)
    ->get();

echo "المهندسين النشطين: " . $engineers->count() . "\n\n";

// Check projects for each engineer
foreach ($engineers as $engineer) {
    echo "المهندس: {$engineer->name}\n";
    echo "--------" . str_repeat('-', strlen($engineer->name)) . "\n";

    // Count total projects
    $totalProjects = Project::count();
    echo "إجمالي المشاريع في النظام: {$totalProjects}\n";

    // Count projects managed by this engineer
    $engineerProjects = Project::where('project_manager_id', $engineer->id)->count();
    echo "المشاريع التي يديرها هذا المهندس: {$engineerProjects}\n";

    if ($engineerProjects > 0) {
        $projects = Project::where('project_manager_id', $engineer->id)->get();
        echo "أسماء المشاريع:\n";
        foreach ($projects as $project) {
            echo "  - {$project->name}\n";
        }
    }

    echo "\n";
}

// Check if there are users linked to engineers
echo "فحص ربط المهندسين بالمستخدمين:\n";
echo "-------------------------------\n";
foreach ($engineers as $engineer) {
    $user = $engineer->user_id ? \App\Models\User::find($engineer->user_id) : null;
    echo "المهندس: {$engineer->name} - ";
    if ($user) {
        echo "مربوط بالمستخدم: {$user->name} (ID: {$user->id})\n";
    } else {
        echo "غير مربوط بأي مستخدم\n";
    }
}

echo "\n";

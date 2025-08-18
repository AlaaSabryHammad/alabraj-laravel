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

echo "=== اختبار التصفية الجديدة للمهندسين ===\n\n";

// Get engineers 
$engineers = Employee::whereIn('role', Employee::variantsForArabic('مهندس'))->get();

echo "المهندسين الموجودين:\n";
foreach ($engineers as $engineer) {
    echo "- {$engineer->name} (ID: {$engineer->id})\n";
}
echo "\n";

foreach ($engineers as $engineer) {
    echo "=== المهندس: {$engineer->name} ===\n";

    // Get projects managed by this engineer
    $managedProjects = Project::where('project_manager_id', $engineer->id)->get();

    echo "المشاريع التي يديرها:\n";
    if ($managedProjects->count() > 0) {
        foreach ($managedProjects as $project) {
            echo "- {$project->name} (ID: {$project->id})\n";
        }
    } else {
        echo "- لا يدير أي مشاريع\n";
    }
    echo "\n";

    if ($managedProjects->count() > 0) {
        $managedProjectIds = $managedProjects->pluck('id');

        // Get all locations under these projects
        $projectLocations = Location::whereIn('project_id', $managedProjectIds)->get();

        echo "المواقع التابعة للمشاريع:\n";
        foreach ($projectLocations as $location) {
            echo "- {$location->name} (ID: {$location->id}) - مشروع: {$location->project->name}\n";
        }
        echo "\n";

        if ($projectLocations->count() > 0) {
            $projectLocationIds = $projectLocations->pluck('id');

            // Get employees in these locations
            $employees = Employee::whereIn('location_id', $projectLocationIds)->get();

            echo "الموظفين في هذه المواقع:\n";
            foreach ($employees as $employee) {
                $locationName = $employee->location ? $employee->location->name : 'غير محدد';
                echo "- {$employee->name} ({$employee->role}) - الموقع: {$locationName}\n";
            }
            echo "إجمالي الموظفين: " . $employees->count() . "\n";
        } else {
            echo "لا توجد مواقع تابعة للمشاريع\n";
        }
    }

    echo "\n" . str_repeat("-", 50) . "\n\n";
}

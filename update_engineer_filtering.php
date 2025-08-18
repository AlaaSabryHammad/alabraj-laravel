<?php

// Script to update all engineer filtering logic in EmployeeController

$file = 'app/Http/Controllers/EmployeeController.php';
$content = file_get_contents($file);

// Old pattern to find
$oldPattern = 'Get employees who have visited any of these projects
                    $projectEmployeeIds = \App\Models\ProjectVisit::whereIn(\'project_id\', $managedProjectIds)
                        ->distinct()
                        ->pluck(\'visitor_id\');

                    if ($projectEmployeeIds->isNotEmpty()) {
                        $query->whereIn(\'id\', $projectEmployeeIds);
                    } else {
                        // If no employees have visited the projects, show empty result
                        $query->where(\'id\', 0);
                    }';

// New pattern to replace with
$newPattern = 'Get all locations under these projects
                    $projectLocationIds = \App\Models\Location::whereIn(\'project_id\', $managedProjectIds)
                        ->pluck(\'id\');

                    if ($projectLocationIds->isNotEmpty()) {
                        // Show employees in these locations
                        $query->whereIn(\'location_id\', $projectLocationIds);
                    } else {
                        // If no locations in managed projects, show empty result
                        $query->where(\'id\', 0);
                    }';

// Replace all occurrences
$updatedContent = str_replace($oldPattern, $newPattern, $content);

// Also replace the comment
$updatedContent = str_replace(
    '// Get employees who have visited any of these projects',
    '// Get all locations under these projects',
    $updatedContent
);

// Write back to file
file_put_contents($file, $updatedContent);

echo "تم تحديث جميع الدوال في EmployeeController بنجاح!\n";

// Count how many replacements were made
$replacementCount = substr_count($content, 'ProjectVisit::whereIn') - substr_count($updatedContent, 'ProjectVisit::whereIn');
echo "تم استبدال {$replacementCount} موضع\n";

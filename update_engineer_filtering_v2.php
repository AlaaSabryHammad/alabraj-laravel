<?php

// Script to update all engineer filtering logic in EmployeeController

$file = 'app/Http/Controllers/EmployeeController.php';
$content = file_get_contents($file);

echo "المحتوى الأصلي:\n";
echo "عدد مرات ظهور ProjectVisit: " . substr_count($content, 'ProjectVisit::whereIn') . "\n\n";

// Pattern 1: Replace ProjectVisit logic with Location logic
$content = preg_replace(
    '/\$projectEmployeeIds = \\\\App\\\\Models\\\\ProjectVisit::whereIn\(\'project_id\', \$managedProjectIds\)\s*->distinct\(\)\s*->pluck\(\'visitor_id\'\);/',
    '$projectLocationIds = \\App\\Models\\Location::whereIn(\'project_id\', $managedProjectIds)
                        ->pluck(\'id\');',
    $content
);

// Pattern 2: Replace the if condition
$content = preg_replace(
    '/if \(\$projectEmployeeIds->isNotEmpty\(\)\) \{\s*\$query->whereIn\(\'id\', \$projectEmployeeIds\);\s*\} else \{\s*\/\/ If no employees have visited the projects, show empty result\s*\$query->where\(\'id\', 0\);\s*\}/',
    'if ($projectLocationIds->isNotEmpty()) {
                            // Show employees in these locations
                            $query->whereIn(\'location_id\', $projectLocationIds);
                        } else {
                            // If no locations in managed projects, show empty result
                            $query->where(\'id\', 0);
                        }',
    $content
);

// Pattern 3: Update comments
$content = str_replace(
    '// Get employees who have visited any of these projects',
    '// Get all locations under these projects',
    $content
);

// Write back to file
file_put_contents($file, $content);

echo "تم تحديث الملف بنجاح!\n";
echo "المحتوى الجديد:\n";
$newContent = file_get_contents($file);
echo "عدد مرات ظهور ProjectVisit: " . substr_count($newContent, 'ProjectVisit::whereIn') . "\n";
echo "عدد مرات ظهور Location::whereIn: " . substr_count($newContent, 'Location::whereIn') . "\n";

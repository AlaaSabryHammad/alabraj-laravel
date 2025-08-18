<?php

// Script to add project manager filter to all functions in EmployeeController

$file = 'app/Http/Controllers/EmployeeController.php';
$content = file_get_contents($file);

echo "إضافة فلتر مدير المشاريع لجميع الدوال...\n";

// Pattern to find: Manager filter block and add project manager filter after it
$searchPattern = '/(\s+\/\/ Manager filter[^}]+\}\s+)\}\s+(\s+\$employees = \$query->with)/';

$replacementPattern = '$1
            // Project Manager filter: show all employees except managers and general managers
            $projectManagerVariants = Employee::variantsForArabic(\'مدير مشاريع\');
            if (in_array($currentEmployee->role, $projectManagerVariants)) {
                // Exclude managers and general managers
                $excludedRoles = array_merge(
                    Employee::variantsForArabic(\'مدير\'),
                    Employee::variantsForArabic(\'مدير عام\')
                );
                $query->whereNotIn(\'role\', $excludedRoles);
            }
        }

$2';

$updatedContent = preg_replace($searchPattern, $replacementPattern, $content);

// Write back to file
file_put_contents($file, $updatedContent);

echo "تم تحديث EmployeeController بنجاح!\n";

// Count occurrences
$newCount = substr_count($updatedContent, "// Project Manager filter");
echo "تم إضافة فلتر مدير المشاريع في {$newCount} موضع\n";

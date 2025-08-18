<?php

// Script to add manager filter to remaining functions in EmployeeController

$file = 'app/Http/Controllers/EmployeeController.php';
$content = file_get_contents($file);

echo "إضافة فلتر المدير لباقي الدوال...\n";

// Pattern to find: the closing of engineer filtering before } and before $employees = 
$searchPattern = '/(\s+)\}\s+\}\s+(\s+\$employees = \$query->with)/';

$replacementPattern = '$1}

            // Manager filter: show all employees except managers and general managers
            $managerVariants = Employee::variantsForArabic(\'مدير\');
            if (in_array($currentEmployee->role, $managerVariants)) {
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
$originalCount = preg_match_all($searchPattern, $content);
$newCount = substr_count($updatedContent, "// Manager filter");
echo "تم إضافة فلتر المدير في {$newCount} موضع\n";

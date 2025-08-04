<?php

$filePath = 'resources/views/employees/print.blade.php';
$content = file_get_contents($filePath);

// Replace all occurrences of isPast() calls for passport
$content = preg_replace(
    '/\$employee->passport_expiry_date && \$employee->passport_expiry_date->isPast\(\)/',
    '$employee->isDateExpired(\'passport_expiry_date\')',
    $content
);

// Replace all occurrences of diffInDays() calls for passport
$content = preg_replace(
    '/\$employee->passport_expiry_date && \$employee->passport_expiry_date->diffInDays\(\)/',
    '$employee->getDaysUntilExpiry(\'passport_expiry_date\')',
    $content
);

// Replace all occurrences of round(diffInDays()) calls for passport
$content = preg_replace(
    '/round\(\$employee->passport_expiry_date->diffInDays\(\)\)/',
    '$employee->getDaysUntilExpiry(\'passport_expiry_date\')',
    $content
);

// Replace all occurrences of isPast() calls for work_permit
$content = preg_replace(
    '/\$employee->work_permit_expiry_date && \$employee->work_permit_expiry_date->isPast\(\)/',
    '$employee->isDateExpired(\'work_permit_expiry_date\')',
    $content
);

// Replace all occurrences of diffInDays() calls for work_permit
$content = preg_replace(
    '/\$employee->work_permit_expiry_date && round\(\$employee->work_permit_expiry_date->diffInDays\(\)\)/',
    '$employee->getDaysUntilExpiry(\'work_permit_expiry_date\')',
    $content
);

// Replace all occurrences of round(diffInDays()) calls for work_permit
$content = preg_replace(
    '/round\(\$employee->work_permit_expiry_date->diffInDays\(\)\)/',
    '$employee->getDaysUntilExpiry(\'work_permit_expiry_date\')',
    $content
);

// Replace all occurrences of isPast() calls for driving_license
$content = preg_replace(
    '/\$employee->driving_license_expiry_date && \$employee->driving_license_expiry_date->isPast\(\)/',
    '$employee->isDateExpired(\'driving_license_expiry_date\')',
    $content
);

// Replace all occurrences of diffInDays() calls for driving_license
$content = preg_replace(
    '/\$employee->driving_license_expiry_date && round\(\$employee->driving_license_expiry_date->diffInDays\(\)\)/',
    '$employee->getDaysUntilExpiry(\'driving_license_expiry_date\')',
    $content
);

// Replace all occurrences of round(diffInDays()) calls for driving_license
$content = preg_replace(
    '/round\(\$employee->driving_license_expiry_date->diffInDays\(\)\)/',
    '$employee->getDaysUntilExpiry(\'driving_license_expiry_date\')',
    $content
);

file_put_contents($filePath, $content);

echo "Fixed all date method calls in print.blade.php\n";

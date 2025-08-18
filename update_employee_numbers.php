<?php

require_once 'bootstrap/app.php';

use App\Models\Employee;

// Get all employees with empty employee numbers
$employees = Employee::whereNull('employee_number')
    ->orWhere('employee_number', '')
    ->get();

echo "Found {$employees->count()} employees with empty employee numbers\n";

$updated = 0;
foreach ($employees as $employee) {
    $employee->employee_number = str_pad($employee->id, 3, '0', STR_PAD_LEFT);
    $employee->save();
    $updated++;

    if ($updated % 50 == 0) {
        echo "Updated {$updated} employees...\n";
    }
}

echo "Successfully updated {$updated} employees with employee numbers\n";

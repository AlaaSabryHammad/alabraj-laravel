<?php
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Check if employee 147 exists
$employee = DB::table('employees')->where('id', 147)->first();

if ($employee) {
    echo "Employee 147 details:\n";
    echo "Name: " . $employee->name . "\n";
    echo "Email: " . $employee->email . "\n";
    echo "Role: " . $employee->role . "\n";
    echo "Status: " . $employee->status . "\n";
    echo "Sponsorship: " . ($employee->sponsorship ?? 'NULL') . "\n";
    echo "Category: " . ($employee->category ?? 'NULL') . "\n";
    echo "Position: " . ($employee->position ?? 'NULL') . "\n";
    echo "Department: " . ($employee->department ?? 'NULL') . "\n";
} else {
    echo "Employee 147 not found\n";
}

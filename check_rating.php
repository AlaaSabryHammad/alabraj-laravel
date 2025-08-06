<?php
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Check employee 147 rating
$employee = DB::table('employees')->where('id', 147)->first();

if ($employee) {
    echo "Employee 147 rating details:\n";
    echo "Rating: " . $employee->rating . "\n";
    echo "Rating type: " . gettype($employee->rating) . "\n";
    
    // Check the rating column structure
    $columns = DB::select("DESCRIBE employees");
    foreach ($columns as $column) {
        if ($column->Field === 'rating') {
            echo "Rating column type: " . $column->Type . "\n";
            break;
        }
    }
}

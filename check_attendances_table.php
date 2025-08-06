<?php
require_once 'bootstrap/app.php';
$app = app();
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== Attendances Table Structure ===" . PHP_EOL;

try {
    $columns = DB::select('SHOW COLUMNS FROM attendances');
    foreach ($columns as $column) {
        echo "- {$column->Field} ({$column->Type})" . PHP_EOL;
    }
    echo PHP_EOL;
    
    // Check if overtime_hours column exists
    $hasOvertimeColumn = false;
    foreach ($columns as $column) {
        if ($column->Field === 'overtime_hours') {
            $hasOvertimeColumn = true;
            break;
        }
    }
    
    if (!$hasOvertimeColumn) {
        echo "❌ overtime_hours column does NOT exist in attendances table!" . PHP_EOL;
        echo "We need to add this column to track overtime hours." . PHP_EOL;
    } else {
        echo "✅ overtime_hours column exists in attendances table!" . PHP_EOL;
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
}

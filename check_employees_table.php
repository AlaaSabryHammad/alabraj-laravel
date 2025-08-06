<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    $columns = DB::select("DESCRIBE employees");
    echo "أعمدة جدول الموظفين:\n";
    foreach ($columns as $column) {
        echo "- {$column->Field} ({$column->Type})\n";
    }
} catch (Exception $e) {
    echo "خطأ: " . $e->getMessage() . "\n";
}

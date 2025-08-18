<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\DB;

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Illuminate\Http\Request::createFromGlobals();
$response = $kernel->handle($request);

try {
    DB::connection()->getPdo();
    echo "Database connected successfully!\n";

    $columns = DB::select("DESCRIBE employees");

    echo "Driving license related columns in employees table:\n";
    foreach ($columns as $column) {
        if (strpos($column->Field, 'driving') !== false) {
            echo "  - " . $column->Field . " (" . $column->Type . ")\n";
        }
    }

    echo "\nAll columns containing 'license':\n";
    foreach ($columns as $column) {
        if (strpos($column->Field, 'license') !== false) {
            echo "  - " . $column->Field . " (" . $column->Type . ")\n";
        }
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

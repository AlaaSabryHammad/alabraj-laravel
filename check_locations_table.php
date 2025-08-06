<?php
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Get table structure
$columns = DB::select('DESCRIBE locations');

echo "Locations table columns:\n";
foreach($columns as $column) {
    echo "- " . $column->Field . " (" . $column->Type . ")\n";
}

echo "\nTotal columns: " . count($columns) . "\n";

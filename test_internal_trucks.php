<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\DB;

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Illuminate\Http\Request::createFromGlobals();
$response = $kernel->handle($request);

// Test database connection
try {
    DB::connection()->getPdo();
    echo "Database connected successfully!\n";

    // Check if the columns exist
    $columns = DB::select("DESCRIBE internal_trucks");
    $columnNames = array_column($columns, 'Field');

    echo "Columns in internal_trucks table:\n";
    foreach ($columnNames as $column) {
        echo "  - $column\n";
    }

    // Check for the specific columns that were missing
    $hasWarrantyExpiry = in_array('warranty_expiry', $columnNames);
    $hasLastMaintenance = in_array('last_maintenance', $columnNames);

    echo "\nMissing columns status:\n";
    echo "  - warranty_expiry: " . ($hasWarrantyExpiry ? "✅ EXISTS" : "❌ MISSING") . "\n";
    echo "  - last_maintenance: " . ($hasLastMaintenance ? "✅ EXISTS" : "❌ MISSING") . "\n";

    if ($hasWarrantyExpiry && $hasLastMaintenance) {
        echo "\n🎉 All required columns are present! The InternalTruck model should work now.\n";
    } else {
        echo "\n❌ Some columns are still missing.\n";
    }
} catch (Exception $e) {
    echo "Database error: " . $e->getMessage() . "\n";
}

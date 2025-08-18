<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

use Illuminate\Support\Facades\DB;
use App\Models\Employee;

try {
    $columns = collect(DB::select('DESCRIBE employees'))->pluck('Field')->toArray();

    echo "Checking for missing columns in employees table:\n";

    $employeeModel = new Employee();
    $fillableFields = $employeeModel->getFillable();

    $missingColumns = [];
    foreach ($fillableFields as $field) {
        if (!in_array($field, $columns)) {
            $missingColumns[] = $field;
            echo "MISSING: " . $field . "\n";
        }
    }

    if (empty($missingColumns)) {
        echo "✅ All fillable fields exist in the database!\n";
    } else {
        echo "\n❌ Found " . count($missingColumns) . " missing columns.\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

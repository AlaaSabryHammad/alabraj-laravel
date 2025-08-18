<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    $columns = collect(DB::select('DESCRIBE users'))->pluck('Field')->toArray();
    echo "Users table columns:\n";
    foreach ($columns as $column) {
        echo "- $column\n";
    }

    if (in_array('avatar', $columns)) {
        echo "\n✅ Avatar column exists\n";
    } else {
        echo "\n❌ Avatar column missing - need to create migration\n";
    }

    if (in_array('phone', $columns)) {
        echo "✅ Phone column exists\n";
    } else {
        echo "❌ Phone column missing - need to create migration\n";
    }
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage() . "\n";
}

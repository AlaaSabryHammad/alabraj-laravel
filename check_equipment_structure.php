<?php
require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;

// إعداد Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== هيكل جدول equipment ===\n\n";

$columns = DB::select('DESCRIBE equipment');
foreach ($columns as $col) {
    echo "{$col->Field} - {$col->Type} - " .
        ($col->Null == 'YES' ? 'NULL' : 'NOT NULL') .
        " - Default: " . ($col->Default ?? 'NULL') . "\n";
}

<?php
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    DB::statement('DROP TABLE IF EXISTS damaged_parts_receipts');
    echo 'تم حذف الجدول بنجاح' . PHP_EOL;
} catch (Exception $e) {
    echo 'خطأ: ' . $e->getMessage() . PHP_EOL;
}

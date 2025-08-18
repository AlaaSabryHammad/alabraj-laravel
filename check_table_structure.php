<?php

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Illuminate\Http\Request::capture();
$response = $kernel->handle($request);

echo "=== بنية جدول المستخدمين ===\n";
$columns = DB::select('DESCRIBE users');
foreach ($columns as $column) {
    echo $column->Field . ' | ' . $column->Type . "\n";
}

echo "\n=== بنية جدول الموظفين ===\n";
$empColumns = DB::select('DESCRIBE employees');
foreach ($empColumns as $column) {
    echo $column->Field . ' | ' . $column->Type . "\n";
}

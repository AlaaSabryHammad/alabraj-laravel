<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

use App\Models\Employee;

$roles = Employee::query()->select('role')
    ->whereNotNull('role')
    ->distinct()
    ->orderBy('role')
    ->pluck('role')
    ->map(function ($r) {
        return '[' . trim($r) . ']';
    });

echo "Distinct roles (trimmed):\n";
echo implode("\n", $roles->toArray());

echo "\n";

<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

echo "=== إحصائيات المستخدمين ===" . PHP_EOL;
echo "إجمالي عدد المستخدمين: " . User::count() . PHP_EOL . PHP_EOL;

echo "توزيع الأدوار:" . PHP_EOL;
$roles = User::select('role', DB::raw('count(*) as total'))
    ->groupBy('role')
    ->orderBy('total', 'desc')
    ->get();

foreach($roles as $role) {
    echo "- {$role->role}: {$role->total} موظف" . PHP_EOL;
}

echo PHP_EOL . "أمثلة على الموظفين:" . PHP_EOL;
$sampleUsers = User::limit(10)->get();
foreach($sampleUsers as $user) {
    echo "- {$user->name} ({$user->role}) - {$user->email}" . PHP_EOL;
}

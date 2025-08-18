<?php

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Illuminate\Http\Request::capture();
$response = $kernel->handle($request);

use App\Models\User;

echo "=== اختبار عرض الأدوار لجميع المستخدمين ===\n\n";

$users = User::all();

foreach ($users as $user) {
    echo "المستخدم: {$user->name}\n";
    echo "- دور المستخدم: {$user->role}\n";
    echo "- دور الموظف: " . ($user->employee ? $user->employee->role : 'غير محدد') . "\n";
    echo "- الدور المعروض: {$user->getCurrentRoleDisplayName()}\n";
    echo "\n";
}

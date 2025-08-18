<?php

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Illuminate\Http\Request::capture();
$response = $kernel->handle($request);

use App\Models\User;

$user = User::find(12);
if ($user) {
    echo "خالد جمال منصور - بيانات المستخدم:\n";
    echo "- ID: {$user->id}\n";
    echo "- الاسم: {$user->name}\n";
    echo "- دور المستخدم: {$user->role}\n";
    echo "- role_id: " . ($user->role_id ?? 'null') . "\n";
    echo "- اسم الدور المعروض: {$user->getCurrentRoleDisplayName()}\n";
} else {
    echo "لم يتم العثور على المستخدم\n";
}

<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== المستخدمون الموجودون حالياً ===\n\n";

$users = \DB::table('users')
    ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
    ->select('users.*', 'roles.display_name as role_display_name')
    ->get();

foreach ($users as $user) {
    echo "ID: {$user->id}\n";
    echo "Name: {$user->name}\n";
    echo "Email: {$user->email}\n";
    echo "Role ID: {$user->role_id}\n";
    echo "Role: {$user->role_display_name}\n";
    echo "---\n";
}

echo "\nإجمالي المستخدمين: " . count($users) . "\n";

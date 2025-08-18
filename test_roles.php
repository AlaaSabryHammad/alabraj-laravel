<?php
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    $roles = App\Models\Role::withCount('users')->orderBy('name')->get();
    $permissions = App\Models\Permission::getByCategory();
    $users = App\Models\User::with('roles')->orderBy('name')->get();

    echo "Roles: " . $roles->count() . "\n";
    echo "Permission categories: " . count($permissions) . "\n";
    echo "Users: " . $users->count() . "\n";
    echo "Test successful!\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}

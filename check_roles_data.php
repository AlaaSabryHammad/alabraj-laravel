<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Role;

try {
    $roles = Role::where('is_active', true)->orderBy('display_name')->get();

    echo "Active Roles from Database:\n";
    echo "==========================\n";

    if ($roles->count() > 0) {
        foreach ($roles as $role) {
            echo "ID: {$role->id}\n";
            echo "Name: {$role->name}\n";
            echo "Display Name: {$role->display_name}\n";
            echo "Category: {$role->category}\n";
            echo "Active: " . ($role->is_active ? 'Yes' : 'No') . "\n";
            echo "---\n";
        }
    } else {
        echo "No active roles found.\n";
    }

    echo "\nTotal active roles: " . $roles->count() . "\n";
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage() . "\n";
}

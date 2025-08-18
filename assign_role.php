<?php

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Role;

$user = User::where('email', 'manager@abraj.com')->first();
$superAdminRole = Role::where('name', 'super_admin')->first();

if ($user && $superAdminRole) {
    $user->roles()->attach($superAdminRole->id);
    echo "Super Admin role assigned successfully!\n";
    echo "User: " . $user->name . " (" . $user->email . ")\n";
    echo "Role: " . $superAdminRole->display_name . "\n";
} else {
    echo "User or role not found.\n";
    if (!$user) echo "User not found.\n";
    if (!$superAdminRole) echo "Super admin role not found.\n";
}

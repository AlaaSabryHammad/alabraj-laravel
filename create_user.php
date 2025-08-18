<?php

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$user = new User();
$user->name = 'Ahmed Sabri';
$user->email = 'manager@abraj.com';
$user->password = Hash::make('manager123');
$user->email_verified_at = now();
$user->save();

echo "User created successfully!\n";
echo "Email: " . $user->email . "\n";
echo "Name: " . $user->name . "\n";
echo "ID: " . $user->id . "\n";

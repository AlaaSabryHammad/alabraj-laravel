<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;

try {
    // Check if there are any manager users
    $managerUsers = User::where('role', 'manager')->get();
    echo "Current manager users count: " . $managerUsers->count() . PHP_EOL;

    foreach($managerUsers as $user) {
        echo "User: " . $user->email . " - Role: " . $user->role . PHP_EOL;
    }

    // If no manager users, create one from existing users or create new one
    if ($managerUsers->count() === 0) {
        echo "No manager users found. Checking for users to promote..." . PHP_EOL;

        $firstUser = User::first();
        if ($firstUser) {
            $firstUser->update(['role' => 'manager']);
            echo "Promoted user: " . $firstUser->email . " to manager role." . PHP_EOL;
        } else {
            echo "No users found in database." . PHP_EOL;
        }
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
}

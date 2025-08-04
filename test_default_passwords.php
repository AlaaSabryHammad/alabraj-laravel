<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;

try {
    // Find a user with an employee that has default password
    $users = User::with('employee')->where('role', 'manager')->get();

    foreach($users as $user) {
        if ($user->employee && $user->employee->national_id) {
            $nationalId = $user->employee->national_id;
            $hasDefaultPassword = Hash::check($nationalId, $user->password);

            echo "User: " . $user->email . PHP_EOL;
            echo "National ID: " . $nationalId . PHP_EOL;
            echo "Has default password: " . ($hasDefaultPassword ? 'YES' : 'NO') . PHP_EOL;
            echo "---" . PHP_EOL;
        }
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
}

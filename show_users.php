<?php
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

echo "المستخدمين المتاحين:" . PHP_EOL;

// Get users
$users = \App\Models\User::take(5)->get(['id', 'email', 'name']);

foreach ($users as $user) {
    echo "ID: " . $user->id . " | Email: " . $user->email . " | Name: " . $user->name . PHP_EOL;
}

echo PHP_EOL . "كلمة المرور لجميع المستخدمين: password" . PHP_EOL;
echo "اذهب إلى: http://127.0.0.1:8000/login" . PHP_EOL;

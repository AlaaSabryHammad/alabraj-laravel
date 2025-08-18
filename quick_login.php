<?php
// Quick login script for testing
require_once 'vendor/autoload.php';

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

// Start Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "<h2>المستخدمين المتاحين للاختبار:</h2>";

// Get first few users for quick testing
$users = \App\Models\User::take(5)->get();

foreach ($users as $user) {
    $role = $user->getCurrentRoleDisplayName();
    echo "<p><strong>ID:</strong> {$user->id} | <strong>Email:</strong> {$user->email} | <strong>Role:</strong> {$role}</p>";
}

echo "<br><h3>للدخول إلى النظام:</h3>";
echo "<p>1. اذهب إلى: <a href='http://127.0.0.1:8000/login' target='_blank'>http://127.0.0.1:8000/login</a></p>";
echo "<p>2. استخدم أي email من الأعلى مع كلمة المرور: <strong>password</strong></p>";
echo "<p>3. بعد تسجيل الدخول، اذهب إلى: <a href='http://127.0.0.1:8000/locations/3' target='_blank'>http://127.0.0.1:8000/locations/3</a></p>";

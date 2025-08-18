<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔄 إنشاء مستخدم محمد الشهراني...\n";

// البحث عن دور المدير العام
$generalManagerRole = \DB::table('roles')->where('name', 'general_manager')->first();

if (!$generalManagerRole) {
    echo "❌ خطأ: دور المدير العام غير موجود!\n";
    exit(1);
}

echo "✅ تم العثور على دور المدير العام (ID: {$generalManagerRole->id})\n";

// إنشاء أو تحديث المستخدم
$userData = [
    'name' => 'محمد الشهراني',
    'email' => 'mohammad.alshahrani@company.com',
    'password' => bcrypt('password123'),
    'role_id' => $generalManagerRole->id,
    'phone' => '966501234567',
    'created_at' => now(),
    'updated_at' => now()
];

// التحقق من وجود المستخدم
$existingUser = \DB::table('users')->where('email', $userData['email'])->first();

if ($existingUser) {
    // تحديث المستخدم الموجود
    \DB::table('users')->where('id', $existingUser->id)->update([
        'name' => $userData['name'],
        'role_id' => $userData['role_id'],
        'phone' => $userData['phone'],
        'updated_at' => $userData['updated_at']
    ]);
    echo "✅ تم تحديث المستخدم محمد الشهراني (ID: {$existingUser->id})\n";
} else {
    // إنشاء مستخدم جديد
    $userId = \DB::table('users')->insertGetId($userData);
    echo "✅ تم إنشاء المستخدم محمد الشهراني (ID: {$userId})\n";
}

// التحقق من النتيجة
$user = \DB::table('users')
    ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
    ->where('users.email', $userData['email'])
    ->select('users.*', 'roles.display_name as role_display_name')
    ->first();

if ($user) {
    echo "\n📋 تفاصيل المستخدم:\n";
    echo "==================\n";
    echo "ID: {$user->id}\n";
    echo "الاسم: {$user->name}\n";
    echo "البريد الإلكتروني: {$user->email}\n";
    echo "رقم الهاتف: {$user->phone}\n";
    echo "الدور: {$user->role_display_name}\n";
    echo "تاريخ الإنشاء: {$user->created_at}\n";
} else {
    echo "❌ خطأ: لم يتم العثور على المستخدم بعد الإنشاء!\n";
}

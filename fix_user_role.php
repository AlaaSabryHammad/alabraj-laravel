<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔧 ربط المستخدم محمد الشهراني بدور المدير العام\n";
echo "============================================\n\n";

// الحصول على المستخدم
$user = \DB::table('users')->where('id', 1)->first();
if (!$user) {
    echo "❌ لم يتم العثور على المستخدم\n";
    exit(1);
}

// الحصول على دور المدير العام
$generalManagerRole = \DB::table('roles')->where('name', 'general_manager')->first();
if (!$generalManagerRole) {
    echo "❌ لم يتم العثور على دور المدير العام\n";
    exit(1);
}

echo "✅ تم العثور على المستخدم: {$user->name}\n";
echo "✅ تم العثور على الدور: {$generalManagerRole->display_name}\n\n";

// تحديث role_id للمستخدم
\DB::table('users')
    ->where('id', $user->id)
    ->update(['role_id' => $generalManagerRole->id]);

echo "🔄 تم ربط المستخدم بالدور بنجاح!\n\n";

// التحقق من النتيجة
$updatedUser = \DB::table('users')
    ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
    ->where('users.id', 1)
    ->select('users.*', 'roles.name as role_name', 'roles.display_name as role_display_name')
    ->first();

echo "📋 النتيجة:\n";
echo "===========\n";
echo "User ID: {$updatedUser->id}\n";
echo "User Name: {$updatedUser->name}\n";
echo "Role ID: " . ($updatedUser->role_id ?? 'null') . "\n";
echo "Role Name: " . ($updatedUser->role_name ?? 'null') . "\n";
echo "Role Display Name: " . ($updatedUser->role_display_name ?? 'null') . "\n";

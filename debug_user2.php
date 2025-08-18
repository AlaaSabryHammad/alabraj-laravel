<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$user2 = \App\Models\User::find(2);

echo "🔍 تحليل مشكلة المستخدم 2:\n";
echo "========================\n";
echo "role_id: " . ($user2->role_id ?? 'null') . "\n";

// التحقق من العلاقة role()
if ($user2->role) {
    echo "الدور المرتبط: {$user2->role->name}\n";
} else {
    echo "لا يوجد دور مرتبط\n";
}

// التحقق من hasRole
echo "hasRole('general_manager'): " . ($user2->hasRole('general_manager') ? 'نعم' : 'لا') . "\n";

// التحقق من roles() (many-to-many)
$roles = $user2->roles;
echo "عدد الأدوار (many-to-many): " . $roles->count() . "\n";

// التحقق من جدول user_roles
$userRolesCount = \DB::table('user_roles')->where('user_id', 2)->count();
echo "سجلات في user_roles: {$userRolesCount}\n";

echo "\n🔧 محاولة إصلاح العلاقة:\n";
echo "======================\n";

// إضافة الدور عبر many-to-many أيضاً
$generalManagerRole = \DB::table('roles')->where('name', 'general_manager')->first();
if ($generalManagerRole) {
    // حذف الأدوار القديمة وإضافة الجديد
    \DB::table('user_roles')->where('user_id', 2)->delete();
    \DB::table('user_roles')->insert([
        'user_id' => 2,
        'role_id' => $generalManagerRole->id
    ]);

    echo "تم إضافة الدور في جدول user_roles\n";

    // اختبار مرة أخرى
    $user2 = $user2->fresh();
    echo "hasRole بعد الإصلاح: " . ($user2->hasRole('general_manager') ? 'نعم' : 'لا') . "\n";
    echo "isGeneralManager بعد الإصلاح: " . ($user2->isGeneralManager() ? 'نعم' : 'لا') . "\n";
}

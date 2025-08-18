<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔍 التحقق من أدوار المستخدمين في القائمة الجانبية\n";
echo "===============================================\n\n";

$users = \App\Models\User::all();

foreach ($users as $user) {
    echo "👤 المستخدم: {$user->name}\n";
    echo "   📧 الإيميل: {$user->email}\n";
    echo "   🆔 role_id: " . ($user->role_id ?? 'null') . "\n";
    echo "   📝 role (string): " . ($user->role ?? 'null') . "\n";

    // اختبار دالة getCurrentRoleDisplayName
    try {
        $displayName = $user->getCurrentRoleDisplayName();
        echo "   🏷️ عرض الدور: {$displayName}\n";
    } catch (Exception $e) {
        echo "   ❌ خطأ في getCurrentRoleDisplayName: " . $e->getMessage() . "\n";
    }

    // اختبار علاقة role()
    if ($user->role_id) {
        $role = \App\Models\Role::find($user->role_id);
        if ($role) {
            echo "   ✅ الدور من العلاقة: {$role->name} ({$role->display_name})\n";
        } else {
            echo "   ❌ role_id موجود لكن الدور غير موجود في الجدول\n";
        }
    }

    echo "\n";
}

echo "💡 تحليل النتائج:\n";
echo "================\n";
echo "- إذا كان عرض الدور صحيح، المشكلة في التصميم\n";
echo "- إذا كان عرض الدور خاطئ، المشكلة في البيانات\n";

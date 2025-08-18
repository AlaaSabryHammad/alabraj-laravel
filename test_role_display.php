<?php

require 'vendor/autoload.php';

// إنشاء البيئة
putenv('APP_ENV=local');
$app = require_once 'bootstrap/app.php';

try {
    echo "اختبار دالة getCurrentRoleDisplayName()...\n\n";

    // البحث عن محمد الشهراني
    $user = App\Models\User::where('email', 'mohamed@abraj.com')->first();

    if ($user) {
        echo "👤 المستخدم: {$user->name}\n";
        echo "📧 الإيميل: {$user->email}\n";
        echo "🆔 Role ID: {$user->role_id}\n";
        echo "👔 الدور المعروض: " . $user->getCurrentRoleDisplayName() . "\n";

        // اختبار العلاقات
        echo "\nتفاصيل العلاقات:\n";
        if ($user->role_id) {
            $role = App\Models\Role::find($user->role_id);
            if ($role) {
                echo "✅ علاقة role_id تعمل: {$role->display_name}\n";
            }
        }

        if ($user->roles && $user->roles->isNotEmpty()) {
            echo "✅ علاقة many-to-many تعمل: {$user->roles->first()->display_name}\n";
        } else {
            echo "⚠️ علاقة many-to-many فارغة\n";
        }
    } else {
        echo "❌ لم يتم العثور على المستخدم\n";
    }
} catch (Exception $e) {
    echo "خطأ: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

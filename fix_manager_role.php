<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔍 إصلاح مشكلة المدير العام\n";
echo "==========================\n\n";

// التحقق من المستخدم الحالي في الجلسة
if (session()->has('user_id')) {
    $currentUserId = session('user_id');
    echo "معرف المستخدم في الجلسة: {$currentUserId}\n";
} else {
    echo "لا يوجد مستخدم في الجلسة\n";
}

// البحث عن دور المدير العام
$generalManagerRole = \DB::table('roles')->where('name', 'general_manager')->first();
if ($generalManagerRole) {
    echo "✅ تم العثور على دور المدير العام:\n";
    echo "  - ID: {$generalManagerRole->id}\n";
    echo "  - Name: {$generalManagerRole->name}\n";
    echo "  - Display: {$generalManagerRole->display_name}\n\n";

    // التحقق من جميع المستخدمين وأدوارهم
    $users = \App\Models\User::all();
    echo "📋 جميع المستخدمين:\n";
    echo "==================\n";

    foreach ($users as $user) {
        echo "المستخدم {$user->id}: {$user->name}\n";
        echo "  - role_id: " . ($user->role_id ?? 'null') . "\n";

        // تحديث المستخدم الأول ليصبح مدير عام
        if ($user->id == 1) {
            $user->role_id = $generalManagerRole->id;
            $user->save();
            echo "  ✅ تم تحديث الدور إلى مدير عام\n";
        }

        // إعادة تحميل المستخدم والتحقق من isGeneralManager
        $user = $user->fresh();
        $isGM = false;
        try {
            $isGM = $user->isGeneralManager();
        } catch (Exception $e) {
            echo "  ❌ خطأ في isGeneralManager: " . $e->getMessage() . "\n";
        }

        echo "  - هل هو مدير عام؟ " . ($isGM ? 'نعم ✅' : 'لا ❌') . "\n";
        echo "\n";
    }
} else {
    echo "❌ لم يتم العثور على دور المدير العام في النظام\n";
}

// اختبار دالة isGeneralManager مباشرة
echo "🧪 اختبار دالة isGeneralManager:\n";
echo "==============================\n";

$user1 = \App\Models\User::find(1);
if ($user1) {
    echo "المستخدم: {$user1->name}\n";
    echo "role_id: " . ($user1->role_id ?? 'null') . "\n";

    // التحقق من hasRole مباشرة
    echo "hasRole('general_manager'): " . ($user1->hasRole('general_manager') ? 'نعم' : 'لا') . "\n";
    echo "hasRole('admin'): " . ($user1->hasRole('admin') ? 'نعم' : 'لا') . "\n";
    echo "hasRole('super_admin'): " . ($user1->hasRole('super_admin') ? 'نعم' : 'لا') . "\n";
    echo "isGeneralManager(): " . ($user1->isGeneralManager() ? 'نعم ✅' : 'لا ❌') . "\n";
}

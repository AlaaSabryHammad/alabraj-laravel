<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔍 التحقق من role_id للمستخدمين:\n";
echo "===============================\n";

$users = \App\Models\User::all();
foreach ($users as $user) {
    echo "المستخدم {$user->id}: {$user->name}\n";
    echo "  - role_id: " . ($user->role_id ?? 'null') . "\n";
    if ($user->role) {
        echo "  - اسم الدور: {$user->role->name}\n";
        echo "  - عرض الدور: {$user->role->display_name}\n";
    } else {
        echo "  - لا يوجد دور مرتبط\n";
    }
    echo "\n";
}

echo "🔄 تحديث المستخدم رقم 1 ليصبح مدير عام:\n";
echo "====================================\n";

$user1 = \App\Models\User::find(1);
$generalManagerRole = \DB::table('roles')->where('name', 'general_manager')->first();

if ($user1 && $generalManagerRole) {
    $user1->role_id = $generalManagerRole->id;
    $user1->save();

    echo "✅ تم تحديث المستخدم {$user1->name} ليصبح مدير عام\n";

    // التحقق من النتيجة
    $user1 = $user1->fresh(); // إعادة تحميل البيانات
    $isGM = $user1->isGeneralManager();
    echo "التحقق من المدير العام: " . ($isGM ? 'نعم ✅' : 'لا ❌') . "\n";

    if ($user1->role) {
        echo "الدور الحالي: {$user1->role->name} ({$user1->role->display_name})\n";
    }
} else {
    echo "❌ فشل في العثور على المستخدم أو دور المدير العام\n";
}

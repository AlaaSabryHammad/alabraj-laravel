<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🎭 الأدوار المتاحة في النظام:\n";
echo "========================\n";

$roles = \DB::table('roles')->get();
foreach ($roles as $role) {
    echo "- {$role->name} ({$role->display_name})\n";
}

echo "\n🔄 إعطاء دور مدير عام للمستخدم رقم 1:\n";
echo "==================================\n";

$user = \App\Models\User::find(1);
if ($user) {
    echo "المستخدم الحالي: {$user->name}\n";

    // البحث عن دور المدير العام
    $generalManagerRole = \DB::table('roles')->where('name', 'general_manager')->first();
    if ($generalManagerRole) {
        echo "تم العثور على دور المدير العام: {$generalManagerRole->display_name}\n";

        // إعطاء الدور للمستخدم
        $user->roles()->sync([$generalManagerRole->id]);
        echo "✅ تم إعطاء دور المدير العام للمستخدم {$user->name}\n";

        // التحقق من النتيجة
        $isGM = $user->fresh()->isGeneralManager();
        echo "التحقق من المدير العام: " . ($isGM ? 'نعم' : 'لا') . "\n";
    } else {
        echo "❌ لم يتم العثور على دور المدير العام\n";
    }
} else {
    echo "❌ لم يتم العثور على المستخدم رقم 1\n";
}

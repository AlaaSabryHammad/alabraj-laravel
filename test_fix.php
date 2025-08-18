<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$user1 = \App\Models\User::find(1);
echo "🎉 اختبار بعد الإصلاح:\n";
echo "===================\n";
echo "المستخدم: {$user1->name}\n";
echo "isGeneralManager(): " . ($user1->isGeneralManager() ? 'نعم ✅' : 'لا ❌') . "\n";

// أيضاً تحديث المستخدم الثاني ليصبح مدير عام
$user2 = \App\Models\User::find(2);
$generalManagerRole = \DB::table('roles')->where('name', 'general_manager')->first();
$user2->role_id = $generalManagerRole->id;
$user2->save();

echo "\nالمستخدم الثاني: {$user2->name}\n";
echo "isGeneralManager(): " . ($user2->fresh()->isGeneralManager() ? 'نعم ✅' : 'لا ❌') . "\n";

echo "\n🎯 الآن كلا المستخدمين لديهما صلاحيات المدير العام!\n";

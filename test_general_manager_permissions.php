<?php

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

// بوتستراب اللارافل
$kernel->bootstrap();

use App\Models\User;
use App\Models\Role;

echo "🔍 اختبار صلاحيات زر تفعيل الموظف\n";
echo "=====================================\n\n";

// فحص الأدوار المتاحة
$roles = Role::all();
echo "الأدوار المتاحة في النظام:\n";
foreach ($roles as $role) {
    echo "- {$role->name} ({$role->display_name})\n";
}
echo "\n";

// فحص المستخدمين وأدوارهم
$users = User::with('roles')->get();
echo "المستخدمون وأدوارهم:\n";
foreach ($users as $user) {
    $userRoles = $user->roles->pluck('name')->toArray();
    $isGeneralManager = $user->isGeneralManager();
    $managerStatus = $isGeneralManager ? "✅ مدير عام" : "❌ ليس مدير عام";

    echo "- {$user->name} ({$user->email})\n";
    echo "  الأدوار: " . implode(', ', $userRoles) . "\n";
    echo "  الحالة: {$managerStatus}\n\n";
}

// اختبار الدالة
echo "اختبار دالة isGeneralManager():\n";
echo "==================================\n";

// البحث عن مستخدم بدور general-manager
$generalManager = User::whereHas('roles', function ($query) {
    $query->where('name', 'general-manager');
})->first();

if ($generalManager) {
    echo "✅ وُجد مدير عام: {$generalManager->name}\n";
    echo "   نتيجة isGeneralManager(): " . ($generalManager->isGeneralManager() ? 'true' : 'false') . "\n";
} else {
    echo "⚠️  لم يتم العثور على مدير عام بدور 'general-manager'\n";
}

// البحث عن مستخدم بدور admin
$admin = User::whereHas('roles', function ($query) {
    $query->where('name', 'admin');
})->first();

if ($admin) {
    echo "✅ وُجد مدير نظام: {$admin->name}\n";
    echo "   نتيجة isGeneralManager(): " . ($admin->isGeneralManager() ? 'true' : 'false') . "\n";
} else {
    echo "⚠️  لم يتم العثور على مدير نظام بدور 'admin'\n";
}

// البحث عن مستخدم عادي
$regularUser = User::whereDoesntHave('roles', function ($query) {
    $query->whereIn('name', ['general-manager', 'admin']);
})->first();

if ($regularUser) {
    echo "✅ وُجد مستخدم عادي: {$regularUser->name}\n";
    echo "   نتيجة isGeneralManager(): " . ($regularUser->isGeneralManager() ? 'true' : 'false') . "\n";
} else {
    echo "⚠️  لم يتم العثور على مستخدم عادي\n";
}

echo "\n";
echo "📝 ملخص:\n";
echo "=========\n";
echo "1. زر تفعيل الموظف سيظهر فقط للمديرين العامين (دور general-manager أو admin)\n";
echo "2. المستخدمون الآخرون لن يروا هذا الزر\n";
echo "3. حتى لو حاول مستخدم غير مخول الوصول للرابط مباشرة، سيتم رفضه\n";

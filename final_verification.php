<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🎯 التحقق النهائي من تحديث الـ Seeder\n";
echo "=====================================\n\n";

// 1. فحص الأدوار
echo "📊 الأدوار الموجودة:\n";
echo "===================\n";

$roles = \DB::table('roles')->orderBy('category')->orderBy('id')->get();

$categories = [];
foreach ($roles as $role) {
    if (!isset($categories[$role->category])) {
        $categories[$role->category] = [];
    }
    $categories[$role->category][] = $role;
}

foreach ($categories as $category => $categoryRoles) {
    echo "\n🏷️ {$category}:\n";
    echo str_repeat('-', 15) . "\n";
    foreach ($categoryRoles as $role) {
        echo "  - {$role->display_name} ({$role->name})\n";
    }
}

// 2. فحص الصلاحيات
echo "\n\n📋 إحصائيات الصلاحيات:\n";
echo "========================\n";
$permissionsCount = \DB::table('permissions')->count();
$rolePermissionsCount = \DB::table('role_permissions')->count();
echo "إجمالي الصلاحيات: {$permissionsCount}\n";
echo "إجمالي روابط الأدوار-الصلاحيات: {$rolePermissionsCount}\n";

// 3. فحص المستخدمين
echo "\n\n👥 المستخدمون:\n";
echo "==============\n";

$users = \DB::table('users')
    ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
    ->select('users.id', 'users.name', 'users.email', 'roles.display_name as role')
    ->get();

if ($users->count() > 0) {
    foreach ($users as $user) {
        echo "  - {$user->name} ({$user->email}) - دور: {$user->role}\n";
    }
} else {
    echo "  لا يوجد مستخدمون\n";
}

// 4. مقارنة مع المطلوب
echo "\n\n✅ التحقق من المطابقة:\n";
echo "======================\n";

$expectedRoles = [
    'general_manager' => 'مدير عام',
    'project_manager' => 'مدير مشاريع',
    'financial_manager' => 'مدير مالي',
    'hr_manager' => 'مدير موارد بشرية',
    'operations_manager' => 'مدير عمليات',
    'engineer' => 'مهندس',
    'equipment_operator' => 'عامل تشغيل معدات',
    'driver' => 'سائق',
    'security' => 'أمن',
    'accountant' => 'محاسب',
    'admin_assistant' => 'مساعد إداري'
];

$currentRoles = \DB::table('roles')->pluck('display_name', 'name')->toArray();

$allMatch = true;
foreach ($expectedRoles as $name => $displayName) {
    if (!isset($currentRoles[$name])) {
        echo "❌ دور مفقود: {$name}\n";
        $allMatch = false;
    } elseif ($currentRoles[$name] !== $displayName) {
        echo "❌ اسم عرض خاطئ للدور {$name}: متوقع '{$displayName}' موجود '{$currentRoles[$name]}'\n";
        $allMatch = false;
    } else {
        echo "✅ {$displayName}\n";
    }
}

// فحص الأدوار الإضافية
foreach ($currentRoles as $name => $displayName) {
    if (!isset($expectedRoles[$name])) {
        echo "⚠️ دور إضافي غير متوقع: {$name} ({$displayName})\n";
        $allMatch = false;
    }
}

echo "\n" . ($allMatch ? "🎉 جميع الأدوار تطابق المطلوب!" : "⚠️ هناك اختلافات في الأدوار") . "\n";

echo "\n🏁 التحقق اكتمل!\n";

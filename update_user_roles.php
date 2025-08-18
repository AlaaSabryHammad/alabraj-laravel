<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔄 تحديث أدوار المستخدمين لتعكس وظائفهم الفعلية\n";
echo "===========================================\n\n";

// المستخدم الأول يبقى مدير عام
$user1 = \App\Models\User::find(1);
echo "👤 المستخدم الأول: {$user1->name}\n";
echo "   - الدور الحالي: {$user1->getCurrentRoleDisplayName()}\n";
echo "   - سيبقى كمدير عام ✅\n\n";

// المستخدم الثاني سنجعله بدور مناسب لاسمه (علاء صبري حماد رضوان)
$user2 = \App\Models\User::find(2);
$employee2 = $user2->employee;

echo "👤 المستخدم الثاني: {$user2->name}\n";
echo "   - الدور الحالي: {$user2->getCurrentRoleDisplayName()}\n";

if ($employee2) {
    echo "   - دور الموظف: {$employee2->role}\n";

    // تحديث دور المستخدم ليطابق دور الموظف
    $employeeRoleInDB = \DB::table('roles')->where('name', $employee2->role)->first();

    if ($employeeRoleInDB) {
        $user2->role_id = $employeeRoleInDB->id;
        $user2->save();

        echo "   ✅ تم تحديث دور المستخدم إلى: {$employeeRoleInDB->display_name}\n";

        // التحقق من التحديث
        $user2 = $user2->fresh();
        echo "   - الدور الجديد: {$user2->getCurrentRoleDisplayName()}\n";
    } else {
        echo "   ❌ لم يتم العثور على الدور في قاعدة البيانات\n";
    }
} else {
    echo "   ❌ لا يوجد موظف مرتبط\n";
}

echo "\n🎯 النتيجة النهائية:\n";
echo "==================\n";

$users = \App\Models\User::all();
foreach ($users as $user) {
    echo "👤 {$user->name}: {$user->getCurrentRoleDisplayName()}\n";
}

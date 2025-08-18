<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

use App\Models\User;
use App\Models\Employee;
use App\Models\Role;
use App\Models\Permission;

echo "🎉 نتائج عملية migrate:fresh\n";
echo "============================\n\n";

try {
    // فحص الأدوار
    $rolesCount = Role::count();
    echo "📝 عدد الأدوار: {$rolesCount}\n";

    // فحص الصلاحيات
    $permissionsCount = Permission::count();
    echo "🔑 عدد الصلاحيات: {$permissionsCount}\n";

    // فحص المستخدمين
    $usersCount = User::count();
    echo "👥 عدد المستخدمين: {$usersCount}\n";

    // فحص الموظفين
    $employeesCount = Employee::count();
    echo "👤 عدد الموظفين: {$employeesCount}\n";

    echo "\n" . str_repeat("=", 40) . "\n";

    // البحث عن محمد الشهراني
    $mohamedUser = User::where('email', 'mohamed@abraj.com')->first();
    if ($mohamedUser) {
        echo "✅ المستخدم الجديد: محمد الشهراني\n";
        echo "   📧 الإيميل: {$mohamedUser->email}\n";
        echo "   📱 الهاتف: {$mohamedUser->phone}\n";

        // التحقق من الأدوار
        $roles = $mohamedUser->roles;
        if ($roles->count() > 0) {
            echo "   🎭 الأدوار: ";
            foreach ($roles as $role) {
                echo $role->display_name . " ";
            }
            echo "\n";
        }

        // البحث عن سجل الموظف
        $mohamedEmployee = Employee::where('email', 'mohamed@abraj.com')->first();
        if ($mohamedEmployee) {
            echo "   👨‍💼 سجل الموظف: ID {$mohamedEmployee->id}\n";
            echo "   💼 المنصب: {$mohamedEmployee->position}\n";
            echo "   🏢 القسم: {$mohamedEmployee->department}\n";
            echo "   💰 الراتب: " . number_format($mohamedEmployee->salary, 2) . " ريال\n";
        }
    } else {
        echo "❌ لم يتم العثور على محمد الشهراني\n";
    }

    echo "\n" . str_repeat("=", 40) . "\n";

    // التحقق من نظام التسلسل الهرمي
    echo "🔗 اختبار نظام التسلسل الهرمي:\n";

    $engineers = Employee::where('role', 'engineer')->limit(3)->get();
    foreach ($engineers as $engineer) {
        echo "   👨‍🔧 {$engineer->name} (مهندس)\n";
        $managers = Employee::where('role', 'project_manager')->get();
        echo "     يمكنه رؤية " . $managers->count() . " مدير مشاريع في القائمة\n";
    }

    echo "\n🌐 الموقع متاح على: http://127.0.0.1:8000\n";
    echo "🔑 للدخول كمدير عام:\n";
    echo "   الإيميل: mohamed@abraj.com\n";
    echo "   كلمة المرور: mohamed123\n";
} catch (Exception $e) {
    echo "❌ خطأ: " . $e->getMessage() . "\n";
}

echo "\n✅ تم إكمال العملية بنجاح!\n";

<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

use App\Models\User;
use App\Models\Employee;

echo "🎯 التحقق النهائي من حذف الموظفين\n";
echo "================================\n\n";

try {
    $usersCount = User::count();
    $employeesCount = Employee::count();

    echo "📊 الإحصائيات:\n";
    echo "   👥 عدد المستخدمين: {$usersCount}\n";
    echo "   👤 عدد الموظفين: {$employeesCount}\n\n";

    if ($usersCount === 1 && $employeesCount === 1) {
        $user = User::first();
        $employee = Employee::first();

        echo "✅ النتيجة: تم الاحتفاظ بمستخدم وموظف واحد فقط\n";
        echo "   👨‍💼 المستخدم: {$user->name} ({$user->email})\n";
        echo "   🏢 الموظف: {$employee->name} - {$employee->position}\n";
        echo "   🎭 الدور: {$employee->role}\n";

        if ($user->email === 'mohamed@abraj.com' && $employee->email === 'mohamed@abraj.com') {
            echo "\n🎉 مثالي! محمد الشهراني هو المستخدم والموظف الوحيد المتبقي\n";
        }
    } else {
        echo "⚠️  يوجد أكثر من مستخدم أو موظف واحد\n";

        echo "\n👥 المستخدمين الموجودين:\n";
        foreach (User::all() as $user) {
            echo "   - {$user->name} ({$user->email})\n";
        }

        echo "\n👤 الموظفين الموجودين:\n";
        foreach (Employee::all() as $employee) {
            echo "   - {$employee->name} ({$employee->email}) - {$employee->position}\n";
        }
    }
} catch (Exception $e) {
    echo "❌ خطأ: " . $e->getMessage() . "\n";
}

echo "\n✅ التحقق مكتمل!\n";

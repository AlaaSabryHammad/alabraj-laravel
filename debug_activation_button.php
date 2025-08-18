<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔍 التحقق من سبب عدم ظهور زر التفعيل\n";
echo "=====================================\n\n";

// التحقق من الموظف رقم 2
$employee = \App\Models\Employee::find(2);
if ($employee) {
    echo "📋 بيانات الموظف رقم 2:\n";
    echo "========================\n";
    echo "الاسم: {$employee->name}\n";
    echo "الدور: {$employee->role}\n";
    echo "الحالة: {$employee->status}\n";

    if ($employee->user) {
        echo "معرف المستخدم المرتبط: {$employee->user->id}\n";
        echo "إيميل المستخدم: {$employee->user->email}\n";
    } else {
        echo "❌ لا يوجد مستخدم مرتبط بهذا الموظف\n";
    }

    echo "\n🔍 التحقق من المستخدمين في النظام:\n";
    echo "================================\n";

    $users = \App\Models\User::with(['employee'])->get();
    foreach ($users as $user) {
        echo "المستخدم {$user->id}: {$user->name}\n";
        echo "  - الإيميل: {$user->email}\n";

        // التحقق من الدور
        if (isset($user->role) && $user->role) {
            if (is_string($user->role)) {
                echo "  - الدور: {$user->role}\n";
            } else {
                echo "  - الدور: {$user->role->name}\n";
            }
        } else {
            echo "  - الدور: غير محدد\n";
        }

        if ($user->employee) {
            echo "  - الموظف المرتبط: {$user->employee->name}\n";
        }

        // التحقق من دالة isGeneralManager
        try {
            $isGM = $user->isGeneralManager();
            echo "  - هل هو مدير عام؟ " . ($isGM ? 'نعم' : 'لا') . "\n";
        } catch (Exception $e) {
            echo "  - خطأ في التحقق من المدير العام: " . $e->getMessage() . "\n";
        }

        echo "\n";
    }
} else {
    echo "❌ لم يتم العثور على الموظف رقم 2\n";
}

echo "💡 شرح منطق ظهور الزر:\n";
echo "=====================\n";
echo "الزر يظهر عندما:\n";
echo "1. المستخدم مسجل دخول\n";
echo "2. المستخدم مدير عام (isGeneralManager() = true)\n";
echo "3. الموظف المعروض حالته inactive\n";

<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔍 التحقق من بيانات الموظف رقم 2\n";
echo "============================\n";

$employee = \App\Models\Employee::find(2);
if ($employee) {
    echo "الاسم: {$employee->name}\n";
    echo "الدور: {$employee->role}\n";
    echo "الحالة: {$employee->status}\n";
    echo "المدير المباشر: " . ($employee->direct_manager_id ? $employee->directManager->name : 'لا يوجد') . "\n";

    // التحقق من المستخدم المرتبط
    $user = $employee->user;
    if ($user) {
        echo "معرف المستخدم: {$user->id}\n";
        echo "إيميل المستخدم: {$user->email}\n";

        // التحقق من دور المستخدم
        if ($user->role) {
            echo "دور المستخدم: {$user->role->name}\n";
        }

        // التحقق من صلاحية المدير العام
        echo "هل هو مدير عام؟ " . ($user->isGeneralManager() ? 'نعم' : 'لا') . "\n";
    } else {
        echo "لا يوجد مستخدم مرتبط بهذا الموظف\n";
    }

    echo "\n🔍 تحليل سبب اختفاء الزر:\n";
    echo "======================\n";

    // التحقق من المستخدم الحالي المسجل
    echo "المستخدم الحالي المسجل: ";
    if (auth()->check()) {
        $currentUser = auth()->user();
        echo "{$currentUser->name} (ID: {$currentUser->id})\n";
        echo "هل المستخدم الحالي مدير عام؟ " . ($currentUser->isGeneralManager() ? 'نعم' : 'لا') . "\n";
    } else {
        echo "لا يوجد مستخدم مسجل دخول\n";
    }
} else {
    echo "لم يتم العثور على الموظف رقم 2\n";
}

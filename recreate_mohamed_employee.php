<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

use App\Models\User;
use App\Models\Employee;

echo "🔄 إعادة إنشاء سجل الموظف لمحمد الشهراني\n";
echo "=====================================\n\n";

try {
    // البحث عن المستخدم
    $user = User::where('email', 'mohamed@abraj.com')->first();

    if (!$user) {
        echo "❌ لم يتم العثور على مستخدم محمد الشهراني\n";
        exit;
    }

    // التحقق من وجود سجل الموظف
    $existingEmployee = Employee::where('email', 'mohamed@abraj.com')->first();
    if ($existingEmployee) {
        echo "✅ سجل الموظف موجود مسبقاً: {$existingEmployee->name}\n";
        exit;
    }

    // إنشاء سجل الموظف
    $employee = Employee::create([
        'name' => 'محمد الشهراني',
        'user_id' => $user->id,
        'position' => 'مدير عام',
        'role' => 'super_admin',
        'department' => 'الإدارة العليا',
        'hire_date' => now(),
        'email' => 'mohamed@abraj.com',
        'phone' => '0501234567',
        'status' => 'active',
        'nationality' => 'سعودي',
        'national_id' => '1234567890',
        'salary' => 25000.00,
    ]);

    echo "✅ تم إنشاء سجل الموظف بنجاح!\n";
    echo "   ID: {$employee->id}\n";
    echo "   الاسم: {$employee->name}\n";
    echo "   المنصب: {$employee->position}\n";
} catch (Exception $e) {
    echo "❌ خطأ: " . $e->getMessage() . "\n";
}

echo "\n✅ العملية مكتملة!\n";

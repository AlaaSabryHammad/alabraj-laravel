<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔍 فحص بيانات الموظف ID=1\n";
echo "===========================\n\n";

// الحصول على بيانات الموظف
$employee = \DB::table('employees')
    ->leftJoin('users', 'employees.user_id', '=', 'users.id')
    ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
    ->where('employees.id', 1)
    ->select(
        'employees.*',
        'users.name as user_name',
        'users.email as user_email',
        'roles.name as role_name',
        'roles.display_name as role_display_name'
    )
    ->first();

if ($employee) {
    echo "👤 معلومات الموظف:\n";
    echo "===================\n";
    echo "ID: {$employee->id}\n";
    echo "الاسم: {$employee->name}\n";
    echo "الايميل: {$employee->email}\n";
    echo "Role (في جدول employees): " . ($employee->role ?? 'null') . "\n";
    echo "Position: " . ($employee->position ?? 'null') . "\n";
    echo "Department: " . ($employee->department ?? 'null') . "\n";
    echo "Status: {$employee->status}\n";
    echo "User ID: " . ($employee->user_id ?? 'null') . "\n";

    echo "\n👤 معلومات المستخدم المرتبط:\n";
    echo "============================\n";
    echo "User Name: " . ($employee->user_name ?? 'null') . "\n";
    echo "User Email: " . ($employee->user_email ?? 'null') . "\n";
    echo "Role Name: " . ($employee->role_name ?? 'null') . "\n";
    echo "Role Display Name: " . ($employee->role_display_name ?? 'null') . "\n";

    echo "\n🔍 تحليل المشكلة:\n";
    echo "==================\n";

    if ($employee->role === 'مسئول رئيسي') {
        echo "❌ المشكلة: employee.role = 'مسئول رئيسي' لذلك الزر مخفي\n";
    } elseif ($employee->role === null) {
        echo "⚠️ employee.role = null، الزر يجب أن يظهر\n";
    } else {
        echo "✅ employee.role = '{$employee->role}'، الزر يجب أن يظهر\n";
    }

    echo "\n💡 الحل المقترح:\n";
    echo "==================\n";
    echo "تعديل الشرط ليكون:\n";
    echo "- استخدام دور المستخدم من جدول roles بدلاً من employee.role\n";
    echo "- أو السماح للمدير العام بتعيين مدير مباشر لنفسه\n";
} else {
    echo "❌ لم يتم العثور على الموظف بالرقم 1\n";
}

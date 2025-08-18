<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔍 فحص محمد الشهراني في النظام\n";
echo "===============================\n\n";

// 1. التحقق من جدول المستخدمين
echo "👤 في جدول المستخدمين (users):\n";
echo "==================================\n";

$user = \DB::table('users')
    ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
    ->where('users.name', 'محمد الشهراني')
    ->select('users.*', 'roles.display_name as role_name')
    ->first();

if ($user) {
    echo "✅ موجود في جدول المستخدمين\n";
    echo "  ID: {$user->id}\n";
    echo "  الاسم: {$user->name}\n";
    echo "  البريد: {$user->email}\n";
    echo "  الدور: {$user->role_name}\n";
    echo "  تاريخ الإنشاء: {$user->created_at}\n";
} else {
    echo "❌ غير موجود في جدول المستخدمين\n";
}

echo "\n";

// 2. التحقق من جدول الموظفين
echo "👥 في جدول الموظفين (employees):\n";
echo "==================================\n";

$employee = \DB::table('employees')
    ->where('name', 'محمد الشهراني')
    ->orWhere('email', 'mohammad.alshahrani@company.com')
    ->first();

if ($employee) {
    echo "✅ موجود في جدول الموظفين\n";
    echo "  ID: {$employee->id}\n";
    echo "  الاسم: {$employee->name}\n";
    echo "  البريد: {$employee->email}\n";
    echo "  الحالة: {$employee->status}\n";
    echo "  تاريخ الإنشاء: {$employee->created_at}\n";
} else {
    echo "❌ غير موجود في جدول الموظفين\n";
}

echo "\n";

// 3. فحص العلاقة بين الجدولين
echo "🔗 العلاقة بين المستخدمين والموظفين:\n";
echo "====================================\n";

$allUsers = \DB::table('users')->get(['id', 'name', 'email']);
echo "إجمالي المستخدمين: " . count($allUsers) . "\n";

$allEmployees = \DB::table('employees')->get(['id', 'name', 'email']);
echo "إجمالي الموظفين: " . count($allEmployees) . "\n";

// 4. فحص structure جدول الموظفين
echo "\n📊 هيكل جدول الموظفين:\n";
echo "========================\n";

try {
    $employeeColumns = \DB::select("PRAGMA table_info(employees)");
    foreach ($employeeColumns as $column) {
        echo "  - {$column->name} ({$column->type})\n";
    }
} catch (Exception $e) {
    echo "خطأ في فحص هيكل الجدول: " . $e->getMessage() . "\n";
}

echo "\n📝 الخلاصة:\n";
echo "============\n";
if ($user && !$employee) {
    echo "💡 محمد الشهراني موجود في جدول المستخدمين فقط، ولكن ليس في جدول الموظفين.\n";
    echo "💡 هذا يعني أنه يمكنه تسجيل الدخول للنظام ولكن لن يظهر في قائمة الموظفين.\n";
    echo "💡 يجب إنشاء سجل له في جدول الموظفين أيضاً.\n";
} elseif (!$user && !$employee) {
    echo "❌ محمد الشهراني غير موجود في أي من الجدولين.\n";
} elseif ($user && $employee) {
    echo "✅ محمد الشهراني موجود في كلا الجدولين.\n";
}

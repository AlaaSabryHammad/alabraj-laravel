<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "📋 فحص بيانات المستخدم الحالي\n";
echo "=============================\n\n";

// الحصول على بيانات المستخدم
$user = \DB::table('users')
    ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
    ->leftJoin('employees', 'users.id', '=', 'employees.user_id')
    ->where('users.id', 1)
    ->select(
        'users.*',
        'roles.display_name as role_display_name',
        'roles.category as role_category',
        'employees.position',
        'employees.department',
        'employees.hire_date',
        'employees.salary',
        'employees.national_id',
        'employees.working_hours',
        'employees.workplace_location',
        'employees.birth_date',
        'employees.nationality',
        'employees.marital_status',
        'employees.children_count'
    )
    ->first();

if ($user) {
    echo "👤 معلومات المستخدم:\n";
    echo "===================\n";
    echo "ID: {$user->id}\n";
    echo "الاسم: {$user->name}\n";
    echo "البريد: {$user->email}\n";
    echo "الهاتف: " . ($user->phone ?? 'غير محدد') . "\n";
    echo "الدور: " . ($user->role_display_name ?? 'غير محدد') . "\n";
    echo "فئة الدور: " . ($user->role_category ?? 'غير محدد') . "\n";

    echo "\n👷 معلومات الموظف:\n";
    echo "==================\n";
    echo "المنصب: " . ($user->position ?? 'غير محدد') . "\n";
    echo "القسم: " . ($user->department ?? 'غير محدد') . "\n";
    echo "تاريخ التوظيف: " . ($user->hire_date ?? 'غير محدد') . "\n";
    echo "الراتب: " . ($user->salary ?? 'غير محدد') . "\n";
    echo "رقم الهوية: " . ($user->national_id ?? 'غير محدد') . "\n";
    echo "ساعات العمل: " . ($user->working_hours ?? 'غير محدد') . "\n";
    echo "مكان العمل: " . ($user->workplace_location ?? 'غير محدد') . "\n";
    echo "تاريخ الميلاد: " . ($user->birth_date ?? 'غير محدد') . "\n";
    echo "الجنسية: " . ($user->nationality ?? 'غير محدد') . "\n";
    echo "الحالة الاجتماعية: " . ($user->marital_status ?? 'غير محدد') . "\n";
    echo "عدد الأطفال: " . ($user->children_count ?? 'غير محدد') . "\n";

    echo "\n📅 تواريخ مهمة:\n";
    echo "===============\n";
    echo "تاريخ إنشاء الحساب: {$user->created_at}\n";
    echo "آخر تحديث: {$user->updated_at}\n";
} else {
    echo "❌ لم يتم العثور على المستخدم\n";
}

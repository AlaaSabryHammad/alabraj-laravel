<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "👥 إنشاء سجل محمد الشهراني في جدول الموظفين\n";
echo "=============================================\n\n";

// الحصول على بيانات المستخدم
$user = \DB::table('users')->where('name', 'محمد الشهراني')->first();

if (!$user) {
    echo "❌ خطأ: لم يتم العثور على المستخدم محمد الشهراني\n";
    exit(1);
}

echo "✅ تم العثور على المستخدم (ID: {$user->id})\n";

// فحص هيكل جدول الموظفين
echo "\n📊 فحص الحقول المطلوبة لجدول الموظفين...\n";

try {
    // استخدام query مناسب لـ MySQL
    $employeeColumns = \DB::select("DESCRIBE employees");

    echo "الحقول الموجودة في جدول employees:\n";
    foreach ($employeeColumns as $column) {
        $nullable = $column->Null == 'YES' ? 'اختياري' : 'مطلوب';
        echo "  - {$column->Field} ({$column->Type}) - {$nullable}\n";
    }
} catch (Exception $e) {
    echo "خطأ في فحص هيكل الجدول: " . $e->getMessage() . "\n";
    exit(1);
}

// إنشاء بيانات الموظف
echo "\n🔄 إنشاء سجل الموظف...\n";

$employeeData = [
    'name' => $user->name,
    'email' => $user->email,
    'phone' => $user->phone ?? '966501234567',
    'national_id' => '1234567890', // رقم هوية تجريبي
    'position' => 'مدير عام', // استخدام position بدلاً من job_title
    'department' => 'الإدارة التنفيذية',
    'hire_date' => now()->format('Y-m-d'),
    'salary' => 15000.00,
    'working_hours' => 8, // حقل مطلوب
    'children_count' => 0, // حقل مطلوب
    'status' => 'active',
    'user_id' => $user->id,
    'created_at' => now(),
    'updated_at' => now()
];

try {
    // التحقق من عدم وجود الموظف مسبقاً
    $existingEmployee = \DB::table('employees')
        ->where('email', $user->email)
        ->orWhere('user_id', $user->id)
        ->first();

    if ($existingEmployee) {
        echo "⚠️ الموظف موجود مسبقاً (ID: {$existingEmployee->id})\n";

        // تحديث البيانات
        \DB::table('employees')
            ->where('id', $existingEmployee->id)
            ->update([
                'name' => $employeeData['name'],
                'phone' => $employeeData['phone'],
                'position' => $employeeData['position'],
                'department' => $employeeData['department'],
                'status' => $employeeData['status'],
                'updated_at' => $employeeData['updated_at']
            ]);

        echo "✅ تم تحديث بيانات الموظف\n";
        $employeeId = $existingEmployee->id;
    } else {
        // إنشاء موظف جديد
        $employeeId = \DB::table('employees')->insertGetId($employeeData);
        echo "✅ تم إنشاء سجل الموظف (ID: {$employeeId})\n";
    }

    // التحقق من النتيجة
    $employee = \DB::table('employees')->where('id', $employeeId)->first();

    echo "\n📋 تفاصيل الموظف:\n";
    echo "==================\n";
    echo "ID: {$employee->id}\n";
    echo "الاسم: {$employee->name}\n";
    echo "البريد: {$employee->email}\n";
    echo "الهاتف: {$employee->phone}\n";
    echo "المسمى الوظيفي: {$employee->position}\n";
    echo "القسم: {$employee->department}\n";
    echo "الحالة: {$employee->status}\n";
    echo "تاريخ التوظيف: {$employee->hire_date}\n";
    echo "الراتب: {$employee->salary}\n";
    echo "ساعات العمل: {$employee->working_hours}\n";

    echo "\n🎉 تم بنجاح! الآن محمد الشهراني سيظهر في قائمة الموظفين.\n";
} catch (Exception $e) {
    echo "❌ خطأ في إنشاء الموظف: " . $e->getMessage() . "\n";
}

<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Illuminate\Http\Request::createFromGlobals();
$response = $kernel->handle($request);

use App\Models\Employee;

try {
    echo "اختبار إنشاء موظف جديد مع الحالة الافتراضية:\n";

    // محاكاة البيانات التي تأتي من النموذج
    $employeeData = [
        'name' => 'موظف تجريبي ' . date('H:i:s'),
        'position' => 'موظف',
        'department' => 'الإدارة',
        'email' => 'test_' . time() . '@test.com',
        'phone' => '123456789',
        'hire_date' => '2025-01-01',
        'salary' => 3000,
        'working_hours' => 8,
        'national_id' => 'test' . time(),
        'role' => 'عامل',
        'sponsorship_status' => 'شركة الأبراج للمقاولات المحدودة',
        'category' => 'A'
    ];

    // إنشاء الموظف
    $employee = Employee::create($employeeData);

    echo "✅ تم إنشاء الموظف بنجاح!\n";
    echo "اسم الموظف: " . $employee->name . "\n";
    echo "حالة الموظف: " . $employee->status . "\n";
    echo "رقم الموظف: " . $employee->employee_number . "\n";

    // حذف الموظف التجريبي
    $employee->delete();
    echo "🗑️ تم حذف الموظف التجريبي\n";

    if ($employee->status === 'inactive') {
        echo "🎉 المطلوب: الحالة الافتراضية 'غير نشط' تعمل بشكل صحيح!\n";
    } else {
        echo "❌ مشكلة: الحالة الافتراضية لا تزال '{$employee->status}' بدلاً من 'inactive'\n";
    }
} catch (Exception $e) {
    echo "❌ خطأ: " . $e->getMessage() . "\n";
}

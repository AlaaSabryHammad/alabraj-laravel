<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

echo "اختبار الحالة الافتراضية للموظف الجديد:\n";

// إنشاء موظف جديد دون حفظ
$employee = new \App\Models\Employee();
echo "الحالة الافتراضية: " . ($employee->status ?? 'null') . "\n";

// إنشاء موظف وحفظه
$testEmployee = \App\Models\Employee::create([
    'name' => 'موظف اختبار الحالة',
    'email' => 'test_status_' . time() . '@test.com',
    'phone' => '123456789'
]);

echo "الحالة بعد الحفظ: " . $testEmployee->status . "\n";

// حذف الموظف التجريبي
$testEmployee->delete();
echo "تم حذف الموظف التجريبي\n";

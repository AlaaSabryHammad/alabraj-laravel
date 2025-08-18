<?php

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

// بوتستراب اللارافل
$kernel->bootstrap();

use App\Models\Employee;
use App\Services\EmailService;
use Illuminate\Support\Facades\Log;

// أي موظف للاختبار
$employee = Employee::first();

if ($employee) {
    echo "بدء إرسال بريد إلكتروني اختباري للموظف: {$employee->name}\n";
    try {
        $result = EmailService::sendNewEmployeeNotification($employee);
        if ($result) {
            echo "تم إرسال البريد الإلكتروني بنجاح\n";
        } else {
            echo "فشل إرسال البريد الإلكتروني\n";
        }
    } catch (\Exception $e) {
        echo "حدث خطأ: " . $e->getMessage() . "\n";
    }
} else {
    echo "لا يوجد موظفين في قاعدة البيانات\n";
}

// طباعة آخر أخطاء مسجلة
echo "\nآخر خطأ مسجل في سجل النظام:\n";
$logFile = storage_path('logs/laravel.log');
if (file_exists($logFile)) {
    $logContent = file_get_contents($logFile);
    $logLines = explode("\n", $logContent);
    $lastErrors = [];

    // البحث عن آخر 5 أخطاء في السجل
    foreach (array_reverse($logLines) as $line) {
        if (strpos($line, 'ERROR') !== false && count($lastErrors) < 5) {
            $lastErrors[] = $line;
        }
    }

    if (count($lastErrors) > 0) {
        echo implode("\n", array_reverse($lastErrors)) . "\n";
    } else {
        echo "لا يوجد أخطاء مسجلة حديثًا\n";
    }
} else {
    echo "ملف السجل غير موجود\n";
}

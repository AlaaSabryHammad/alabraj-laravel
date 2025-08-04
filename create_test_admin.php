<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;

try {
    echo "=== إنشاء مستخدم رئيسي تجريبي ===" . PHP_EOL;

    // التحقق من وجود المستخدم التجريبي
    $testUser = User::where('email', 'admin@alabraaj.com.sa')->first();

    if ($testUser) {
        echo "المستخدم التجريبي موجود بالفعل: " . $testUser->email . PHP_EOL;
        echo "الدور: " . $testUser->role . PHP_EOL;
    } else {
        // إنشاء موظف تجريبي أولاً
        $testEmployee = Employee::create([
            'name' => 'مدير النظام التجريبي',
            'position' => 'مدير عام',
            'department' => 'الإدارة العليا',
            'email' => 'admin@alabraaj.com.sa',
            'phone' => '0501234567',
            'hire_date' => now(),
            'salary' => 15000,
            'national_id' => '1111111111',
            'role' => 'مسئول رئيسي',
            'sponsorship' => 'شركة الأبراج للمقاولات المحدودة',
            'category' => 'A+',
            'status' => 'active',
        ]);

        echo "تم إنشاء الموظف التجريبي: " . $testEmployee->name . PHP_EOL;

        // إنشاء المستخدم
        $testUser = User::create([
            'name' => 'مدير النظام التجريبي',
            'email' => 'admin@alabraaj.com.sa',
            'password' => Hash::make('1111111111'), // كلمة المرور الافتراضية = رقم الهوية
            'role' => 'manager',
        ]);

        // ربط المستخدم بالموظف
        $testEmployee->update(['user_id' => $testUser->id]);

        echo "تم إنشاء المستخدم التجريبي بنجاح!" . PHP_EOL;
        echo "البريد الإلكتروني: admin@alabraaj.com.sa" . PHP_EOL;
        echo "كلمة المرور الافتراضية: 1111111111" . PHP_EOL;
        echo "الدور: manager" . PHP_EOL;
        echo "ملاحظة: ستُطلب منك تغيير كلمة المرور عند أول تسجيل دخول" . PHP_EOL;
    }

    echo PHP_EOL . "=== قائمة جميع المستخدمين الرئيسيين ===" . PHP_EOL;
    $managers = User::where('role', 'manager')->with('employee')->get();

    foreach($managers as $manager) {
        echo "البريد الإلكتروني: " . $manager->email . PHP_EOL;
        echo "الاسم: " . $manager->name . PHP_EOL;
        if ($manager->employee) {
            echo "رقم الهوية: " . $manager->employee->national_id . PHP_EOL;
            $hasDefaultPassword = Hash::check($manager->employee->national_id, $manager->password);
            echo "كلمة المرور الافتراضية: " . ($hasDefaultPassword ? 'نعم - يحتاج تغيير' : 'لا - تم تغييرها') . PHP_EOL;
        }
        echo "---" . PHP_EOL;
    }

} catch (Exception $e) {
    echo "خطأ: " . $e->getMessage() . PHP_EOL;
    echo "التفاصيل: " . $e->getTraceAsString() . PHP_EOL;
}

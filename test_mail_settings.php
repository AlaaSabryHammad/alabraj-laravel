<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

// تهيئة البيئة وإعدادات Laravel
$app->make(Illuminate\Contracts\Console\Kernel::class)
    ->bootstrap();

// احصل على قيم إعدادات البريد الإلكتروني
$mailer = config('mail.default');
$host = config('mail.mailers.smtp.host');
$port = config('mail.mailers.smtp.port');
$username = config('mail.mailers.smtp.username');
$password = config('mail.mailers.smtp.password');
$encryption = config('mail.mailers.smtp.encryption');

$fromAddress = config('mail.from.address');
$fromName = config('mail.from.name');

echo "إعدادات البريد الإلكتروني الحالية:\n";
echo "-------------------------------------\n";
echo "نوع مرسل البريد الإلكتروني: $mailer\n";
echo "خادم SMTP: $host\n";
echo "منفذ SMTP: $port\n";
echo "اسم المستخدم: $username\n";
echo "كلمة المرور: " . ($password ? '(تم تعيينها)' : '(غير معينة)') . "\n";
echo "التشفير: $encryption\n";
echo "عنوان المرسل: $fromAddress\n";
echo "اسم المرسل: $fromName\n";

echo "\n";
echo "محاولة إرسال بريد إلكتروني اختباري...\n";

try {
    // إرسال رسالة اختبارية
    \Illuminate\Support\Facades\Mail::raw('هذه رسالة اختبارية من نظام شركة الأبراج للمقاولات', function ($message) {
        $message->to('test@example.com')
            ->subject('اختبار إرسال البريد الإلكتروني');
    });
    echo "تم إرسال البريد الإلكتروني الاختباري بنجاح!\n";
} catch (\Exception $e) {
    echo "حدث خطأ أثناء إرسال البريد الإلكتروني: " . $e->getMessage() . "\n";
}

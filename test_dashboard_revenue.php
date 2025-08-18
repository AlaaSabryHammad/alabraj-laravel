<?php

require 'vendor/autoload.php';

// إنشاء البيئة
putenv('APP_ENV=local');

$app = require_once 'bootstrap/app.php';

// إعداد قاعدة البيانات
$app->instance('path.database', __DIR__ . '/database');

try {
    // اختبار الداشبورد
    $controller = new App\Http\Controllers\DashboardController();

    // استخدام reflection للوصول للدالة الخاصة
    $reflection = new ReflectionClass($controller);
    $method = $reflection->getMethod('getMonthlyRevenue');
    $method->setAccessible(true);

    $revenue = $method->invoke($controller);

    echo "إيرادات الشهر المحسوبة: " . number_format($revenue) . " ر.س\n";

    if ($revenue > 0) {
        echo "✅ الداشبورد يعرض إيرادات حقيقية\n";
    } else {
        echo "⚠️ لا توجد إيرادات للشهر الحالي\n";
    }
} catch (Exception $e) {
    echo "خطأ: " . $e->getMessage() . "\n";
}

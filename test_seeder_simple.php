<?php

require_once 'vendor/autoload.php';

// استخدام Laravel bootstrapper
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🧪 اختبار تشغيل RolesAndPermissionsSeeder المحدث\n";
echo "==============================================\n\n";

// تشغيل الـ seeder
try {
    echo "🔄 تشغيل الـ seeder...\n";

    $seeder = new Database\Seeders\RolesAndPermissionsSeeder();
    $seeder->run();

    echo "✅ تم تشغيل الـ seeder بنجاح!\n\n";

    // عرض النتائج
    echo "📊 الأدوار المنشأة:\n";
    echo "===================\n";

    $roles = \DB::table('roles')->orderBy('category', 'asc')->orderBy('id', 'asc')->get();

    $currentCategory = '';
    foreach ($roles as $role) {
        if ($role->category !== $currentCategory) {
            $currentCategory = $role->category;
            echo "\n🏷️ {$currentCategory}:\n";
            echo str_repeat('-', 20) . "\n";
        }
        echo "  - {$role->display_name} ({$role->name})\n";
    }

    echo "\n📈 إحصائيات:\n";
    echo "================\n";
    echo "إجمالي الأدوار: " . \DB::table('roles')->count() . "\n";
    echo "إجمالي الصلاحيات: " . \DB::table('permissions')->count() . "\n";
    echo "إجمالي روابط الأدوار-الصلاحيات: " . \DB::table('role_permissions')->count() . "\n";
} catch (Exception $e) {
    echo "❌ خطأ: " . $e->getMessage() . "\n";
    echo "في الملف: " . $e->getFile() . " السطر: " . $e->getLine() . "\n";
}

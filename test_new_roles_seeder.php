<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔄 تشغيل Seeder الأدوار المحدث\n";
echo "===============================\n\n";

// تشغيل الـ seeder
try {
    echo "🗑️ حذف الأدوار القديمة...\n";

    // حذف البيانات القديمة
    \DB::table('role_permissions')->delete();
    \DB::table('roles')->delete();

    echo "✅ تم حذف الأدوار القديمة\n\n";

    echo "🔄 تشغيل RolesAndPermissionsSeeder...\n";

    $seeder = new Database\Seeders\RolesAndPermissionsSeeder();
    $seeder->run();

    echo "✅ تم تشغيل الـ seeder بنجاح!\n\n";

    // عرض النتائج
    echo "📊 الأدوار الجديدة:\n";
    echo "===================\n";

    $roles = \DB::table('roles')->orderBy('category')->orderBy('id')->get();

    $categories = [];
    foreach ($roles as $role) {
        if (!isset($categories[$role->category])) {
            $categories[$role->category] = [];
        }
        $categories[$role->category][] = $role;
    }

    foreach ($categories as $category => $categoryRoles) {
        echo "\n🏷️ {$category}:\n";
        echo str_repeat('-', 20) . "\n";
        foreach ($categoryRoles as $role) {
            echo "  - {$role->display_name} ({$role->name})\n";
        }
    }

    echo "\n📈 إحصائيات:\n";
    echo "================\n";
    echo "إجمالي الأدوار: " . \DB::table('roles')->count() . "\n";
    echo "إجمالي الصلاحيات: " . \DB::table('permissions')->count() . "\n";
    echo "إجمالي روابط الأدوار-الصلاحيات: " . \DB::table('role_permissions')->count() . "\n";

    // التحقق من الأدوار المطلوبة
    echo "\n✅ التحقق من الأدوار المطلوبة:\n";
    echo "===============================\n";

    $requiredRoles = [
        'general_manager' => 'مدير عام',
        'project_manager' => 'مدير مشاريع',
        'engineer' => 'مهندس',
        'financial_manager' => 'مدير مالي',
        'accountant' => 'محاسب',
        'manager' => 'مدير',
        'driver' => 'سائق',
        'security' => 'أمن',
        'worker' => 'عامل',
        'warehouse_manager' => 'أمين مستودع',
        'workship_manager' => 'أمين ورشة',
        'site_manager' => 'مشرف موقع',
        'fuel_manager' => 'سائق تانك محروقات',
        'truck_driver' => 'سائق شاحنة'
    ];

    $currentRoles = \DB::table('roles')->pluck('display_name', 'name')->toArray();

    $allFound = true;
    foreach ($requiredRoles as $name => $displayName) {
        if (isset($currentRoles[$name])) {
            echo "✅ {$displayName}\n";
        } else {
            echo "❌ مفقود: {$displayName}\n";
            $allFound = false;
        }
    }

    echo "\n" . ($allFound ? "🎉 جميع الأدوار المطلوبة موجودة!" : "⚠️ هناك أدوار مفقودة") . "\n";
} catch (Exception $e) {
    echo "❌ خطأ: " . $e->getMessage() . "\n";
    echo "في الملف: " . $e->getFile() . " السطر: " . $e->getLine() . "\n";
}

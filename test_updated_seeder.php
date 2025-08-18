<?php

require_once 'vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as DB;

// إعداد قاعدة البيانات
$capsule = new DB;
$capsule->addConnection([
    'driver' => 'sqlite',
    'database' => __DIR__ . '/database/database.sqlite',
    'prefix' => '',
]);
$capsule->setAsGlobal();
$capsule->bootEloquent();

echo "🧪 اختبار الـ Seeder المحدث\n";
echo "=================================\n\n";

// حذف جميع الأدوار الحالية لاختبار الـ seeder
try {
    // حذف الروابط أولاً (تحقق من وجود الجداول أولاً)
    $tables = ['role_permissions', 'user_roles', 'roles', 'permissions'];

    foreach ($tables as $table) {
        try {
            // تحقق من وجود الجدول أولاً
            $exists = DB::select("SELECT name FROM sqlite_master WHERE type='table' AND name='{$table}'");
            if (!empty($exists)) {
                DB::table($table)->delete();
                echo "✅ تم حذف محتويات جدول {$table}\n";
            } else {
                echo "⚠️ جدول {$table} غير موجود\n";
            }
        } catch (Exception $e) {
            echo "⚠️ تخطي جدول {$table}: " . $e->getMessage() . "\n";
        }
    }

    echo "\n";

    // تشغيل الـ seeder
    echo "🔄 تشغيل RolesAndPermissionsSeeder...\n";

    // محاكاة تشغيل الـ seeder
    require_once 'database/seeders/RolesAndPermissionsSeeder.php';

    $seeder = new RolesAndPermissionsSeeder();
    $seeder->run();

    echo "✅ تم تشغيل الـ seeder بنجاح!\n\n";

    // التحقق من النتائج
    echo "📊 الأدوار الجديدة المنشأة:\n";
    echo "============================\n";

    $roles = DB::table('roles')->orderBy('id')->get(['id', 'name', 'display_name', 'category']);

    foreach ($roles as $role) {
        echo "ID: {$role->id} | Name: {$role->name} | Display: {$role->display_name} | Category: {$role->category}\n";
    }

    echo "\n📈 إحصائيات:\n";
    echo "================\n";
    echo "إجمالي الأدوار: " . DB::table('roles')->count() . "\n";
    echo "إجمالي الصلاحيات: " . DB::table('permissions')->count() . "\n";
    echo "إجمالي روابط الأدوار-الصلاحيات: " . DB::table('role_permissions')->count() . "\n";

    // عرض تصنيفات الأدوار
    echo "\n🏷️ الأدوار حسب التصنيف:\n";
    echo "========================\n";

    $categories = DB::table('roles')
        ->select('category', DB::raw('COUNT(*) as count'))
        ->groupBy('category')
        ->get();

    foreach ($categories as $category) {
        echo "{$category->category}: {$category->count} أدوار\n";

        $rolesInCategory = DB::table('roles')
            ->where('category', $category->category)
            ->pluck('display_name');

        foreach ($rolesInCategory as $roleDisplayName) {
            echo "  - {$roleDisplayName}\n";
        }
        echo "\n";
    }
} catch (Exception $e) {
    echo "❌ خطأ: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

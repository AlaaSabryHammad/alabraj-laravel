<?php
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Permission;
use App\Models\Role;

try {
    echo "🧪 اختبار RolesAndPermissionsSeeder الجديد\n\n";

    // حذف البيانات الموجودة
    echo "🧹 تنظيف البيانات الموجودة...\n";
    \DB::table('role_permissions')->delete();
    \DB::table('user_roles')->delete();
    \DB::table('permissions')->delete();
    \DB::table('roles')->delete();

    // تنفيذ الـ seeder
    echo "🚀 تنفيذ RolesAndPermissionsSeeder...\n";
    $seeder = new \Database\Seeders\RolesAndPermissionsSeeder();
    $seeder->run();

    echo "\n✅ تم الانتهاء بنجاح!\n\n";

    // فحص النتائج
    echo "📊 النتائج:\n";
    echo "عدد الصلاحيات: " . Permission::count() . "\n";
    echo "عدد الأدوار: " . Role::count() . "\n\n";

    echo "📋 صلاحيات المشاريع:\n";
    $projectPermissions = Permission::where('category', 'المشاريع')->get(['name', 'display_name']);
    foreach ($projectPermissions as $perm) {
        echo "• {$perm->display_name} ({$perm->name})\n";
    }

    echo "\n👥 الأدوار المتوفرة:\n";
    $roles = Role::all(['name', 'display_name']);
    foreach ($roles as $role) {
        echo "• {$role->display_name} ({$role->name})\n";
    }

    // فحص دور مدير المشاريع
    echo "\n🔍 فحص دور مدير المشاريع:\n";
    $projectManager = Role::where('name', 'project_manager')->first();
    if ($projectManager) {
        $projectPermissionsCount = $projectManager->permissions()->where('category', 'المشاريع')->count();
        $totalPermissions = $projectManager->permissions()->count();
        echo "✅ دور مدير المشاريع موجود\n";
        echo "صلاحيات المشاريع: {$projectPermissionsCount}\n";
        echo "إجمالي الصلاحيات: {$totalPermissions}\n";
    } else {
        echo "❌ دور مدير المشاريع غير موجود\n";
    }
} catch (Exception $e) {
    echo "❌ خطأ: " . $e->getMessage() . "\n";
    echo "في الملف: " . $e->getFile() . " السطر: " . $e->getLine() . "\n";
}

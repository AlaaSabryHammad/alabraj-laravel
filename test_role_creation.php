<?php
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Role;
use App\Models\Permission;

try {
    echo "🧪 اختبار إنشاء دور جديد مع الصلاحيات\n\n";

    // إنشاء دور جديد
    $role = Role::create([
        'name' => 'test_role_' . time(),
        'display_name' => 'دور تجريبي',
        'description' => 'دور للاختبار',
        'is_active' => true
    ]);

    echo "✅ تم إنشاء الدور بنجاح: {$role->display_name}\n";
    echo "معرف الدور: {$role->id}\n\n";

    // إضافة صلاحيات للدور
    $permissions = ['employees.view', 'reports.view', 'equipment.view'];

    foreach ($permissions as $permissionName) {
        $permission = Permission::where('name', $permissionName)->first();
        if ($permission) {
            DB::table('role_permissions')->insert([
                'role_id' => $role->id,
                'permission_id' => $permission->id,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            echo "✅ تم ربط الصلاحية: {$permission->display_name}\n";
        } else {
            echo "❌ صلاحية غير موجودة: {$permissionName}\n";
        }
    }

    echo "\n📊 فحص صلاحيات الدور:\n";
    $rolePermissions = $role->permissions()->pluck('display_name', 'name')->toArray();
    foreach ($rolePermissions as $name => $displayName) {
        echo "• {$displayName} ({$name})\n";
    }

    echo "\n✅ اختبار الدور مكتمل!\n";
    echo "عدد الصلاحيات المربوطة: " . count($rolePermissions) . "\n";

    // تنظيف - حذف الدور التجريبي
    DB::table('role_permissions')->where('role_id', $role->id)->delete();
    $role->delete();
    echo "\n🧹 تم حذف الدور التجريبي\n";
} catch (Exception $e) {
    echo "❌ خطأ: " . $e->getMessage() . "\n";
    echo "في الملف: " . $e->getFile() . " السطر: " . $e->getLine() . "\n";
}

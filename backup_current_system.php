<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

use App\Models\Role;
use App\Models\Permission;
use App\Models\User;

echo "🔍 حفظ الأدوار والصلاحيات الحالية\n";
echo "==============================\n\n";

try {
    // حفظ الأدوار
    $roles = Role::all();
    echo "📝 الأدوار الموجودة (" . $roles->count() . "):\n";

    $rolesData = [];
    foreach ($roles as $role) {
        $roleData = [
            'name' => $role->name,
            'display_name' => $role->display_name,
            'description' => $role->description,
            'category' => $role->category,
            'is_active' => $role->is_active,
            'permissions' => []
        ];

        // حفظ الصلاحيات المرتبطة بهذا الدور
        if ($role->permissions) {
            foreach ($role->permissions as $permission) {
                $roleData['permissions'][] = [
                    'name' => $permission->name,
                    'display_name' => $permission->display_name,
                    'description' => $permission->description,
                    'category' => $permission->category,
                ];
            }
        }

        $rolesData[] = $roleData;
        echo "  - {$role->name} ({$role->display_name})\n";
    }

    // حفظ البيانات في ملف JSON
    file_put_contents('backup_roles_permissions.json', json_encode($rolesData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

    echo "\n✅ تم حفظ البيانات في backup_roles_permissions.json\n";

    // عرض الصلاحيات
    $permissions = Permission::all();
    echo "\n📋 الصلاحيات الموجودة (" . $permissions->count() . "):\n";
    foreach ($permissions as $permission) {
        echo "  - {$permission->name} ({$permission->display_name})\n";
    }
} catch (Exception $e) {
    echo "❌ خطأ: " . $e->getMessage() . "\n";
}

echo "\n🚀 جاهز لعمل migrate:fresh\n";

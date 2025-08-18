<?php

require_once 'vendor/autoload.php';

// إعداد Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Permission;
use App\Models\Role;

echo "=====================================\n";
echo "فحص الصلاحيات المتقدمة الجديدة\n";
echo "=====================================\n\n";

// فحص صلاحيات الموظفين المتقدمة
$employeePermissions = Permission::where('category', 'الموظفين')->get();
echo "صلاحيات الموظفين المتقدمة (" . $employeePermissions->count() . "):\n";
echo "--------------------------------\n";
foreach ($employeePermissions as $permission) {
    echo "- {$permission->name} => {$permission->display_name}\n";
}

echo "\n";

// فحص صلاحيات المعدات المتقدمة
$equipmentPermissions = Permission::where('category', 'المعدات')->get();
echo "صلاحيات المعدات المتقدمة (" . $equipmentPermissions->count() . "):\n";
echo "--------------------------------\n";
foreach ($equipmentPermissions as $permission) {
    echo "- {$permission->name} => {$permission->display_name}\n";
}

echo "\n";

// فحص صلاحيات الإعدادات المتقدمة
$settingsPermissions = Permission::where('category', 'الإعدادات')->get();
echo "صلاحيات الإعدادات المتقدمة (" . $settingsPermissions->count() . "):\n";
echo "--------------------------------\n";
foreach ($settingsPermissions as $permission) {
    echo "- {$permission->name} => {$permission->display_name}\n";
}

echo "\n=====================================\n";
echo "الأدوار الجديدة والمحدثة\n";
echo "=====================================\n\n";

// فحص المدير التقني
$techManager = Role::where('name', 'tech_manager')->first();
if ($techManager) {
    echo "المدير التقني ({$techManager->display_name}):\n";
    echo "- عدد الصلاحيات: " . count($techManager->permissions ?? []) . "\n";
    echo "- الوصف: {$techManager->description}\n\n";
}

// فحص مدير الموارد البشرية
$hrManager = Role::where('name', 'hr_manager')->first();
if ($hrManager) {
    echo "مدير الموارد البشرية ({$hrManager->display_name}):\n";
    echo "- عدد الصلاحيات: " . count($hrManager->permissions ?? []) . "\n";
    echo "- الوصف: {$hrManager->description}\n\n";
}

// فحص إجمالي الصلاحيات
$totalPermissions = Permission::count();
echo "إجمالي الصلاحيات في النظام: {$totalPermissions}\n";

// فحص إجمالي الأدوار  
$totalRoles = Role::count();
echo "إجمالي الأدوار في النظام: {$totalRoles}\n\n";

// فحص الأدوار مع عدد صلاحياتها
$allRoles = Role::all();
echo "تفاصيل جميع الأدوار:\n";
echo "====================\n";
foreach ($allRoles as $role) {
    $permCount = count($role->permissions ?? []);
    echo "- {$role->display_name} ({$role->name}): {$permCount} صلاحية\n";
}

echo "\n✅ تم فحص النظام بنجاح!\n";

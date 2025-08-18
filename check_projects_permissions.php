<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🚀 صلاحيات المشاريع المُضافة:\n\n";

$projectsPermissions = App\Models\Permission::where('category', 'المشاريع')->get();

$categories = [
    'إدارة أساسية' => ['projects.view', 'projects.create', 'projects.edit', 'projects.delete'],
    'إدارة الحالة' => ['projects.status', 'projects.approve', 'projects.archive'],
    'إدارة الفريق' => ['projects.manage_team', 'projects.assign_roles'],
    'إدارة المهام' => ['projects.manage_tasks', 'projects.assign_tasks', 'projects.track_progress'],
    'الميزانية' => ['projects.manage_budget', 'projects.view_budget', 'projects.approve_expenses'],
    'الموارد' => ['projects.manage_resources', 'projects.allocate_equipment', 'projects.allocate_materials'],
    'التقارير' => ['projects.reports', 'projects.analytics', 'projects.timeline'],
    'التعاون' => ['projects.communicate', 'projects.meetings'],
    'الجودة والمخاطر' => ['projects.risk_management', 'projects.quality_control']
];

foreach ($categories as $categoryName => $permissionsList) {
    echo "📁 $categoryName:\n";
    foreach ($permissionsList as $permName) {
        $permission = $projectsPermissions->where('name', $permName)->first();
        if ($permission) {
            echo "   ✅ " . $permission->display_name . " (" . $permission->name . ")\n";
        }
    }
    echo "\n";
}

echo "العدد الإجمالي: " . $projectsPermissions->count() . " صلاحية\n\n";

// عرض الدور الجديد
echo "👤 الدور الجديد: مدير مشاريع\n\n";
$projectManager = App\Models\Role::where('name', 'project_manager')->first();
if ($projectManager) {
    echo "- الاسم: " . $projectManager->display_name . "\n";
    echo "- الفئة: " . $projectManager->category . "\n";
    echo "- الوصف: " . $projectManager->description . "\n";
    echo "- عدد الصلاحيات: " . count($projectManager->permissions ?? []) . "\n\n";
}

// عرض الأدوار المحدثة
echo "🔄 الأدوار المحدثة بصلاحيات المشاريع:\n\n";
$rolesWithProjectPermissions = App\Models\Role::all()->filter(function ($role) {
    $permissions = $role->permissions ?? [];
    return collect($permissions)->contains(function ($perm) {
        return strpos($perm, 'projects.') === 0;
    });
});

foreach ($rolesWithProjectPermissions as $role) {
    $projectPermissions = array_filter($role->permissions ?? [], function ($perm) {
        return strpos($perm, 'projects.') === 0;
    });

    echo "- " . $role->display_name . " (" . count($projectPermissions) . " صلاحية مشاريع)\n";
}

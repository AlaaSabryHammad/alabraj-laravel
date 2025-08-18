<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "📄 صلاحيات المستندات المُضافة:\n\n";

$documentsPermissions = App\Models\Permission::where('category', 'المستندات')->get();

foreach ($documentsPermissions as $permission) {
    echo "✅ " . $permission->display_name . " (" . $permission->name . ")\n";
    echo "   " . $permission->description . "\n\n";
}

echo "العدد الإجمالي: " . $documentsPermissions->count() . " صلاحية\n\n";

// عرض الأدوار التي تملك صلاحيات المستندات
echo "🔐 الأدوار التي تملك صلاحيات المستندات:\n\n";

$roles = App\Models\Role::whereJsonContains('permissions', 'documents.view')->get();
foreach ($roles as $role) {
    $docPermissions = array_filter($role->permissions ?? [], function ($perm) {
        return strpos($perm, 'documents.') === 0;
    });

    echo "- " . $role->display_name . " (" . count($docPermissions) . " صلاحية مستندات)\n";
    foreach ($docPermissions as $perm) {
        echo "  • " . $perm . "\n";
    }
    echo "\n";
}

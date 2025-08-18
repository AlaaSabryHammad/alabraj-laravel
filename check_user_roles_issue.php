<?php

require_once 'vendor/autoload.php';

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Role;

echo "=== فحص مشكلة عرض الأدوار في القائمة الجانبية ===\n\n";

// جلب المستخدمين
$users = User::with(['roles', 'employee'])->get();

foreach ($users as $user) {
    echo "المستخدم: {$user->name}\n";
    echo "البريد الإلكتروني: {$user->email}\n";
    echo "role_id: " . ($user->role_id ?? 'null') . "\n";
    echo "role (old): " . ($user->role ?? 'null') . "\n";

    // فحص role_id
    if ($user->role_id) {
        $role = Role::find($user->role_id);
        if ($role) {
            echo "دور من role_id: {$role->name} - {$role->display_name}\n";
        }
    }

    // فحص علاقة many-to-many
    if ($user->roles && $user->roles->isNotEmpty()) {
        echo "أدوار من many-to-many:\n";
        foreach ($user->roles as $role) {
            echo "  - {$role->name} - {$role->display_name}\n";
        }
    }

    echo "getCurrentRoleDisplayName(): " . $user->getCurrentRoleDisplayName() . "\n";
    echo "---\n\n";
}

echo "\n=== فحص جميع الأدوار المتاحة ===\n";
$roles = Role::all();
foreach ($roles as $role) {
    echo "- {$role->name} => {$role->display_name}\n";
}

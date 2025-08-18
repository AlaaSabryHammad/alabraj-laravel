<?php

require_once 'vendor/autoload.php';

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Role;
use App\Models\Employee;

echo "=== فحص الأدوار الحالية للمستخدمين ===\n\n";

// جلب جميع المستخدمين مع بياناتهم
$users = User::with(['roles', 'employee'])->get();

foreach ($users as $user) {
    echo "المستخدم: {$user->name}\n";
    echo "البريد الإلكتروني: {$user->email}\n";
    echo "role_id: " . ($user->role_id ?? 'null') . "\n";
    echo "role (old): " . ($user->role ?? 'null') . "\n";

    // فحص الموظف
    if ($user->employee) {
        echo "معرف الموظف: {$user->employee->id}\n";
        echo "منصب الموظف: " . ($user->employee->position ?? 'غير محدد') . "\n";
        echo "قسم الموظف: " . ($user->employee->department ?? 'غير محدد') . "\n";
    }

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
$roles = Role::orderBy('id')->get();
foreach ($roles as $role) {
    echo "{$role->id}. {$role->name} => {$role->display_name}\n";
}

echo "\n=== اقتراح الأدوار الصحيحة حسب المنصب ===\n";
foreach ($users as $user) {
    if ($user->employee && $user->employee->position) {
        $position = $user->employee->position;
        echo "{$user->name} - منصب: {$position}\n";

        // اقتراح الدور حسب المنصب
        $suggestedRole = '';
        if (str_contains($position, 'مدير عام')) {
            $suggestedRole = 'general_manager';
        } elseif (str_contains($position, 'مدير مشاريع')) {
            $suggestedRole = 'project_manager';
        } elseif (str_contains($position, 'مدير مالي')) {
            $suggestedRole = 'financial_manager';
        } elseif (str_contains($position, 'مدير')) {
            $suggestedRole = 'manager';
        } elseif (str_contains($position, 'مهندس')) {
            $suggestedRole = 'engineer';
        } elseif (str_contains($position, 'محاسب')) {
            $suggestedRole = 'accountant';
        } elseif (str_contains($position, 'مشرف')) {
            $suggestedRole = 'site_manager';
        } elseif (str_contains($position, 'سائق')) {
            $suggestedRole = 'driver';
        }

        if ($suggestedRole) {
            $role = Role::where('name', $suggestedRole)->first();
            if ($role) {
                echo "  اقتراح: {$role->display_name}\n";
            }
        }
        echo "\n";
    }
}

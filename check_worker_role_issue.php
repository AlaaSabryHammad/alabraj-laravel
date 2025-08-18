<?php

require_once 'vendor/autoload.php';

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Role;

echo "=== فحص مشكلة ظهور 'موظف ميداني' للعامل ===\n\n";

// جلب جميع المستخدمين
$users = User::with(['roles', 'employee'])->get();

echo "=== جميع المستخدمين وأدوارهم ===\n";
foreach ($users as $user) {
    echo "المستخدم: {$user->name}\n";
    echo "البريد: {$user->email}\n";
    echo "role_id: " . ($user->role_id ?? 'null') . "\n";
    echo "role (old): " . ($user->role ?? 'null') . "\n";

    if ($user->role_id) {
        $role = Role::find($user->role_id);
        if ($role) {
            echo "دور من role_id: {$role->name} - {$role->display_name}\n";
        }
    }

    if ($user->roles && $user->roles->isNotEmpty()) {
        echo "أدوار من many-to-many:\n";
        foreach ($user->roles as $role) {
            echo "  - {$role->name} - {$role->display_name}\n";
        }
    }

    echo "getCurrentRoleDisplayName(): '" . $user->getCurrentRoleDisplayName() . "'\n";
    echo "---\n\n";
}

// فحص خاص للمستخدمين الذين دورهم عامل
echo "=== المستخدمون الذين دورهم عامل ===\n";
$workers = User::with(['roles', 'employee'])->whereHas('roles', function ($query) {
    $query->where('name', 'worker');
})->get();

foreach ($workers as $user) {
    echo "عامل: {$user->name}\n";
    echo "يظهر له: '{$user->getCurrentRoleDisplayName()}'\n";
    echo "منصبه: " . ($user->employee ? $user->employee->position : 'غير محدد') . "\n";
    echo "\n";
}

// فحص User model للتأكد من دالة getCurrentRoleDisplayName
echo "=== فحص دالة getCurrentRoleDisplayName ===\n";
$sampleUser = $users->first();
if ($sampleUser) {
    echo "عينة مستخدم: {$sampleUser->name}\n";
    echo "role_id: " . ($sampleUser->role_id ?? 'null') . "\n";
    echo "role (old): " . ($sampleUser->role ?? 'null') . "\n";
    echo "لديه roles؟ " . ($sampleUser->roles && $sampleUser->roles->isNotEmpty() ? 'نعم' : 'لا') . "\n";

    // محاكاة الدالة خطوة بخطوة
    if ($sampleUser->role_id) {
        $role = Role::find($sampleUser->role_id);
        if ($role) {
            echo "سيعرض من role_id: {$role->display_name}\n";
        }
    } elseif ($sampleUser->roles && $sampleUser->roles->isNotEmpty()) {
        echo "سيعرض من many-to-many: {$sampleUser->roles->first()->display_name}\n";
    } else {
        echo "سيعرض من النظام القديم لـ: {$sampleUser->role}\n";
    }
}

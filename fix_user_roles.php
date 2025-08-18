<?php

require_once 'vendor/autoload.php';

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Role;

echo "=== إصلاح مشكلة عرض الأدوار ===\n\n";

// العثور على المستخدمين الذين ليس لديهم role_id
$usersWithoutRoleId = User::whereNull('role_id')->get();

echo "المستخدمون الذين يحتاجون إصلاح:\n";
foreach ($usersWithoutRoleId as $user) {
    echo "- {$user->name} ({$user->email}) - دور قديم: {$user->role}\n";
}

echo "\nهل تريد إصلاح هذه المشكلة؟ (y/n): ";
$input = trim(fgets(STDIN));

if (strtolower($input) === 'y' || strtolower($input) === 'yes') {

    // إنشاء خريطة من الأدوار القديمة إلى الجديدة
    $roleMapping = [
        'admin' => 'general_manager',      // مدير النظام → مدير عام
        'manager' => 'project_manager',    // مدير → مدير مشاريع  
        'finance' => 'financial_manager',  // مالية → مدير مالي
        'employee' => 'worker'             // موظف → عامل
    ];

    $updated = 0;

    foreach ($usersWithoutRoleId as $user) {
        $oldRole = $user->role;
        $newRoleName = $roleMapping[$oldRole] ?? 'worker'; // افتراضي عامل

        $role = Role::where('name', $newRoleName)->first();
        if ($role) {
            $user->role_id = $role->id;
            $user->save();

            // إضافة الدور إلى علاقة many-to-many أيضاً
            if (!$user->roles->contains($role->id)) {
                $user->roles()->attach($role->id);
            }

            echo "✅ تم تحديث {$user->name}: {$oldRole} → {$role->display_name}\n";
            $updated++;
        }
    }

    echo "\n🎉 تم تحديث {$updated} مستخدم بنجاح!\n";
} else {
    echo "تم إلغاء العملية.\n";
}

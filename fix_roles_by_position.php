<?php

require_once 'vendor/autoload.php';

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Role;

echo "=== إصلاح الأدوار حسب المناصب الفعلية ===\n\n";

// المستخدمون الذين يحتاجون إصلاح
$usersToFix = [
    [
        'name' => 'سالم الاحمدي',
        'email' => '20656245@alabraaj.com.sa',
        'correct_role' => 'site_manager' // مشرف موقع
    ],
    [
        'name' => 'محمد السمادوني',
        'email' => '11223344@alabraaj.com.sa',
        'correct_role' => 'engineer' // مهندس
    ]
];

$updated = 0;

foreach ($usersToFix as $userInfo) {
    $user = User::where('email', $userInfo['email'])->first();

    if ($user) {
        $newRole = Role::where('name', $userInfo['correct_role'])->first();

        if ($newRole) {
            // إزالة الدور القديم من many-to-many
            $user->roles()->detach();

            // تحديث role_id
            $user->role_id = $newRole->id;
            $user->save();

            // إضافة الدور الجديد إلى many-to-many
            $user->roles()->attach($newRole->id);

            echo "✅ تم تحديث {$user->name}:\n";
            echo "   من: عامل\n";
            echo "   إلى: {$newRole->display_name}\n";
            echo "   المنصب: {$user->employee->position}\n\n";

            $updated++;
        }
    }
}

echo "🎉 تم تحديث {$updated} مستخدم بنجاح!\n\n";

// فحص النتائج النهائية
echo "=== النتائج النهائية ===\n";
$users = User::with(['roles', 'employee'])->get();
foreach ($users as $user) {
    $position = $user->employee ? $user->employee->position : 'غير محدد';
    echo "{$user->name}:\n";
    echo "  المنصب: {$position}\n";
    echo "  الدور: {$user->getCurrentRoleDisplayName()}\n\n";
}

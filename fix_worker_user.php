<?php

require_once 'vendor/autoload.php';

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Role;

echo "=== إصلاح مستخدم 'العامل الاول' ===\n\n";

// العثور على المستخدم
$worker = User::where('email', '256245655@alabraaj.com.sa')->first();

if ($worker) {
    echo "تم العثور على المستخدم: {$worker->name}\n";
    echo "الحالة الحالية:\n";
    echo "- role_id: " . ($worker->role_id ?? 'null') . "\n";
    echo "- role (old): " . ($worker->role ?? 'null') . "\n";
    echo "- getCurrentRoleDisplayName(): '{$worker->getCurrentRoleDisplayName()}'\n\n";

    // العثور على دور العامل
    $workerRole = Role::where('name', 'worker')->first();

    if ($workerRole) {
        echo "سيتم تطبيق دور: {$workerRole->display_name}\n\n";

        // تحديث المستخدم
        $worker->role_id = $workerRole->id;
        $worker->save();

        // إضافة الدور إلى علاقة many-to-many
        if (!$worker->roles->contains($workerRole->id)) {
            $worker->roles()->attach($workerRole->id);
        }

        // إعادة تحميل البيانات للتحقق
        $worker->refresh();
        $worker->load('roles');

        echo "✅ تم التحديث بنجاح!\n";
        echo "الحالة الجديدة:\n";
        echo "- role_id: {$worker->role_id}\n";
        echo "- getCurrentRoleDisplayName(): '{$worker->getCurrentRoleDisplayName()}'\n";
    } else {
        echo "❌ لم يتم العثور على دور 'worker' في قاعدة البيانات\n";
    }
} else {
    echo "❌ لم يتم العثور على المستخدم\n";
}

echo "\n=== فحص جميع المستخدمين بعد الإصلاح ===\n";
$users = User::with(['roles', 'employee'])->get();
foreach ($users as $user) {
    echo "{$user->name}: '{$user->getCurrentRoleDisplayName()}'\n";
}

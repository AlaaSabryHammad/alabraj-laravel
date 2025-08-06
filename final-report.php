<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

echo "===========================================" . PHP_EOL;
echo "       تقرير شامل عن موظفي شركة الأبراج     " . PHP_EOL;
echo "===========================================" . PHP_EOL . PHP_EOL;

echo "📊 الإحصائيات العامة:" . PHP_EOL;
echo "- إجمالي عدد الموظفين: " . User::count() . " موظف" . PHP_EOL . PHP_EOL;

echo "👥 توزيع الأدوار والصلاحيات:" . PHP_EOL;
$roles = User::select('role', DB::raw('count(*) as total'))
    ->groupBy('role')
    ->orderBy('total', 'desc')
    ->get();

$roleNames = [
    'admin' => 'مدير النظام',
    'manager' => 'مدير',
    'supervisor' => 'مشرف',
    'engineer' => 'مهندس',
    'accountant' => 'محاسب',
    'finance' => 'مالية',
    'hr' => 'موارد بشرية',
    'employee' => 'موظف',
    'user' => 'مستخدم'
];

foreach($roles as $role) {
    $roleName = $roleNames[$role->role] ?? $role->role;
    $percentage = round(($role->total / User::count()) * 100, 1);
    echo "- {$roleName}: {$role->total} موظف ({$percentage}%)" . PHP_EOL;
}

echo PHP_EOL . "🏢 توزيع الأقسام:" . PHP_EOL;
$departments = User::select('department', DB::raw('count(*) as total'))
    ->groupBy('department')
    ->orderBy('total', 'desc')
    ->get();

foreach($departments as $dept) {
    $percentage = round(($dept->total / User::count()) * 100, 1);
    echo "- {$dept->department}: {$dept->total} موظف ({$percentage}%)" . PHP_EOL;
}

echo PHP_EOL . "🔐 معلومات تسجيل الدخول:" . PHP_EOL;
echo "- كلمة المرور الافتراضية للموظفين الجدد: password123" . PHP_EOL;
echo "- حسابات إدارية خاصة:" . PHP_EOL;
echo "  * admin@abraj.com (كلمة المرور: admin123)" . PHP_EOL;
echo "  * manager@abraj.com (كلمة المرور: manager123)" . PHP_EOL;
echo "  * finance@abraj.com (كلمة المرور: finance123)" . PHP_EOL;
echo "  * employee@abraj.com (كلمة المرور: employee123)" . PHP_EOL;

echo PHP_EOL . "📝 أمثلة على الموظفين حسب القسم:" . PHP_EOL;
foreach($departments->take(5) as $dept) {
    echo PHP_EOL . "قسم {$dept->department}:" . PHP_EOL;
    $deptUsers = User::where('department', $dept->department)->limit(3)->get();
    foreach($deptUsers as $user) {
        $roleName = $roleNames[$user->role] ?? $user->role;
        echo "  - {$user->name} ({$roleName}) - {$user->email}" . PHP_EOL;
    }
}

echo PHP_EOL . "===========================================" . PHP_EOL;
echo "تم إنشاء قاعدة البيانات بنجاح مع 200 موظف!" . PHP_EOL;
echo "===========================================" . PHP_EOL;

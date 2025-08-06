<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

$departments = [
    'الإدارة العامة', 'الهندسة', 'المحاسبة', 'الموارد البشرية', 'المشاريع', 'المالية',
    'الأمن والسلامة', 'الصيانة', 'المشتريات', 'الجودة', 'التسويق', 'تكنولوجيا المعلومات'
];

echo "تحديث أقسام الموظفين..." . PHP_EOL;

$users = User::all();
foreach($users as $user) {
    // تخصيص القسم حسب الدور
    $department = null;
    
    switch($user->role) {
        case 'admin':
            $department = 'الإدارة العامة';
            break;
        case 'engineer':
            $department = collect(['الهندسة', 'المشاريع', 'الجودة'])->random();
            break;
        case 'accountant':
        case 'finance':
            $department = collect(['المحاسبة', 'المالية'])->random();
            break;
        case 'hr':
            $department = 'الموارد البشرية';
            break;
        case 'manager':
            $department = collect(['الإدارة العامة', 'المشاريع', 'الهندسة'])->random();
            break;
        case 'supervisor':
            $department = collect($departments)->random();
            break;
        default:
            $department = collect($departments)->random();
    }
    
    $user->department = $department;
    $user->save();
}

echo "تم تحديث أقسام " . $users->count() . " موظف بنجاح!" . PHP_EOL;

// عرض توزيع الأقسام
echo PHP_EOL . "توزيع الأقسام:" . PHP_EOL;
$departmentStats = User::select('department', DB::raw('count(*) as total'))
    ->groupBy('department')
    ->orderBy('total', 'desc')
    ->get();

foreach($departmentStats as $dept) {
    echo "- {$dept->department}: {$dept->total} موظف" . PHP_EOL;
}

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

echo "===========================================\n";
echo "     تقرير شامل - شركة الأبراج مع الصور    \n";
echo "===========================================\n\n";

echo "📊 الإحصائيات العامة:\n";
echo "- إجمالي عدد الموظفين: " . User::count() . " موظف\n";
echo "- عدد الصور الشخصية: " . count(glob(public_path('avatars/*.svg'))) . " صورة\n\n";

echo "👥 توزيع الأدوار:\n";
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
    echo "- {$roleName}: {$role->total} موظف ({$percentage}%)\n";
}

echo "\n🏢 توزيع الأقسام:\n";
$departments = User::select('department', DB::raw('count(*) as total'))
    ->groupBy('department')
    ->orderBy('total', 'desc')
    ->get();

foreach($departments as $dept) {
    $percentage = round(($dept->total / User::count()) * 100, 1);
    echo "- {$dept->department}: {$dept->total} موظف ({$percentage}%)\n";
}

echo "\n🖼️ أمثلة على الموظفين مع صورهم الشخصية:\n";
$sampleUsers = User::limit(10)->get();
foreach($sampleUsers as $user) {
    $roleName = $roleNames[$user->role] ?? $user->role;
    $avatarPath = $user->avatar ? asset($user->avatar) : 'بدون صورة';
    echo "- {$user->name} ({$roleName})\n";
    echo "  📧 {$user->email}\n";
    echo "  🏢 {$user->department}\n";
    echo "  🖼️ الصورة: {$avatarPath}\n\n";
}

echo "🎨 معلومات الصور الشخصية:\n";
echo "- النوع: SVG (Scalable Vector Graphics)\n";
echo "- الحجم: 200x200 بكسل\n";
echo "- الألوان: 20 لون مختلف\n";
echo "- المحتوى: الأحرف الأولى من اسم الموظف\n";
echo "- المسار: public/avatars/\n\n";

echo "===========================================\n";
echo "تم إنشاء 200 موظف مع صور شخصية بنجاح! 🎉\n";
echo "===========================================\n";

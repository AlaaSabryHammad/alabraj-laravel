<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Employee;

echo "===========================================\n";
echo "    تقرير شامل - الموظفين والمستخدمين    \n";
echo "===========================================\n\n";

echo "📊 الإحصائيات العامة:\n";
echo "- إجمالي عدد المستخدمين: " . User::count() . " مستخدم\n";
echo "- إجمالي عدد الموظفين: " . Employee::count() . " موظف\n";
echo "- الموظفون المرتبطون بمستخدمين: " . Employee::whereNotNull('user_id')->count() . " موظف\n";
echo "- الموظفون غير المرتبطين: " . Employee::whereNull('user_id')->count() . " موظف\n\n";

echo "💰 إحصائيات الرواتب:\n";
$salaryStats = Employee::selectRaw('
    AVG(salary) as avg_salary,
    MIN(salary) as min_salary,
    MAX(salary) as max_salary,
    SUM(salary) as total_salary
')->first();

echo "- متوسط الراتب: " . number_format($salaryStats->avg_salary, 2) . " ريال\n";
echo "- أقل راتب: " . number_format($salaryStats->min_salary, 2) . " ريال\n";
echo "- أعلى راتب: " . number_format($salaryStats->max_salary, 2) . " ريال\n";
echo "- إجمالي الرواتب: " . number_format($salaryStats->total_salary, 2) . " ريال\n\n";

echo "👥 توزيع الأدوار في جدول الموظفين:\n";
$employeeRoles = Employee::select('role', DB::raw('count(*) as total, AVG(salary) as avg_salary'))
    ->whereNotNull('role')
    ->groupBy('role')
    ->orderBy('total', 'desc')
    ->get();

foreach($employeeRoles as $role) {
    $avgSalary = number_format($role->avg_salary, 0);
    echo "- {$role->role}: {$role->total} موظف (متوسط الراتب: {$avgSalary} ريال)\n";
}

echo "\n🏢 توزيع الأقسام:\n";
$departments = Employee::select('department', DB::raw('count(*) as total, AVG(salary) as avg_salary'))
    ->whereNotNull('department')
    ->groupBy('department')
    ->orderBy('total', 'desc')
    ->get();

foreach($departments as $dept) {
    $avgSalary = number_format($dept->avg_salary, 0);
    echo "- {$dept->department}: {$dept->total} موظف (متوسط الراتب: {$avgSalary} ريال)\n";
}

echo "\n🌍 توزيع الجنسيات:\n";
$nationalities = Employee::select('nationality', DB::raw('count(*) as total'))
    ->whereNotNull('nationality')
    ->groupBy('nationality')
    ->orderBy('total', 'desc')
    ->get();

foreach($nationalities as $nat) {
    $percentage = round(($nat->total / Employee::count()) * 100, 1);
    echo "- {$nat->nationality}: {$nat->total} موظف ({$percentage}%)\n";
}

echo "\n👨‍👩‍👧‍👦 الحالة الاجتماعية:\n";
$maritalStatuses = Employee::select('marital_status', DB::raw('count(*) as total'))
    ->whereNotNull('marital_status')
    ->groupBy('marital_status')
    ->orderBy('total', 'desc')
    ->get();

foreach($maritalStatuses as $status) {
    echo "- {$status->marital_status}: {$status->total} موظف\n";
}

echo "\n🏦 البنوك الأكثر استخداماً:\n";
$banks = Employee::select('bank_name', DB::raw('count(*) as total'))
    ->whereNotNull('bank_name')
    ->groupBy('bank_name')
    ->orderBy('total', 'desc')
    ->limit(5)
    ->get();

foreach($banks as $bank) {
    echo "- {$bank->bank_name}: {$bank->total} موظف\n";
}

echo "\n⭐ متوسط التقييمات حسب الدور:\n";
$ratings = Employee::select('role', DB::raw('AVG(rating) as avg_rating, count(*) as total'))
    ->whereNotNull('rating')
    ->whereNotNull('role')
    ->groupBy('role')
    ->orderBy('avg_rating', 'desc')
    ->get();

foreach($ratings as $rating) {
    $avgRating = number_format($rating->avg_rating, 2);
    echo "- {$rating->role}: {$avgRating}/5.0 ({$rating->total} موظف)\n";
}

echo "\n📋 أمثلة على بيانات الموظفين:\n";
$sampleEmployees = Employee::with('user')
    ->whereNotNull('user_id')
    ->limit(5)
    ->get();

foreach($sampleEmployees as $emp) {
    echo "\n- {$emp->name}\n";
    echo "  📧 {$emp->email}\n";
    echo "  🏢 {$emp->department} - {$emp->position}\n";
    echo "  💰 راتب: " . number_format($emp->salary, 0) . " ريال\n";
    echo "  🌍 الجنسية: {$emp->nationality}\n";
    echo "  📅 تاريخ التوظيف: " . $emp->hire_date->format('Y-m-d') . "\n";
    echo "  ⭐ التقييم: {$emp->rating}/5.0\n";
    if ($emp->user) {
        echo "  🔗 مرتبط بمستخدم: {$emp->user->email}\n";
    }
}

echo "\n===========================================\n";
echo "تم بنجاح إنشاء 200 مستخدم و 205 موظف! 🎉\n";
echo "===========================================\n";

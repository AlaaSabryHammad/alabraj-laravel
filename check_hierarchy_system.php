<?php

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

// بوتستراب اللارافل
$kernel->bootstrap();

use App\Models\Employee;

echo "🔍 فحص الأدوار في النظام\n";
echo "=======================\n\n";

// إحصائيات الأدوار
$roleStats = Employee::selectRaw('role, COUNT(*) as count')
    ->whereNotNull('role')
    ->groupBy('role')
    ->orderBy('count', 'desc')
    ->get();

echo "📊 الأدوار الموجودة:\n";
foreach ($roleStats as $stat) {
    echo "- {$stat->role}: {$stat->count} موظف\n";
}

echo "\n";

// البحث عن أول موظف لعرض مثال
$firstEmployee = Employee::first();
if ($firstEmployee) {
    echo "📝 مثال على موظف (ID: {$firstEmployee->id}):\n";
    echo "   الاسم: {$firstEmployee->name}\n";
    echo "   الدور: {$firstEmployee->role}\n";

    // فحص التسلسل الهرمي
    $requiredRole = Employee::MANAGER_CHAIN[$firstEmployee->role] ?? null;
    if ($requiredRole) {
        echo "   الدور المطلوب للمدير: {$requiredRole}\n";

        $availableManagers = Employee::where('role', $requiredRole)
            ->where('id', '!=', $firstEmployee->id)
            ->count();
        echo "   عدد المديرين المتاحين: {$availableManagers}\n";
    } else {
        echo "   هذا الدور في أعلى التسلسل\n";
    }

    echo "\n   رابط الموظف: http://127.0.0.1:8000/employees/{$firstEmployee->id}\n";
}

echo "\n";
echo "🔗 التسلسل الهرمي المطلوب:\n";
echo "========================\n";

foreach (Employee::MANAGER_CHAIN as $role => $managerRole) {
    $employeeCount = Employee::where('role', $role)->count();
    $managerCount = Employee::where('role', $managerRole)->count();

    $status = $employeeCount > 0 && $managerCount > 0 ? "✅" : "⚠️";
    echo "{$status} {$role} ({$employeeCount}) ← {$managerRole} ({$managerCount})\n";
}

echo "\n";
echo "💡 للاختبار:\n";
echo "============\n";

// البحث عن موظف يمكن اختباره
$testableEmployee = null;
foreach (Employee::MANAGER_CHAIN as $role => $managerRole) {
    $employee = Employee::where('role', $role)->first();
    $managerExists = Employee::where('role', $managerRole)->exists();

    if ($employee && $managerExists) {
        $testableEmployee = $employee;
        break;
    }
}

if ($testableEmployee) {
    echo "✅ يمكنك اختبار النظام بزيارة:\n";
    echo "   http://127.0.0.1:8000/employees/{$testableEmployee->id}\n";
    echo "   الموظف: {$testableEmployee->name} (دور: {$testableEmployee->role})\n";
    echo "   سيرى في القائمة: موظفين بدور " . Employee::MANAGER_CHAIN[$testableEmployee->role] . "\n";
} else {
    echo "⚠️  لا يوجد موظفين يمكن اختبارهم حالياً\n";
    echo "   يرجى إضافة موظفين بأدوار مختلفة\n";
}

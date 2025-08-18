<?php

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

// بوتستراب اللارافل
$kernel->bootstrap();

use App\Models\Employee;

echo "🔍 فحص نظام تعيين المدير المباشر\n";
echo "=====================================\n\n";

// البحث عن موظف مهندس
$engineer = Employee::where('role', 'مهندس')->first();
if (!$engineer) {
    echo "❌ لا يوجد موظف بدور 'مهندس' في النظام\n";
    exit;
}

echo "✅ تم العثور على مهندس: {$engineer->name}\n";
echo "   الدور: {$engineer->role}\n";
echo "   الموقع: " . ($engineer->location ? $engineer->location->name : 'غير محدد') . "\n\n";

// فحص الدور المطلوب للمدير المباشر
$requiredRole = Employee::MANAGER_CHAIN[$engineer->role] ?? null;
echo "📋 الدور المطلوب للمدير المباشر: {$requiredRole}\n\n";

// البحث عن موظفين بدور "مدير مشاريع"
$projectManagers = Employee::where('role', $requiredRole)
    ->where('id', '!=', $engineer->id)
    ->orderBy('name')
    ->get();

echo "👥 الموظفون المتاحون كمديرين مباشرين للمهندس:\n";
echo "================================================\n";

if ($projectManagers->count() > 0) {
    foreach ($projectManagers as $manager) {
        echo "- {$manager->name}\n";
        echo "  المسمى الوظيفي: {$manager->position}\n";
        echo "  القسم: {$manager->department}\n";
        echo "  الموقع: " . ($manager->location ? $manager->location->name : 'غير محدد') . "\n";
        echo "  البريد الإلكتروني: {$manager->email}\n";
        echo "  الحالة: {$manager->status}\n\n";
    }
    echo "✅ العدد الإجمالي للمديرين المتاحين: {$projectManagers->count()}\n";
} else {
    echo "❌ لا يوجد موظفين بدور 'مدير مشاريع' في النظام\n";
    echo "💡 لحل هذه المشكلة، يجب:\n";
    echo "   1. إضافة موظفين جدد بدور 'مدير مشاريع'\n";
    echo "   2. أو تغيير دور موظف موجود ليصبح 'مدير مشاريع'\n";
}

echo "\n";
echo "📊 إحصائيات الأدوار في النظام:\n";
echo "===============================\n";

$roleStats = Employee::selectRaw('role, COUNT(*) as count')
    ->whereNotNull('role')
    ->groupBy('role')
    ->orderBy('count', 'desc')
    ->get();

foreach ($roleStats as $stat) {
    echo "- {$stat->role}: {$stat->count} موظف\n";
}

echo "\n";
echo "🔗 التسلسل الهرمي المطلوب:\n";
echo "========================\n";

foreach (Employee::MANAGER_CHAIN as $role => $managerRole) {
    $employeeCount = Employee::where('role', $role)->count();
    $managerCount = Employee::where('role', $managerRole)->count();

    echo "- {$role} ({$employeeCount} موظف) ← يدير من قِبل → {$managerRole} ({$managerCount} مدير)\n";
}

echo "\n";
echo "✨ النتيجة:\n";
echo "==========\n";
if ($projectManagers->count() > 0) {
    echo "✅ النظام يعمل بشكل صحيح! المهندس سيرى {$projectManagers->count()} مدير مشاريع في القائمة\n";
} else {
    echo "⚠️  يحتاج النظام إلى إضافة موظفين بدور 'مدير مشاريع'\n";
}

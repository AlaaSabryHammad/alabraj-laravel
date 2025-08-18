<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🧪 اختبار ظهور زر تعيين المدير المباشر\n";
echo "======================================\n\n";

// اختبار أدوار مختلفة
$testRoles = [
    'general_manager' => 'مدير عام',
    'project_manager' => 'مدير مشاريع',
    'engineer' => 'مهندس',
    'driver' => 'سائق',
    'worker' => 'عامل'
];

foreach ($testRoles as $roleKey => $roleDisplay) {
    echo "🔍 اختبار الدور: {$roleDisplay} ({$roleKey})\n";
    echo str_repeat('-', 40) . "\n";

    $requiredManagerRole = \App\Models\Employee::MANAGER_CHAIN[$roleKey] ?? null;

    if ($requiredManagerRole) {
        echo "✅ الزر سيظهر\n";
        echo "🎯 المدير المطلوب: {$requiredManagerRole}\n";

        // التحقق من وجود مديرين بهذا الدور
        $availableManagers = \DB::table('employees')
            ->where('role', $requiredManagerRole)
            ->count();

        echo "👥 عدد المديرين المتاحين: {$availableManagers}\n";
    } else {
        echo "❌ الزر لن يظهر\n";
        echo "💡 السبب: هذا الدور في أعلى التسلسل\n";
    }

    echo "\n";
}

echo "📋 ملخص منطق العرض:\n";
echo "=====================\n";
echo "✅ الزر يظهر: للأدوار التي لها مدير مباشر مطلوب\n";
echo "❌ الزر لا يظهر: للأدوار في أعلى التسلسل (مثل المدير العام)\n";
echo "🎯 هذا يضمن منطق عمل صحيح وتسلسل هرمي واضح\n";

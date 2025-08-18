<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

use App\Models\Employee;

echo "🔍 فحص المهندسين ومديري المشاريع\n";
echo "============================\n\n";

// عرض المهندسين
$engineers = Employee::where('role', 'engineer')->get();
echo "👨‍🔧 المهندسين المتاحين:\n";
foreach ($engineers as $eng) {
    echo "   ID: {$eng->id} - {$eng->full_name}\n";
}
echo "\n";

// عرض مديري المشاريع
$project_managers = Employee::where('role', 'project_manager')->get();
echo "👔 مديري المشاريع المتاحين:\n";
foreach ($project_managers as $pm) {
    echo "   ID: {$pm->id} - {$pm->full_name}\n";
}
echo "\n";

// اختبار التسلسل الهرمي
if ($engineers->count() > 0) {
    $testEngineer = $engineers->first();
    echo "🔧 اختبار للمهندس: {$testEngineer->full_name} (ID: {$testEngineer->id})\n";
    echo "   رابط صفحة الموظف: http://127.0.0.1:8000/employees/{$testEngineer->id}\n";

    $availableManagers = $testEngineer->getAvailableDirectManagers();
    echo "   المديرين المباشرين المتاحين:\n";
    foreach ($availableManagers as $manager) {
        echo "      - {$manager->full_name} (دور: {$manager->role})\n";
    }
}

echo "\n✅ الاختبار مكتمل!\n";

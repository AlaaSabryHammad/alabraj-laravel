<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

use Illuminate\Support\Facades\DB;

echo "🔍 التحقق من المهندسين ومديري المشاريع\n";
echo "=====================================\n\n";

// البحث عن المهندسين
$engineers = DB::table('employees')
    ->where('role', 'engineer')
    ->select('id', 'name', 'role')
    ->get();

echo "👨‍🔧 المهندسين الموجودين (" . count($engineers) . "):\n";
foreach ($engineers as $engineer) {
    echo "   ID: {$engineer->id} - {$engineer->name}\n";
}

echo "\n";

// البحث عن مديري المشاريع
$projectManagers = DB::table('employees')
    ->where('role', 'project_manager')
    ->select('id', 'name', 'role')
    ->get();

echo "👔 مديري المشاريع الموجودين (" . count($projectManagers) . "):\n";
foreach ($projectManagers as $pm) {
    echo "   ID: {$pm->id} - {$pm->name}\n";
}

echo "\n✅ النتيجة:\n";
echo "عندما يدخل أي مهندس لصفحة الموظف، سيرى في قائمة \"تعيين مدير مباشر\":\n";
foreach ($projectManagers as $pm) {
    echo "   - {$pm->name} (دور: project_manager)\n";
}

if (count($engineers) > 0) {
    $testEngineer = $engineers[0];
    echo "\n🔗 للاختبار، قم بزيارة:\n";
    echo "   http://127.0.0.1:8000/employees/{$testEngineer->id}\n";
    echo "   للموظف: {$testEngineer->name}\n";
}

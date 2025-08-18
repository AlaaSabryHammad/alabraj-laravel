<?php

use Illuminate\Support\Facades\DB;

// استعلام مباشر للتحقق من الموظفين
echo "🔍 التحقق من المهندسين ومديري المشاريع\n";
echo "=====================================\n\n";

try {
    // البحث عن المهندسين
    $engineers = DB::table('employees')
        ->where('role', 'engineer')
        ->select('id', 'name', 'role')
        ->get();

    echo "👨‍🔧 المهندسين الموجودين:\n";
    foreach ($engineers as $engineer) {
        echo "   ID: {$engineer->id} - {$engineer->name}\n";
        echo "   رابط: http://127.0.0.1:8000/employees/{$engineer->id}\n\n";
    }

    // البحث عن مديري المشاريع
    $projectManagers = DB::table('employees')
        ->where('role', 'project_manager')
        ->select('id', 'name', 'role')
        ->get();

    echo "👔 مديري المشاريع الموجودين:\n";
    foreach ($projectManagers as $pm) {
        echo "   ID: {$pm->id} - {$pm->name}\n";
    }

    echo "\n✅ النتيجة: عندما يدخل المهندس لصفحته، سيرى في قائمة المدير المباشر:\n";
    foreach ($projectManagers as $pm) {
        echo "   - {$pm->name} (مدير مشاريع)\n";
    }
} catch (Exception $e) {
    echo "❌ خطأ: " . $e->getMessage() . "\n";
}

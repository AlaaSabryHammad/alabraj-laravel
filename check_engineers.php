<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Employee;

echo "التحقق من الموظفين بدور مهندس:\n";
echo "================================\n\n";

// Get engineer variants
$engineerVariants = Employee::variantsForArabic('مهندس');
echo "البحث عن الأدوار التالية: " . implode(', ', $engineerVariants) . "\n\n";

// Get all active engineers
$engineers = Employee::where('status', 'active')
    ->whereIn('role', $engineerVariants)
    ->get();

echo "عدد المهندسين النشطين: " . $engineers->count() . "\n\n";

if ($engineers->count() > 0) {
    echo "قائمة المهندسين:\n";
    echo "---------------\n";
    foreach ($engineers as $engineer) {
        echo "- {$engineer->name} (الدور: {$engineer->role})\n";
    }
} else {
    echo "لا يوجد مهندسين نشطين في النظام.\n";

    // Check all employees with any engineer-related role
    echo "\nالبحث في جميع الموظفين عن كلمة 'مهندس' أو 'engineer':\n";
    $allEngineers = Employee::where(function ($query) {
        $query->where('role', 'like', '%مهندس%')
            ->orWhere('role', 'like', '%engineer%');
    })->get();

    foreach ($allEngineers as $engineer) {
        echo "- {$engineer->name} (الدور: {$engineer->role}, الحالة: {$engineer->status})\n";
    }
}

echo "\n";

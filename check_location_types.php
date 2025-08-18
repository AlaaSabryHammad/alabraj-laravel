<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\LocationType;
use App\Models\Employee;

echo "أنواع المواقع المتاحة:\n";
echo "=====================\n\n";

$locationTypes = LocationType::where('is_active', true)->get();
foreach ($locationTypes as $type) {
    echo "- {$type->name} (ID: {$type->id})\n";
    if ($type->description) {
        echo "  الوصف: {$type->description}\n";
    }
}

echo "\n\nمشرفي المواقع:\n";
echo "==============\n";

$siteManagerVariants = Employee::variantsForArabic('مشرف موقع');
echo "البحث عن الأدوار: " . implode(', ', $siteManagerVariants) . "\n\n";

$siteManagers = Employee::where('status', 'active')
    ->whereIn('role', $siteManagerVariants)
    ->get();

foreach ($siteManagers as $manager) {
    echo "- {$manager->name} (الدور: {$manager->role})\n";
}

echo "\n";

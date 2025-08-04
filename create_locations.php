<?php

use App\Models\Location;

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// إنشاء مواقع تجريبية
$locations = [
    ['name' => 'المكتب الرئيسي', 'city' => 'الرياض', 'type' => 'office', 'status' => 'active'],
    ['name' => 'فرع جدة', 'city' => 'جدة', 'type' => 'branch', 'status' => 'active'],
    ['name' => 'مصنع الدمام', 'city' => 'الدمام', 'type' => 'factory', 'status' => 'active'],
    ['name' => 'مستودع مكة', 'city' => 'مكة المكرمة', 'type' => 'warehouse', 'status' => 'active']
];

foreach ($locations as $location) {
    Location::firstOrCreate(['name' => $location['name']], $location);
}

echo 'تم إنشاء المواقع بنجاح!' . PHP_EOL;
echo 'عدد المواقع: ' . Location::count() . PHP_EOL;

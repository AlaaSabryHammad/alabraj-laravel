<?php
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// إحصائيات المواقع
$totalLocations = DB::table('locations')->count();
$activeLocations = DB::table('locations')->where('status', 'active')->count();
$inactiveLocations = DB::table('locations')->where('status', 'inactive')->count();
$maintenanceLocations = DB::table('locations')->where('status', 'maintenance')->count();

echo "إحصائيات المواقع:\n";
echo "=====================\n";
echo "إجمالي المواقع: $totalLocations موقع\n";
echo "المواقع النشطة: $activeLocations موقع\n";
echo "المواقع غير النشطة: $inactiveLocations موقع\n";
echo "المواقع تحت الصيانة: $maintenanceLocations موقع\n";

// أحدث 10 مواقع
echo "\nأحدث 10 مواقع تم إنشاؤها:\n";
echo "============================\n";
$recentLocations = DB::table('locations')
    ->orderBy('created_at', 'desc')
    ->limit(10)
    ->get(['name', 'city', 'status']);

foreach($recentLocations as $location) {
    echo "- {$location->name} ({$location->city}) - حالة: {$location->status}\n";
}

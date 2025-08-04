<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== فحص تفصيلي لبيانات التحركات ===" . PHP_EOL . PHP_EOL;

// Get all movement history records for equipment 3
$history = \App\Models\EquipmentMovementHistory::where('equipment_id', 3)
    ->with(['fromLocation', 'toLocation'])
    ->orderBy('moved_at', 'desc')
    ->get();

foreach ($history as $item) {
    echo "=== السجل رقم {$item->id} ===" . PHP_EOL;
    echo "من (ID): " . ($item->from_location_id ?? 'null') . PHP_EOL;
    echo "من (نص): " . ($item->from_location_text ?? 'null') . PHP_EOL;
    echo "من (علاقة): " . ($item->fromLocation ? $item->fromLocation->name : 'null') . PHP_EOL;
    echo "إلى (ID): " . ($item->to_location_id ?? 'null') . PHP_EOL;
    echo "إلى (نص): " . ($item->to_location_text ?? 'null') . PHP_EOL;
    echo "إلى (علاقة): " . ($item->toLocation ? $item->toLocation->name : 'null') . PHP_EOL;

    // Test the accessor
    echo "من (getFromLocationNameAttribute): " . $item->from_location_name . PHP_EOL;
    echo "إلى (getToLocationNameAttribute): " . $item->to_location_name . PHP_EOL;
    echo "---" . PHP_EOL;
}

echo PHP_EOL . "=== جميع المواقع المتاحة ===" . PHP_EOL;
$locations = \App\Models\Location::all();
foreach ($locations as $location) {
    echo "ID: {$location->id} - الاسم: {$location->name}" . PHP_EOL;
}

echo PHP_EOL . "=== انتهى الفحص ===" . PHP_EOL;

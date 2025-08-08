<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// التحقق من وجود نوع المستودع
$warehouseType = App\Models\LocationType::where('name', 'مستودع')->first();

if ($warehouseType) {
    // تحديث 5 مواقع لتصبح مستودعات
    $locations = App\Models\Location::limit(5)->get();
    
    foreach ($locations as $location) {
        $location->update([
            'location_type_id' => $warehouseType->id,
            'status' => 'نشط'
        ]);
        echo "Updated location: {$location->name} to warehouse\n";
    }
    
    echo "Updated {$locations->count()} locations to be warehouses.\n";
    echo "Warehouse type ID: {$warehouseType->id}\n";
} else {
    echo "Warehouse type not found!\n";
}

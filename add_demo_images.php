<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$truck = \App\Models\InternalTruck::find(2);
if ($truck) {
    $truck->images = [
        'internal-trucks/demo-truck-1.jpg',
        'internal-trucks/demo-truck-2.jpg',
        'internal-trucks/demo-truck-3.jpg'
    ];
    $truck->save();
    echo 'تم إضافة الصور التجريبية للشاحنة رقم ' . $truck->id . "\n";
    echo 'الصور المضافة: ' . count($truck->images) . " صورة\n";
} else {
    echo 'لم يتم العثور على الشاحنة رقم 2' . "\n";
}

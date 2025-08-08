<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== تحليل البيانات الحالية ===\n\n";

// فحص أنواع المعدات
echo "1. أنواع المعدات في النظام:\n";
$categories = \App\Models\Equipment::select('category')->distinct()->whereNotNull('category')->pluck('category');
foreach ($categories as $cat) {
    $count = \App\Models\Equipment::where('category', $cat)->count();
    echo "   - {$cat}: {$count} معدة\n";
}

echo "\n2. الشاحنات الداخلية:\n";
$internalTrucks = \App\Models\InternalTruck::all();
echo "   عدد الشاحنات الداخلية: " . $internalTrucks->count() . "\n";

echo "\n3. معدات مرتبطة بالشاحنات:\n";
$truckEquipments = \App\Models\Equipment::whereNotNull('truck_id')->get();
foreach ($truckEquipments as $eq) {
    echo "   - المعدة: {$eq->name} (مرتبطة بشاحنة ID: {$eq->truck_id})\n";
}

echo "\n4. معدات من نوع 'شاحنات' غير مرتبطة:\n";
$unlinkedTruckEquipments = \App\Models\Equipment::where('category', 'شاحنات')->whereNull('truck_id')->get();
foreach ($unlinkedTruckEquipments as $eq) {
    echo "   - المعدة: {$eq->name} (ID: {$eq->id})\n";
}

echo "\nتحليل مكتمل.\n";

<?php
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// إحصائيات المعدات
$totalEquipment = DB::table('equipment')->count();
$availableEquipment = DB::table('equipment')->where('status', 'available')->count();
$inUseEquipment = DB::table('equipment')->where('status', 'in_use')->count();
$maintenanceEquipment = DB::table('equipment')->where('status', 'maintenance')->count();
$outOfOrderEquipment = DB::table('equipment')->where('status', 'out_of_order')->count();

echo "إحصائيات المعدات:\n";
echo "=====================\n";
echo "إجمالي المعدات: $totalEquipment معدة\n";
echo "المعدات المتاحة: $availableEquipment معدة\n";
echo "المعدات قيد الاستخدام: $inUseEquipment معدة\n";
echo "المعدات تحت الصيانة: $maintenanceEquipment معدة\n";
echo "المعدات المعطلة: $outOfOrderEquipment معدة\n";

// توزيع المعدات حسب النوع
echo "\nتوزيع المعدات حسب النوع:\n";
echo "============================\n";
$equipmentByType = DB::table('equipment')
    ->select('type', DB::raw('COUNT(*) as count'))
    ->groupBy('type')
    ->orderBy('count', 'desc')
    ->get();

foreach($equipmentByType as $type) {
    echo "- {$type->type}: {$type->count} معدة\n";
}

// المعدات الأحدث
echo "\nأحدث 10 معدات تم إضافتها:\n";
echo "===============================\n";
$recentEquipment = DB::table('equipment')
    ->join('locations', 'equipment.location_id', '=', 'locations.id')
    ->select('equipment.name', 'equipment.type', 'equipment.status', 'locations.name as location_name')
    ->orderBy('equipment.created_at', 'desc')
    ->limit(10)
    ->get();

foreach($recentEquipment as $equipment) {
    echo "- {$equipment->name} ({$equipment->type}) - {$equipment->status} - {$equipment->location_name}\n";
}

// المعدات حسب القيمة
echo "\nأغلى 5 معدات:\n";
echo "==================\n";
$expensiveEquipment = DB::table('equipment')
    ->select('name', 'type', 'purchase_price')
    ->orderBy('purchase_price', 'desc')
    ->limit(5)
    ->get();

foreach($expensiveEquipment as $equipment) {
    $price = number_format($equipment->purchase_price, 2);
    echo "- {$equipment->name} ({$equipment->type}) - {$price} ريال\n";
}

<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== اختبار إنشاء معدة شاحنة جديدة ===\n\n";

// إنشاء معدة جديدة من نوع شاحنات
$equipment = \App\Models\Equipment::create([
    'name' => 'فولفو FH16 - ABC123',
    'category' => 'شاحنات',
    'description' => 'شاحنة اختبار',
    'serial_number' => 'TEST-' . time(),
    'purchase_date' => now(),
    'purchase_price' => 150000,
    'status' => 'available',
    'user_id' => 1,
]);

echo "تم إنشاء المعدة: {$equipment->name} (ID: {$equipment->id})\n";

// فحص إن تم إنشاء شاحنة داخلية
$internalTruck = \App\Models\InternalTruck::where('plate_number', 'ABC123')->first();
if ($internalTruck) {
    echo "✅ تم إنشاء شاحنة داخلية: {$internalTruck->brand} {$internalTruck->model} - {$internalTruck->plate_number}\n";
    echo "   ID الشاحنة: {$internalTruck->id}\n";

    // فحص ربط المعدة
    $equipment->refresh();
    if ($equipment->truck_id == $internalTruck->id) {
        echo "✅ المعدة مرتبطة بالشاحنة بنجاح\n";
    } else {
        echo "❌ المعدة غير مرتبطة بالشاحنة\n";
    }
} else {
    echo "❌ لم يتم إنشاء شاحنة داخلية\n";
}

echo "\nاختبار مكتمل.\n";

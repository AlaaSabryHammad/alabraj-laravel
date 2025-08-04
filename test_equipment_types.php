<?php

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "أنواع المعدات الموجودة:\n";
$types = \App\Models\EquipmentType::all();
foreach ($types as $type) {
    echo "ID: {$type->id} - الاسم: {$type->name}\n";
}

if ($types->count() > 0) {
    $firstType = $types->first();
    echo "\nاختبار إنشاء معدة مع type_id = {$firstType->id}:\n";

    try {
        $equipment = \App\Models\Equipment::create([
            'name' => 'تست معدة ' . time(),
            'type_id' => $firstType->id,
            'serial_number' => 'TEST-' . time(),
            'purchase_date' => '2024-01-01',
            'purchase_price' => 1000,
            'status' => 'available'
        ]);

        echo "✅ تم إنشاء المعدة بنجاح!\n";
        echo "ID: " . $equipment->id . "\n";
        echo "الاسم: " . $equipment->name . "\n";
        echo "type_id: " . $equipment->type_id . "\n";
        echo "type: " . ($equipment->type ?? 'null') . "\n";

    } catch (Exception $e) {
        echo "❌ خطأ: " . $e->getMessage() . "\n";
    }
} else {
    echo "لا توجد أنواع معدات في قاعدة البيانات\n";
}

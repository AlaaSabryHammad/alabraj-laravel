<?php

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "اختبار إنشاء معدة مع boot method:\n";

try {
    $equipment = \App\Models\Equipment::create([
        'name' => 'تست معدة boot ' . time(),
        'type_id' => 2, // بلدوزر
        'serial_number' => 'BOOT-' . time(),
        'purchase_date' => '2024-01-01',
        'purchase_price' => 1000,
        'status' => 'available'
    ]);

    echo "✅ تم إنشاء المعدة بنجاح!\n";
    echo "ID: " . $equipment->id . "\n";
    echo "الاسم: " . $equipment->name . "\n";
    echo "type_id: " . $equipment->type_id . "\n";
    echo "type: " . ($equipment->type ?? 'null') . "\n";
    echo "equipmentType->name: " . ($equipment->equipmentType->name ?? 'null') . "\n";

} catch (Exception $e) {
    echo "❌ خطأ: " . $e->getMessage() . "\n";
}

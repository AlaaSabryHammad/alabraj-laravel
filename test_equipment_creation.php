<?php

// اختبار إنشاء معدة جديدة
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    $equipment = \App\Models\Equipment::create([
        'name' => 'تست معدة ' . time(),
        'type_id' => 1,
        'serial_number' => 'TEST-' . time(),
        'purchase_date' => '2024-01-01',
        'purchase_price' => 1000,
        'status' => 'available'
    ]);

    echo "تم إنشاء المعدة بنجاح!\n";
    echo "ID: " . $equipment->id . "\n";
    echo "الاسم: " . $equipment->name . "\n";
    echo "type_id: " . $equipment->type_id . "\n";
    echo "type: " . ($equipment->type ?? 'null') . "\n";

} catch (Exception $e) {
    echo "خطأ: " . $e->getMessage() . "\n";
}

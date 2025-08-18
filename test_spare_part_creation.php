<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

echo "=== اختبار حفظ قطعة غيار جديدة ===\n\n";

try {
    // البحث عن المستودع
    $warehouse = App\Models\Location::find(41);
    if (!$warehouse) {
        echo "المستودع غير موجود!\n";
        exit;
    }

    echo "المستودع: {$warehouse->name}\n";

    // إنشاء بيانات تجريبية
    $testData = [
        'code' => 'TEST-' . time(),
        'name' => 'قطعة اختبار - ' . date('Y-m-d H:i:s'),
        'description' => 'قطعة اختبار للتحقق من النظام',
        'category' => 'اختبار',
        'brand' => 'TEST',
        'model' => 'MODEL-1',
        'unit_price' => 100.00,
        'unit_type' => 'قطعة',
        'minimum_stock' => 5,
        'supplier' => 'مورد تجريبي',
        'location_shelf' => 'الرف A1',
        'source' => 'new'
    ];

    echo "محاولة إنشاء قطعة غيار...\n";
    $sparePart = App\Models\SparePart::create($testData);
    echo "تم إنشاء قطعة الغيار بنجاح! ID: {$sparePart->id}\n";

    // إنشاء مخزون
    echo "محاولة إنشاء سجل المخزون...\n";
    $inventory = App\Models\WarehouseInventory::create([
        'spare_part_id' => $sparePart->id,
        'location_id' => $warehouse->id,
        'current_stock' => 10,
        'available_stock' => 10,
        'average_cost' => 100.00,
        'total_value' => 1000.00,
        'last_transaction_date' => now(),
        'location_shelf' => 'الرف A1',
    ]);
    echo "تم إنشاء سجل المخزون بنجاح! ID: {$inventory->id}\n";

    // إنشاء معاملة
    echo "محاولة إنشاء المعاملة...\n";
    $transaction = App\Models\SparePartTransaction::create([
        'spare_part_id' => $sparePart->id,
        'location_id' => $warehouse->id,
        'user_id' => 1, // افتراض وجود مستخدم بـ ID 1
        'transaction_type' => 'استلام',
        'quantity' => 10,
        'unit_price' => 100.00,
        'total_amount' => 1000.00,
        'notes' => 'اختبار النظام',
        'transaction_date' => now(),
    ]);
    echo "تم إنشاء المعاملة بنجاح! ID: {$transaction->id}\n";

    echo "\n=== تم الاختبار بنجاح ===\n";
} catch (Exception $e) {
    echo "حدث خطأ: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

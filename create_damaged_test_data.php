<?php
// إنشاء قطع غيار تالفة تجريبية
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "إنشاء قطع غيار تالفة تجريبية...\n";

// إنشاء قطعة غيار تالفة
$damagedPart = App\Models\SparePart::create([
    'name' => 'محرك هيدروليكي (تالفة)',
    'code' => 'SP-DAMAGED-001',
    'description' => 'قطعة غيار تالفة للاختبار',
    'unit_price' => 0,
    'is_active' => false,
    'source' => 'damaged_replacement',
    'spare_part_type_id' => 1, // افتراض وجود نوع بهذا الرقم
]);

echo "تم إنشاء قطعة الغيار التالفة: " . $damagedPart->name . "\n";

// إنشاء مخزون لهذه القطعة في المستودع 40
$inventory = App\Models\WarehouseInventory::create([
    'spare_part_id' => $damagedPart->id,
    'location_id' => 40, // المستودع المطلوب
    'current_stock' => 5,
    'available_stock' => 0, // غير متاحة للاستخدام
    'total_value' => 0,
    'average_cost' => 0,
    'last_transaction_date' => now(),
]);

echo "تم إنشاء سجل المخزون للقطعة التالفة\n";

// إنشاء أرقام تسلسلية
for ($i = 1; $i <= 5; $i++) {
    App\Models\SparePartSerial::create([
        'spare_part_id' => $damagedPart->id,
        'serial_number' => 'DMG-' . str_pad($i, 3, '0', STR_PAD_LEFT),
        'barcode' => 'DMG-BAR-' . str_pad($i, 3, '0', STR_PAD_LEFT),
        'location_id' => 40,
        'status' => 'damaged',
        'condition_notes' => 'قطعة تالفة للاختبار - تلف هيدروليكي',
    ]);
}

echo "تم إنشاء 5 أرقام تسلسلية للقطعة التالفة\n";

// إنشاء معاملة الاستلام
App\Models\SparePartTransaction::create([
    'location_id' => 40,
    'spare_part_id' => $damagedPart->id,
    'transaction_type' => 'استلام',
    'quantity' => 5,
    'unit_price' => 0,
    'total_amount' => 0,
    'notes' => 'استلام قطع تالفة للاختبار',
    'user_id' => 1, // افتراض وجود مستخدم بهذا الرقم
    'transaction_date' => now(),
]);

echo "تم إنشاء معاملة الاستلام\n";
echo "تم الانتهاء من إنشاء البيانات التجريبية!\n";

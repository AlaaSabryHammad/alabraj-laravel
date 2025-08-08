<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🔍 تقرير السيناريو الكامل لقطع الغيار:\n\n";

// 1. آخر المعاملات
echo "📊 آخر المعاملات:\n";
$transactions = \App\Models\SparePartTransaction::orderBy('created_at', 'desc')->limit(5)->get();
foreach($transactions as $t) {
    $part = \App\Models\SparePart::find($t->spare_part_id);
    echo "- {$t->transaction_type}: {$t->quantity} من {$part->name}\n";
    echo "  📅 التاريخ: {$t->created_at->format('Y-m-d H:i')}\n";
    if($t->notes) echo "  📝 ملاحظات: {$t->notes}\n";
    echo "\n";
}

// 2. حالة المخزون
echo "\n📦 حالة المخزون الحالية:\n";
$inventory = \App\Models\WarehouseInventory::with(['sparePart', 'location'])->get();
foreach($inventory as $inv) {
    echo "- {$inv->sparePart->name}: {$inv->current_stock} قطعة\n";
    echo "  💰 القيمة: {$inv->total_value} ريال\n";
    echo "  📍 المستودع: {$inv->location->name}\n\n";
}

// 3. الأرقام التسلسلية
echo "\n🏷️ الأرقام التسلسلية وحالاتها:\n";
$serials = \App\Models\SparePartSerial::with(['sparePart'])->orderBy('created_at', 'desc')->limit(10)->get();
foreach($serials as $serial) {
    echo "- {$serial->serial_number}: {$serial->sparePart->name}\n";
    echo "  📊 الحالة: {$serial->status}\n";
    if($serial->assigned_to_equipment) {
        $equipment = \App\Models\Equipment::find($serial->assigned_to_equipment);
        echo "  🚜 مُسند للمعدة: {$equipment->name}\n";
    }
    if($serial->condition_notes) echo "  📝 ملاحظات: {$serial->condition_notes}\n";
    echo "\n";
}

echo "✅ تم إنجاز التقرير بنجاح!\n";
?>

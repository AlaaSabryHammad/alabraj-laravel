<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== اختبار بيانات تاريخ التحركات ===" . PHP_EOL . PHP_EOL;

// Check movement history for equipment ID 3
$equipment = \App\Models\Equipment::find(3);
if ($equipment) {
    echo "المعدة: " . $equipment->name . PHP_EOL;
    echo "الموقع الحالي: " . ($equipment->locationDetail ? $equipment->locationDetail->name : ($equipment->location ?? 'غير محدد')) . PHP_EOL;
    echo "---" . PHP_EOL;

    $history = $equipment->movementHistory()
        ->with(['fromLocation', 'toLocation', 'movedBy'])
        ->orderBy('moved_at', 'desc')
        ->limit(5)
        ->get();

    if ($history->count() > 0) {
        echo "تاريخ التحركات:" . PHP_EOL;
        foreach ($history as $item) {
            echo "- من: " . ($item->fromLocation ? $item->fromLocation->name : ($item->from_location_text ?? 'غير محدد')) . PHP_EOL;
            echo "  إلى: " . ($item->toLocation ? $item->toLocation->name : ($item->to_location_text ?? 'غير محدد')) . PHP_EOL;
            echo "  التاريخ: " . $item->moved_at->format('Y-m-d H:i') . PHP_EOL;
            echo "  السبب: " . ($item->movement_reason ?? 'غير محدد') . PHP_EOL;
            echo "  النوع: " . $item->movement_type . PHP_EOL;
            echo "---" . PHP_EOL;
        }
    } else {
        echo "لا يوجد تاريخ تحركات لهذه المعدة" . PHP_EOL;

        // Create a test movement record
        echo "إنشاء سجل تحرك تجريبي..." . PHP_EOL;
        \App\Models\EquipmentMovementHistory::create([
            'equipment_id' => $equipment->id,
            'from_location_id' => null,
            'from_location_text' => 'الموقع القديم (تجريبي)',
            'to_location_id' => $equipment->location_id,
            'to_location_text' => null,
            'moved_by' => 1, // Assuming user ID 1 exists
            'moved_at' => now(),
            'movement_reason' => 'اختبار البيانات',
            'movement_type' => 'location_change',
            'notes' => 'سجل تجريبي للاختبار'
        ]);
        echo "✅ تم إنشاء سجل التحرك التجريبي" . PHP_EOL;
    }
} else {
    echo "لم يتم العثور على المعدة رقم 3" . PHP_EOL;
}

echo PHP_EOL . "=== انتهى الاختبار ===" . PHP_EOL;

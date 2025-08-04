<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== اختبار API تاريخ التحركات ===" . PHP_EOL . PHP_EOL;

// Get equipment
$equipment = \App\Models\Equipment::find(3);
if (!$equipment) {
    echo "لم يتم العثور على المعدة رقم 3" . PHP_EOL;
    exit;
}

// Test the API method directly
$controller = new \App\Http\Controllers\EquipmentHistoryController();
try {
    $response = $controller->getMovementHistory($equipment);
    $data = $response->getData(true); // Get as array

    echo "استجابة الـ API:" . PHP_EOL;
    echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . PHP_EOL;

    // Check specific fields
    if (isset($data['data']) && count($data['data']) > 0) {
        echo PHP_EOL . "=== فحص البيانات المُحوّلة ===" . PHP_EOL;
        foreach ($data['data'] as $index => $item) {
            echo "السجل " . ($index + 1) . ":" . PHP_EOL;
            echo "- من: " . ($item['from_location_name'] ?? 'غير موجود') . PHP_EOL;
            echo "- إلى: " . ($item['to_location_name'] ?? 'غير موجود') . PHP_EOL;
            echo "- التاريخ: " . ($item['moved_at'] ?? 'غير موجود') . PHP_EOL;
            echo "---" . PHP_EOL;
        }
    } else {
        echo "لا توجد بيانات في الاستجابة" . PHP_EOL;
    }

} catch (Exception $e) {
    echo "خطأ: " . $e->getMessage() . PHP_EOL;
    echo "في الملف: " . $e->getFile() . " السطر: " . $e->getLine() . PHP_EOL;
}

echo PHP_EOL . "=== انتهى الاختبار ===" . PHP_EOL;

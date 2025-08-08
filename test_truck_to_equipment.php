<?php
require_once 'vendor/autoload.php';

use App\Models\InternalTruck;
use App\Models\Equipment;

// إعداد Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== اختبار إنشاء شاحنة داخلية جديدة ===\n\n";

try {
    // إنشاء شاحنة داخلية جديدة
    $truck = InternalTruck::create([
        'plate_number' => 'GHI789',
        'brand' => 'مرسيدس',
        'model' => 'أكتروس',
        'year' => 2023,
        'load_capacity' => 10.0,
        'fuel_type' => 'diesel',
        'status' => 'available',
        'purchase_date' => now()->subMonths(3),
        'purchase_price' => 450000,
        'description' => 'شاحنة جديدة للنقل الداخلي',
        'user_id' => 1  // إضافة معرف المستخدم
    ]);

    echo "تم إنشاء الشاحنة: {$truck->brand} {$truck->model} - {$truck->plate_number} (ID: {$truck->id})\n";

    // التحقق من إنشاء المعدة التلقائي
    $equipment = Equipment::where('truck_id', $truck->id)->first();

    if ($equipment) {
        echo "✅ تم إنشاء معدة تلقائياً: {$equipment->name}\n";
        echo "   ID المعدة: {$equipment->id}\n";
        echo "   فئة المعدة: {$equipment->category}\n";
        echo "   الحالة: {$equipment->status}\n";
    } else {
        echo "❌ لم يتم إنشاء معدة تلقائياً\n";
    }

    echo "\nاختبار مكتمل.\n";
} catch (Exception $e) {
    echo "خطأ: " . $e->getMessage() . "\n";
    echo "في الملف: " . $e->getFile() . " السطر: " . $e->getLine() . "\n";
}

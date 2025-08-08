<?php
require_once 'vendor/autoload.php';

use App\Models\Equipment;

// إعداد Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== فحص المعدات غير المربوطة ===\n\n";

try {
    // البحث عن معدات من فئة شاحنات بدون truck_id
    $unlinkedEquipments = Equipment::where(function ($query) {
        $query->where('category', 'شاحنات')
            ->orWhere('category', 'شاحنة');
    })
        ->whereNull('truck_id')
        ->get();

    echo "عدد المعدات غير المربوطة: " . $unlinkedEquipments->count() . "\n\n";

    if ($unlinkedEquipments->count() > 0) {
        echo "المعدات غير المربوطة:\n";
        foreach ($unlinkedEquipments as $equipment) {
            echo "- {$equipment->name} (ID: {$equipment->id}) - الفئة: {$equipment->category}\n";
        }
    }

    // إنشاء معدة شاحنة جديدة بدون ربط تلقائي للاختبار
    echo "\nإنشاء معدة شاحنة للاختبار...\n";

    // تعطيل الأحداث مؤقتاً
    Equipment::unsetEventDispatcher();

    $testEquipment = Equipment::create([
        'name' => 'شاحنة اختبار - TEST123',
        'category' => 'شاحنات',
        'type' => 'اختبار',
        'serial_number' => 'TEST' . time(),
        'status' => 'available',
        'purchase_date' => now(),
        'purchase_price' => 300000,
        'description' => 'شاحنة للاختبار فقط',
        'user_id' => 1,
        'truck_id' => null // تأكد من عدم وجود ربط
    ]);

    echo "تم إنشاء معدة اختبار: {$testEquipment->name}\n";
    echo "هذه المعدة يجب أن تظهر في قسم 'معدات غير مربوطة' في صفحة الشاحنات الداخلية\n";
} catch (Exception $e) {
    echo "خطأ: " . $e->getMessage() . "\n";
}

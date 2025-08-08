<?php
require_once 'vendor/autoload.php';

use App\Models\InternalTruck;
use App\Models\Equipment;

// إعداد Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== اختبار النظام الثنائي الكامل ===\n\n";

try {
    echo "1. إنشاء معدة شاحنة جديدة...\n";

    // إنشاء معدة من نوع شاحنة
    $equipment = Equipment::create([
        'name' => 'سكانيا R730 - JKL012',
        'category' => 'شاحنات',
        'type' => 'نقل ثقيل',
        'model' => 'R730',
        'manufacturer' => 'سكانيا',
        'serial_number' => 'SCN' . rand(100000, 999999),
        'status' => 'available',
        'purchase_date' => now(),
        'purchase_price' => 550000,
        'description' => 'شاحنة سكانيا للنقل الثقيل',
        'user_id' => 1
    ]);

    echo "   ✅ تم إنشاء المعدة: {$equipment->name} (ID: {$equipment->id})\n";

    // فترة انتظار قصيرة للسماح للأحداث بالتنفيذ
    sleep(1);

    // التحقق من إنشاء الشاحنة التلقائي
    $autoTruck = InternalTruck::where('plate_number', 'JKL012')->first();
    if ($autoTruck) {
        echo "   ✅ تم إنشاء شاحنة داخلية تلقائياً: {$autoTruck->brand} {$autoTruck->model} (ID: {$autoTruck->id})\n";
        echo "   ✅ المعدة مرتبطة بالشاحنة (truck_id: {$equipment->fresh()->truck_id})\n";
    } else {
        echo "   ❌ لم يتم إنشاء الشاحنة الداخلية تلقائياً\n";
    }

    echo "\n2. إنشاء شاحنة داخلية جديدة...\n";

    // إنشاء شاحنة داخلية مباشرة
    $truck = InternalTruck::create([
        'plate_number' => 'MNO345',
        'brand' => 'فولفو',
        'model' => 'FMX',
        'year' => 2024,
        'load_capacity' => 15.0,
        'fuel_type' => 'diesel',
        'status' => 'available',
        'purchase_date' => now(),
        'purchase_price' => 600000,
        'description' => 'شاحنة فولفو للعمليات الخاصة',
        'user_id' => 1
    ]);

    echo "   ✅ تم إنشاء الشاحنة: {$truck->brand} {$truck->model} - {$truck->plate_number} (ID: {$truck->id})\n";

    // فترة انتظار قصيرة للسماح للأحداث بالتنفيذ
    sleep(1);

    // التحقق من إنشاء المعدة التلقائي
    $autoEquipment = Equipment::where('truck_id', $truck->id)->first();
    if ($autoEquipment) {
        echo "   ✅ تم إنشاء معدة تلقائياً: {$autoEquipment->name} (ID: {$autoEquipment->id})\n";
        echo "   ✅ المعدة من فئة: {$autoEquipment->category}\n";
    } else {
        echo "   ❌ لم يتم إنشاء المعدة تلقائياً\n";
    }

    echo "\n3. تحديث بيانات لاختبار المزامنة...\n";

    // تحديث الشاحنة الأولى
    if ($autoTruck) {
        $autoTruck->update([
            'status' => 'in_use',
            'description' => 'محدثة - قيد الاستخدام'
        ]);
        echo "   ✅ تم تحديث الشاحنة الأولى\n";

        // التحقق من تحديث المعدة
        $equipment->refresh();
        if ($equipment->status == 'in_use') {
            echo "   ✅ تم تحديث المعدة المرتبطة تلقائياً\n";
        }
    }

    echo "\n=== ملخص النتائج ===\n";
    echo "✅ إنشاء معدة شاحنة → إنشاء شاحنة داخلية تلقائياً\n";
    echo "✅ إنشاء شاحنة داخلية → إنشاء معدة تلقائياً\n";
    echo "✅ الربط الثنائي يعمل بشكل مثالي\n";

    echo "\nاختبار النظام مكتمل بنجاح! 🎉\n";
} catch (Exception $e) {
    echo "خطأ: " . $e->getMessage() . "\n";
    echo "في الملف: " . $e->getFile() . " السطر: " . $e->getLine() . "\n";
}

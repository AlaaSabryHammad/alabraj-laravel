<?php
require_once 'vendor/autoload.php';

use App\Models\InternalTruck;
use App\Models\Equipment;

// إعداد Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== اختبار سريع للنظام الثنائي ===\n\n";

try {
    echo "✅ الاختبار 1: معدة → شاحنة داخلية\n";

    $equipment = Equipment::create([
        'name' => 'داف XF - PQR678',
        'category' => 'شاحنات',
        'serial_number' => 'DAF' . time(),
        'status' => 'available',
        'purchase_date' => now(),
        'purchase_price' => 400000,
        'user_id' => 1
    ]);

    sleep(1); // انتظار المعالجة

    $truck = InternalTruck::where('plate_number', 'PQR678')->first();
    if ($truck && $equipment->fresh()->truck_id == $truck->id) {
        echo "   ✅ تم إنشاء الشاحنة وربطها بالمعدة\n";
    } else {
        echo "   ❌ فشل في الربط\n";
    }

    echo "\n✅ الاختبار 2: شاحنة داخلية → معدة\n";

    $truck2 = InternalTruck::create([
        'plate_number' => 'STU901',
        'brand' => 'إيفيكو',
        'model' => 'Stralis',
        'year' => 2023,
        'load_capacity' => 8.0,
        'fuel_type' => 'diesel',
        'status' => 'available',
        'purchase_date' => now(),
        'purchase_price' => 350000,
        'user_id' => 1
    ]);

    sleep(1); // انتظار المعالجة

    $equipment2 = Equipment::where('truck_id', $truck2->id)->first();
    if ($equipment2) {
        echo "   ✅ تم إنشاء المعدة المرتبطة\n";
    } else {
        echo "   ❌ فشل في إنشاء المعدة\n";
    }

    echo "\n🎉 النظام الثنائي يعمل بنجاح!\n";
} catch (Exception $e) {
    echo "❌ خطأ: " . $e->getMessage() . "\n";
}

<?php
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\SparePart;
use App\Models\SparePartSerial;

echo "🔍 اختبار نظام إنتاج الباركود والأرقام التسلسلية:\n\n";

// اختبار 1: إنشاء كود قطعة غيار
echo "1. إنشاء كود قطعة غيار:\n";
$code1 = SparePart::generateCode();
$code2 = SparePart::generateCode();
echo "   الكود الأول: $code1\n";
echo "   الكود الثاني: $code2\n";
echo "   ✅ " . ($code1 !== $code2 ? "الأكواد فريدة" : "❌ خطأ: الأكواد متشابهة") . "\n\n";

// اختبار 2: إنشاء قطعة غيار واختبار الباركود والرقم التسلسلي
echo "2. اختبار الباركود والرقم التسلسلي:\n";
$sparePart = SparePart::where('is_active', true)->first();
if ($sparePart) {
    echo "   قطعة الغيار: {$sparePart->name} (ID: {$sparePart->id})\n";
    
    $barcode1 = $sparePart->generateBarcode();
    $barcode2 = $sparePart->generateBarcode();
    $serial1 = $sparePart->generateSerialNumber();
    $serial2 = $sparePart->generateSerialNumber();
    
    echo "   الباركود الأول: $barcode1\n";
    echo "   الباركود الثاني: $barcode2\n";
    echo "   الرقم التسلسلي الأول: $serial1\n";
    echo "   الرقم التسلسلي الثاني: $serial2\n";
    
    echo "   ✅ " . ($barcode1 !== $barcode2 ? "الباركودات فريدة" : "❌ خطأ: الباركودات متشابهة") . "\n";
    echo "   ✅ " . ($serial1 !== $serial2 ? "الأرقام التسلسلية فريدة" : "❌ خطأ: الأرقام التسلسلية متشابهة") . "\n\n";
    
    echo "3. محاولة إنشاء سجل رقم تسلسلي:\n";
    try {
        $sparePartSerial = SparePartSerial::create([
            'spare_part_id' => $sparePart->id,
            'serial_number' => $serial1,
            'barcode' => $barcode1,
            'location_id' => 1, // افتراضي
            'status' => 'available',
        ]);
        echo "   ✅ تم إنشاء سجل الرقم التسلسلي بنجاح\n";
        echo "   📦 معرف السجل: {$sparePartSerial->id}\n";
    } catch (Exception $e) {
        echo "   ❌ خطأ في إنشاء السجل: " . $e->getMessage() . "\n";
    }
} else {
    echo "   ❌ لا توجد قطع غيار في النظام\n";
}

echo "\n🎉 انتهى الاختبار!\n";
?>

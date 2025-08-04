<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Location;
use App\Models\LocationType;

echo "=== إضافة 15 موقع وهمي ===" . PHP_EOL . PHP_EOL;

// بيانات المواقع الوهمية
$dummyLocations = [
    [
        'name' => 'كسارة الرياض الشمالية',
        'location_type_id' => 1, // كسارة
        'type' => 'site',
        'address' => 'طريق الملك خالد، شمال الرياض',
        'city' => 'الرياض',
        'region' => 'منطقة الرياض',
        'coordinates' => '24.7890, 46.6553',
        'description' => 'كسارة حجارة رئيسية لإنتاج الركام',
        'status' => 'active',
        'manager_name' => 'أحمد بن سعد العتيبي',
        'contact_phone' => '0551234567',
        'area_size' => 15000.00
    ],
    [
        'name' => 'خلاطة جدة المركزية',
        'location_type_id' => 2, // خلاطة
        'type' => 'site',
        'address' => 'طريق مكة القديم، جدة',
        'city' => 'جدة',
        'region' => 'منطقة مكة المكرمة',
        'coordinates' => '21.5169, 39.2192',
        'description' => 'محطة خلط الخرسانة الجاهزة',
        'status' => 'active',
        'manager_name' => 'محمد بن عبدالله القرشي',
        'contact_phone' => '0559876543',
        'area_size' => 8000.00
    ],
    [
        'name' => 'مكتب الدمام الإقليمي',
        'location_type_id' => 3, // مكتب
        'type' => 'office',
        'address' => 'حي الفيصلية، الدمام',
        'city' => 'الدمام',
        'region' => 'المنطقة الشرقية',
        'coordinates' => '26.4282, 50.0918',
        'description' => 'المكتب الإقليمي للمنطقة الشرقية',
        'status' => 'active',
        'manager_name' => 'خالد بن عبدالرحمن الدوسري',
        'contact_phone' => '0138765432',
        'area_size' => 500.00
    ],
    [
        'name' => 'مستودع مكة للمعدات',
        'location_type_id' => 4, // مستودع
        'type' => 'warehouse',
        'address' => 'طريق جدة السريع، مكة المكرمة',
        'city' => 'مكة المكرمة',
        'region' => 'منطقة مكة المكرمة',
        'coordinates' => '21.3891, 39.8579',
        'description' => 'مستودع تخزين المعدات والأدوات',
        'status' => 'active',
        'manager_name' => 'عبدالعزيز بن أحمد الغامدي',
        'contact_phone' => '0125556789',
        'area_size' => 3000.00
    ],
    [
        'name' => 'ورشة الصيانة المركزية',
        'location_type_id' => 5, // ورشة
        'type' => 'workshop',
        'address' => 'المدينة الصناعية الثانية، الرياض',
        'city' => 'الرياض',
        'region' => 'منطقة الرياض',
        'coordinates' => '24.6408, 46.7728',
        'description' => 'ورشة الصيانة الرئيسية للمعدات الثقيلة',
        'status' => 'active',
        'manager_name' => 'سعد بن محمد العنزي',
        'contact_phone' => '0114445566',
        'area_size' => 2500.00
    ],
    [
        'name' => 'موقع مشروع طريق الطائف',
        'location_type_id' => 6, // موقع
        'type' => 'site',
        'address' => 'طريق الطائف - الرياض السريع',
        'city' => 'الطائف',
        'region' => 'منطقة مكة المكرمة',
        'coordinates' => '21.2703, 40.4178',
        'description' => 'موقع تطوير وصيانة طريق الطائف',
        'status' => 'active',
        'manager_name' => 'فهد بن سلطان الحربي',
        'contact_phone' => '0127778899',
        'area_size' => 50000.00
    ],
    [
        'name' => 'كسارة أبها الجنوبية',
        'location_type_id' => 1, // كسارة
        'type' => 'site',
        'address' => 'طريق الملك فهد، أبها',
        'city' => 'أبها',
        'region' => 'منطقة عسير',
        'coordinates' => '18.2164, 42.5053',
        'description' => 'كسارة الجرانيت والحجر الطبيعي',
        'status' => 'active',
        'manager_name' => 'علي بن يحيى عسيري',
        'contact_phone' => '0172223344',
        'area_size' => 12000.00
    ],
    [
        'name' => 'خلاطة القصيم الصناعية',
        'location_type_id' => 2, // خلاطة
        'type' => 'site',
        'address' => 'المدينة الصناعية، بريدة',
        'city' => 'بريدة',
        'region' => 'منطقة القصيم',
        'coordinates' => '26.3444, 43.9750',
        'description' => 'محطة إنتاج الخرسانة عالية الجودة',
        'status' => 'active',
        'manager_name' => 'ناصر بن عبدالله المطيري',
        'contact_phone' => '0166667788',
        'area_size' => 6000.00
    ],
    [
        'name' => 'مكتب تبوك الفرعي',
        'location_type_id' => 3, // مكتب
        'type' => 'office',
        'address' => 'شارع الأمير فهد بن سلطان، تبوك',
        'city' => 'تبوك',
        'region' => 'منطقة تبوك',
        'coordinates' => '28.3998, 36.5697',
        'description' => 'مكتب المشاريع الشمالية',
        'status' => 'active',
        'manager_name' => 'مشعل بن فهد الشراري',
        'contact_phone' => '0144445566',
        'area_size' => 400.00
    ],
    [
        'name' => 'مستودع حائل للقطع',
        'location_type_id' => 4, // مستودع
        'type' => 'warehouse',
        'address' => 'طريق المدينة المنورة، حائل',
        'city' => 'حائل',
        'region' => 'منطقة حائل',
        'coordinates' => '27.5236, 41.6948',
        'description' => 'مستودع قطع الغيار والمواد الاستهلاكية',
        'status' => 'active',
        'manager_name' => 'بدر بن سعد الشمري',
        'contact_phone' => '0165554433',
        'area_size' => 1800.00
    ],
    [
        'name' => 'ورشة جازان البحرية',
        'location_type_id' => 5, // ورشة
        'type' => 'workshop',
        'address' => 'الكورنيش الجنوبي، جازان',
        'city' => 'جازان',
        'region' => 'منطقة جازان',
        'coordinates' => '16.9026, 42.5663',
        'description' => 'ورشة صيانة المعدات البحرية',
        'status' => 'active',
        'manager_name' => 'حسن بن أحمد فقيهي',
        'contact_phone' => '0173332211',
        'area_size' => 2000.00
    ],
    [
        'name' => 'موقع مشروع نيوم',
        'location_type_id' => 6, // موقع
        'type' => 'site',
        'address' => 'منطقة نيوم، تبوك',
        'city' => 'تبوك',
        'region' => 'منطقة تبوك',
        'coordinates' => '28.2666, 35.3204',
        'description' => 'موقع مشروع نيوم للتطوير الحضري',
        'status' => 'active',
        'manager_name' => 'طلال بن عبدالعزيز البلوي',
        'contact_phone' => '0144448888',
        'area_size' => 100000.00
    ],
    [
        'name' => 'كسارة الجوف الشمالية',
        'location_type_id' => 1, // كسارة
        'type' => 'site',
        'address' => 'طريق عرعر، سكاكا',
        'city' => 'سكاكا',
        'region' => 'منطقة الجوف',
        'coordinates' => '29.9533, 40.2061',
        'description' => 'كسارة الصخور والحصى',
        'status' => 'active',
        'manager_name' => 'راشد بن محمد الرويلي',
        'contact_phone' => '0146669977',
        'area_size' => 18000.00
    ],
    [
        'name' => 'خلاطة الخرج الزراعية',
        'location_type_id' => 2, // خلاطة
        'type' => 'site',
        'address' => 'طريق الخرج - الرياض',
        'city' => 'الخرج',
        'region' => 'منطقة الرياض',
        'coordinates' => '24.1442, 47.3341',
        'description' => 'محطة خلط للمشاريع الزراعية',
        'status' => 'active',
        'manager_name' => 'صالح بن عبدالرحمن الدوسري',
        'contact_phone' => '0115556677',
        'area_size' => 4500.00
    ],
    [
        'name' => 'مستودع الباحة الجبلي',
        'location_type_id' => 4, // مستودع
        'type' => 'warehouse',
        'address' => 'طريق الطائف - الباحة',
        'city' => 'الباحة',
        'region' => 'منطقة الباحة',
        'coordinates' => '20.0129, 41.4687',
        'description' => 'مستودع المعدات الجبلية والمناخ البارد',
        'status' => 'active',
        'manager_name' => 'عبدالله بن محمد الغامدي',
        'contact_phone' => '0177775544',
        'area_size' => 2200.00
    ]
];

echo "بدء إضافة المواقع الوهمية..." . PHP_EOL . PHP_EOL;

$created = 0;
$skipped = 0;

foreach ($dummyLocations as $index => $locationData) {
    // التحقق من وجود الموقع
    $existingLocation = Location::where('name', $locationData['name'])->first();

    if ($existingLocation) {
        echo "⚠️  الموقع '{$locationData['name']}' موجود مسبقاً - تم التخطي" . PHP_EOL;
        $skipped++;
        continue;
    }

    try {
        $location = Location::create($locationData);
        echo "✅ تم إنشاء الموقع: {$location->name} في {$location->city}" . PHP_EOL;
        $created++;
    } catch (Exception $e) {
        echo "❌ خطأ في إنشاء الموقع '{$locationData['name']}': " . $e->getMessage() . PHP_EOL;
    }
}

echo PHP_EOL . "=== النتائج ===" . PHP_EOL;
echo "تم إنشاء: {$created} موقع جديد" . PHP_EOL;
echo "تم تخطي: {$skipped} موقع (موجود مسبقاً)" . PHP_EOL;
echo "إجمالي المواقع الآن: " . Location::count() . " موقع" . PHP_EOL;

echo PHP_EOL . "=== قائمة جميع المواقع ===" . PHP_EOL;
$allLocations = Location::with('locationType')->get();
foreach ($allLocations as $location) {
    $typeName = $location->locationType ? $location->locationType->name : 'غير محدد';
    echo "📍 {$location->name} ({$typeName}) - {$location->city}" . PHP_EOL;
}

echo PHP_EOL . "تم الانتهاء! يمكنك الآن زيارة http://127.0.0.1:8000/locations لعرض المواقع" . PHP_EOL;

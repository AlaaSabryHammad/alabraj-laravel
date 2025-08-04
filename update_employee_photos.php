<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Employee;
use Illuminate\Support\Facades\Storage;

echo "=== تحميل وتحديث الصور الشخصية للموظفين ===" . PHP_EOL . PHP_EOL;

// الحصول على جميع الموظفين الذين لا يملكون صور
$employees = Employee::whereNull('photo')->orWhere('photo', '')->get();

if ($employees->isEmpty()) {
    echo "✅ جميع الموظفين يملكون صور بالفعل!" . PHP_EOL;
    exit;
}

echo "تم العثور على {$employees->count()} موظف بحاجة لصور شخصية" . PHP_EOL . PHP_EOL;

// إنشاء مجلد الصور إذا لم يكن موجوداً
$photosPath = storage_path('app/public/employees/photos');
if (!file_exists($photosPath)) {
    mkdir($photosPath, 0755, true);
    echo "تم إنشاء مجلد الصور: {$photosPath}" . PHP_EOL;
}

$updated = 0;
$errors = 0;

// مصادر مختلفة للصور العشوائية
$imageServices = [
    'https://thispersondoesnotexist.com/image', // وجوه حقيقية مولدة بالذكاء الاصطناعي
    'https://picsum.photos/400/400', // صور عشوائية
    'https://i.pravatar.cc/400', // أفاتار عشوائي
];

foreach ($employees as $index => $employee) {
    try {
        $current = $index + 1;
        $total = $employees->count();
        echo "معالجة الموظف: {$employee->name} ({$current}/{$total})" . PHP_EOL;

        // اختيار خدمة عشوائية للصور
        $imageUrl = $imageServices[array_rand($imageServices)];

        // إضافة معاملات عشوائية لضمان الحصول على صور مختلفة
        if (strpos($imageUrl, 'pravatar.cc') !== false) {
            $imageUrl .= '?img=' . rand(1, 70); // pravatar لديه 70 صورة
        } elseif (strpos($imageUrl, 'picsum.photos') !== false) {
            $imageUrl .= '?random=' . rand(1, 1000);
        } elseif (strpos($imageUrl, 'thispersondoesnotexist.com') !== false) {
            // إضافة معامل عشوائي لتجنب التخزين المؤقت
            $imageUrl .= '?t=' . time() . rand(1, 1000);
        }

        // تحميل الصورة
        $imageData = downloadImage($imageUrl);

        if ($imageData === false) {
            echo "  ❌ فشل في تحميل الصورة من: {$imageUrl}" . PHP_EOL;
            $errors++;
            continue;
        }

        // إنشاء اسم فريد للملف
        $fileName = 'employee_' . $employee->id . '_' . uniqid() . '.jpg';
        $filePath = 'employees/photos/' . $fileName;

        // حفظ الصورة
        if (Storage::disk('public')->put($filePath, $imageData)) {
            // تحديث بيانات الموظف
            $employee->update(['photo' => $filePath]);

            echo "  ✅ تم تحديث الصورة: {$fileName}" . PHP_EOL;
            $updated++;
        } else {
            echo "  ❌ فشل في حفظ الصورة" . PHP_EOL;
            $errors++;
        }

        // توقف قصير لتجنب إرهاق الخوادم
        usleep(500000); // 0.5 ثانية

    } catch (\Exception $e) {
        echo "  ❌ خطأ: " . $e->getMessage() . PHP_EOL;
        $errors++;
    }
}

echo PHP_EOL . "=== النتائج النهائية ===" . PHP_EOL;
echo "✅ تم تحديث: {$updated} صورة" . PHP_EOL;
echo "❌ أخطاء: {$errors} خطأ" . PHP_EOL;

// إحصائيات إضافية
$totalEmployees = Employee::count();
$employeesWithPhotos = Employee::whereNotNull('photo')->where('photo', '!=', '')->count();

echo PHP_EOL . "=== إحصائيات الصور ===" . PHP_EOL;
echo "📊 إجمالي الموظفين: {$totalEmployees}" . PHP_EOL;
echo "📷 الموظفين مع صور: {$employeesWithPhotos}" . PHP_EOL;
echo "📷 الموظفين بدون صور: " . ($totalEmployees - $employeesWithPhotos) . PHP_EOL;
echo "📈 نسبة الصور: " . round(($employeesWithPhotos / $totalEmployees) * 100, 1) . "%" . PHP_EOL;

echo PHP_EOL . "تم الانتهاء! يمكنك الآن زيارة صفحات الموظفين لرؤية الصور الجديدة." . PHP_EOL;

/**
 * تحميل صورة من رابط
 */
function downloadImage($url, $timeout = 30) {
    // إعداد cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    // تحميل البيانات
    $data = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if (curl_error($ch)) {
        echo "  ⚠️  خطأ في cURL: " . curl_error($ch) . PHP_EOL;
        curl_close($ch);
        return false;
    }

    curl_close($ch);

    // التحقق من نجاح التحميل
    if ($httpCode !== 200) {
        echo "  ⚠️  HTTP Code: {$httpCode}" . PHP_EOL;
        return false;
    }

    // التحقق من أن البيانات صورة صالحة
    if (empty($data) || strlen($data) < 1000) {
        echo "  ⚠️  بيانات الصورة غير صالحة أو صغيرة جداً" . PHP_EOL;
        return false;
    }

    // التحقق من نوع الملف
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->buffer($data);

    if (!in_array($mimeType, ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'])) {
        echo "  ⚠️  نوع الملف غير مدعوم: {$mimeType}" . PHP_EOL;
        return false;
    }

    return $data;
}

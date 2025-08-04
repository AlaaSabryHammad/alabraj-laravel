<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Employee;
use App\Models\Location;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "=== إضافة 200 موظف بمختلف الصلاحيات ===" . PHP_EOL . PHP_EOL;

// الحصول على المواقع المتاحة
$locations = Location::where('status', 'active')->get();
if ($locations->isEmpty()) {
    die("❌ لا توجد مواقع نشطة في النظام. يرجى إضافة مواقع أولاً." . PHP_EOL);
}

echo "تم العثور على {$locations->count()} موقع متاح" . PHP_EOL;

// الأسماء العربية
$firstNames = [
    'أحمد', 'محمد', 'علي', 'حسن', 'خالد', 'سعد', 'فهد', 'عبدالله', 'عبدالرحمن', 'إبراهيم',
    'فاطمة', 'عائشة', 'خديجة', 'مريم', 'نور', 'سارة', 'هدى', 'أمل', 'نوال', 'ريم',
    'عبدالعزيز', 'سلطان', 'ناصر', 'تركي', 'بندر', 'طلال', 'مشعل', 'راشد', 'فيصل', 'وليد',
    'زينب', 'لطيفة', 'منى', 'هيفاء', 'شيماء', 'دانا', 'غادة', 'جواهر', 'بشرى', 'وفاء'
];

$lastNames = [
    'الأحمد', 'المحمد', 'الخالد', 'السعد', 'الفهد', 'العبدالله', 'الإبراهيم', 'الحسن', 'العلي', 'الناصر',
    'الدوسري', 'العتيبي', 'القحطاني', 'الغامدي', 'الشهري', 'الزهراني', 'الحربي', 'المطيري', 'العنزي', 'الشمري',
    'البلوي', 'الرشيد', 'الفيصل', 'السلطان', 'التركي', 'البندر', 'الطلال', 'المشعل', 'الراشد', 'الوليد',
    'الفقيهي', 'القرشي', 'الهاشمي', 'الأنصاري', 'التميمي', 'الخزرجي', 'الأموي', 'العباسي', 'الفاطمي', 'المكي'
];

// الأقسام
$departments = [
    'الإدارة', 'المالية', 'الهندسة', 'الموارد البشرية', 'الصيانة', 'المشاريع', 'النقليات'
];

// المناصب حسب الأقسام
$positionsByDepartment = [
    'الإدارة' => ['مدير عام', 'مدير إداري', 'مساعد إداري', 'سكرتير', 'موظف إداري'],
    'المالية' => ['مدير مالي', 'محاسب رئيسي', 'محاسب', 'مراقب مالي', 'كاشير'],
    'الهندسة' => ['مهندس رئيسي', 'مهندس مدني', 'مهندس ميكانيكي', 'مهندس كهربائي', 'مساح'],
    'الموارد البشرية' => ['مدير موارد بشرية', 'مختص توظيف', 'مختص رواتب', 'مختص تدريب', 'موظف شؤون إدارية'],
    'الصيانة' => ['رئيس فني', 'فني معدات', 'فني كهربائي', 'فني ميكانيكي', 'عامل صيانة'],
    'المشاريع' => ['مدير مشاريع', 'مهندس مشاريع', 'مشرف مشاريع', 'منسق مشاريع', 'مساعد مشاريع'],
    'النقليات' => ['مدير نقليات', 'مشرف نقليات', 'سائق شاحنة', 'سائق معدة', 'عامل تحميل']
];

// الأدوار والصلاحيات
$roles = [
    'عامل' => 60,        // 60 عامل (30%)
    'مشرف موقع' => 50,   // 50 مشرف موقع (25%)
    'مهندس' => 40,       // 40 مهندس (20%)
    'إداري' => 35,       // 35 إداري (17.5%)
    'مسئول رئيسي' => 15  // 15 مسؤول رئيسي (7.5%)
];

// أنواع الكفالة
$sponsorships = [
    'شركة الأبراج للمقاولات المحدودة',
    'فرع1 شركة الأبراج للمقاولات المحدودة',
    'فرع2 شركة الأبراج للمقاولات المحدودة',
    'مؤسسة فريق التعمير للمقاولات',
    'فرع مؤسسة فريق التعمير للنقل',
    'مؤسسة الزفاف الذهبي',
    'مؤسسة عنوان الكادي',
    'عمالة منزلية',
    'عمالة كفالة خارجية تحت التجربة',
    'أخرى'
];

// الفئات
$categories = ['A+', 'A', 'B', 'C', 'D', 'E'];

// الجنسيات
$nationalities = [
    'سعودي', 'مصري', 'أردني', 'سوري', 'لبناني', 'فلسطيني', 'يمني', 'سوداني',
    'هندي', 'باكستاني', 'بنغلاديشي', 'فلبيني', 'نيبالي', 'سريلانكي'
];

$created = 0;
$skipped = 0;
$errors = 0;

// دالة لإنشاء رقم هوية وطنية فريد
function generateUniqueNationalId() {
    do {
        $nationalId = '1' . str_pad(rand(100000000, 999999999), 9, '0', STR_PAD_LEFT);
    } while (Employee::where('national_id', $nationalId)->exists() ||
             User::where('email', $nationalId . '@alabraaj.com.sa')->exists());
    return $nationalId;
}

// دالة لإنشاء بريد إلكتروني فريد
function generateUniqueEmail($name, $department) {
    $baseEmail = strtolower(str_replace(' ', '.', transliterateArabic($name))) . '@alabraj.com.sa';
    $counter = 1;
    $email = $baseEmail;

    while (Employee::where('email', $email)->exists()) {
        $email = strtolower(str_replace(' ', '.', transliterateArabic($name))) . $counter . '@alabraj.com.sa';
        $counter++;
    }

    return $email;
}

// دالة لتحويل النص العربي إلى إنجليزي مبسط
function transliterateArabic($text) {
    $arabic = ['أ', 'ب', 'ت', 'ث', 'ج', 'ح', 'خ', 'د', 'ذ', 'ر', 'ز', 'س', 'ش', 'ص', 'ض', 'ط', 'ظ', 'ع', 'غ', 'ف', 'ق', 'ك', 'ل', 'م', 'ن', 'ه', 'و', 'ي', 'ة', 'ا', 'إ', 'آ'];
    $english = ['a', 'b', 't', 'th', 'j', 'h', 'kh', 'd', 'th', 'r', 'z', 's', 'sh', 's', 'd', 't', 'th', 'a', 'gh', 'f', 'q', 'k', 'l', 'm', 'n', 'h', 'w', 'y', 'h', 'a', 'a', 'a'];

    return str_replace($arabic, $english, $text);
}

// دالة لربط دور الموظف بصلاحية النظام
function mapEmployeeRoleToUserRole($employeeRole) {
    $roleMapping = [
        'عامل' => 'employee',
        'مشرف موقع' => 'supervisor',
        'مهندس' => 'engineer',
        'إداري' => 'admin',
        'مسئول رئيسي' => 'manager'
    ];
    return $roleMapping[$employeeRole] ?? 'employee';
}

echo "بدء إضافة الموظفين..." . PHP_EOL . PHP_EOL;

$employeeCounter = 1;

foreach ($roles as $role => $count) {
    echo "إضافة {$count} موظف بصلاحية: {$role}" . PHP_EOL;

    for ($i = 0; $i < $count; $i++) {
        try {
            // إنشاء اسم عشوائي
            $firstName = $firstNames[array_rand($firstNames)];
            $lastName = $lastNames[array_rand($lastNames)];
            $fullName = "{$firstName} {$lastName}";

            // اختيار قسم وموقع عشوائي
            $department = $departments[array_rand($departments)];
            $position = $positionsByDepartment[$department][array_rand($positionsByDepartment[$department])];
            $location = $locations->random();

            // إنشاء البيانات
            $nationalId = generateUniqueNationalId();
            $email = generateUniqueEmail($fullName, $department);
            $phone = '05' . rand(10000000, 99999999);
            $salary = rand(3000, 15000);

            // تاريخ التوظيف عشوائي في السنوات الثلاث الماضية
            $hireDate = date('Y-m-d', strtotime('-' . rand(1, 1095) . ' days'));

            // بيانات الموظف
            $employeeData = [
                'name' => $fullName,
                'position' => $position,
                'department' => $department,
                'email' => $email,
                'phone' => $phone,
                'hire_date' => $hireDate,
                'salary' => $salary,
                'national_id' => $nationalId,
                'address' => 'الرياض، حي ' . ['النرجس', 'الربوة', 'الملقا', 'العليا', 'الياسمين', 'الفيصلية'][array_rand(['النرجس', 'الربوة', 'الملقا', 'العليا', 'الياسمين', 'الفيصلية'])],
                'status' => 'active',
                'role' => $role,
                'sponsorship' => $sponsorships[array_rand($sponsorships)],
                'category' => $categories[array_rand($categories)],
                'location_id' => $location->id,
                'location_assignment_date' => $hireDate,
                'nationality' => $nationalities[array_rand($nationalities)],
                'birth_date' => date('Y-m-d', strtotime('-' . rand(22*365, 60*365) . ' days')),
                'medical_insurance_status' => ['مشمول', 'غير مشمول'][array_rand(['مشمول', 'غير مشمول'])],
                'location_type' => 'داخل المملكة',
                'rating' => rand(3, 5)
            ];

            // إنشاء الموظف
            $employee = Employee::create($employeeData);

            // إنشاء حساب مستخدم
            try {
                $userEmail = $nationalId . '@alabraaj.com.sa';
                $userRole = mapEmployeeRoleToUserRole($role);

                $user = User::create([
                    'name' => $fullName,
                    'email' => $userEmail,
                    'password' => Hash::make($nationalId),
                    'role' => $userRole,
                ]);

                // ربط المستخدم بالموظف
                $employee->update(['user_id' => $user->id]);

            } catch (\Exception $e) {
                // تسجيل الخطأ فقط، لا نوقف العملية
                echo "⚠️  خطأ في إنشاء حساب مستخدم للموظف {$fullName}: " . $e->getMessage() . PHP_EOL;
            }

            $created++;

            if ($employeeCounter % 10 == 0) {
                echo "تم إنشاء {$employeeCounter} موظف..." . PHP_EOL;
            }

            $employeeCounter++;

        } catch (\Exception $e) {
            echo "❌ خطأ في إنشاء الموظف رقم {$employeeCounter}: " . $e->getMessage() . PHP_EOL;
            $errors++;
        }
    }

    echo "تم الانتهاء من إضافة موظفي صلاحية: {$role}" . PHP_EOL . PHP_EOL;
}

echo "=== النتائج النهائية ===" . PHP_EOL;
echo "✅ تم إنشاء: {$created} موظف جديد" . PHP_EOL;
echo "⚠️  أخطاء: {$errors} خطأ" . PHP_EOL;
echo "📊 إجمالي الموظفين الآن: " . Employee::count() . " موظف" . PHP_EOL;

echo PHP_EOL . "=== إحصائيات الموظفين حسب الصلاحيات ===" . PHP_EOL;
foreach ($roles as $role => $expectedCount) {
    $actualCount = Employee::where('role', $role)->count();
    echo "📋 {$role}: {$actualCount} موظف" . PHP_EOL;
}

echo PHP_EOL . "=== إحصائيات حسب الأقسام ===" . PHP_EOL;
foreach ($departments as $department) {
    $count = Employee::where('department', $department)->count();
    echo "🏢 {$department}: {$count} موظف" . PHP_EOL;
}

echo PHP_EOL . "=== إحصائيات حسب المواقع ===" . PHP_EOL;
foreach ($locations as $location) {
    $count = Employee::where('location_id', $location->id)->count();
    echo "📍 {$location->name}: {$count} موظف" . PHP_EOL;
}

echo PHP_EOL . "=== معلومات الحسابات ===" . PHP_EOL;
echo "🔐 جميع كلمات المرور هي أرقام الهوية الوطنية" . PHP_EOL;
echo "📧 البريد الإلكتروني للنظام: [رقم الهوية]@alabraaj.com.sa" . PHP_EOL;
echo "📧 البريد الإلكتروني العام: [اسم الموظف]@alabraj.com.sa" . PHP_EOL;

echo PHP_EOL . "تم الانتهاء! يمكنك الآن زيارة http://127.0.0.1:8000/employees لعرض الموظفين" . PHP_EOL;

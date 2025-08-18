<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\User;
use App\Models\Role;
use App\Models\Location;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('ar_SA'); // Arabic Saudi Arabia locale

        // Get all active roles from database or use defaults
        $roles = [];
        try {
            $roles = Role::where('is_active', true)->pluck('name')->toArray();
        } catch (\Exception $e) {
            // Roles table doesn't exist, use default roles
        }

        if (empty($roles)) {
            $roles = [
                'super_admin',
                'site_manager',
                'equipment_manager',
                'fuel_manager',
                'accountant',
                'warehouse_manager',
                'equipment_operator',
                'observer',
                'laborer',
                'project_manager',
                'tech_manager',
                'hr_manager',
                'employee'
            ];
        }

        // Get all active locations or use defaults
        $locationIds = [];
        try {
            $locations = Location::where('status', 'active')->get();
            $locationIds = $locations->pluck('id')->toArray();
        } catch (\Exception $e) {
            // Locations table doesn't exist or has no active locations
        }

        if (empty($roles)) {
            $this->command->error('No active roles found. Please run RolesAndPermissionsSeeder first.');
            return;
        }

        // Real Arabic names for variety
        $maleNames = [
            'أحمد محمد العبدالله',
            'محمد عبدالله الأحمد',
            'عبدالله أحمد محمد',
            'سعد محمد العتيبي',
            'فهد عبدالله الدوسري',
            'خالد سعد المطيري',
            'عبدالرحمن فهد القحطاني',
            'طلال خالد الغامدي',
            'مساعد طلال الحربي',
            'ناصر مساعد الشهري',
            'عبدالعزيز ناصر الزهراني',
            'سلطان عبدالعزيز العنزي',
            'بندر سلطان الرشيد',
            'ماجد بندر السبيعي',
            'وليد ماجد العسيري',
            'يوسف وليد الفيصل',
            'إبراهيم يوسف الخالد',
            'علي إبراهيم النعيمي',
            'حسن علي البقمي',
            'عمر حسن الجهني',
            'عادل عمر اللحياني',
            'راشد عادل الشمراني',
            'فيصل راشد الدعجاني',
            'نواف فيصل البلوي',
            'تركي نواف القرني',
            'مشعل تركي العمري',
            'صالح مشعل الثبيتي',
            'عبدالمجيد صالح الحكمي',
            'جابر عبدالمجيد المالكي',
            'حمد جابر الشريف',
            'رياض حمد العبدلي',
            'معاذ رياض الرفاعي',
            'أسامة معاذ السلمي',
            'هشام أسامة الحضرمي',
            'كريم هشام البكر',
            'ضياء كريم الطيار',
            'منصور ضياء القاسمي',
            'زكريا منصور الحوسني',
            'عثمان زكريا المري',
            'حامد عثمان الكعبي',
            'سيف حامد النقبي',
            'راكان سيف المهيري',
            'غازي راكان السويدي',
            'جمال غازي الظاهري',
            'كمال جمال العلي',
            'نبيل كمال الحمادي',
            'أنس نبيل الشامسي',
            'مروان أنس الزعابي',
            'حسام مروان النعيمي',
            'عامر حسام الدرعي',
            'باسم عامر المقبل',
            'وائل باسم الشويهين'
        ];

        $femaleNames = [
            'فاطمة أحمد العبدالله',
            'عائشة محمد الأحمد',
            'خديجة عبدالله محمد',
            'مريم سعد العتيبي',
            'زينب فهد الدوسري',
            'رقية خالد المطيري',
            'حفصة عبدالرحمن القحطاني',
            'أم كلثوم طلال الغامدي',
            'سكينة مساعد الحربي',
            'آمنة ناصر الشهري',
            'صفية عبدالعزيز الزهراني',
            'جويرية سلطان العنزي',
            'أسماء بندر الرشيد',
            'حنان ماجد السبيعي',
            'سلمى وليد العسيري',
            'ليلى يوسف الفيصل',
            'نورا إبراهيم الخالد',
            'هند علي النعيمي',
            'رانيا حسن البقمي',
            'دانا عمر الجهني',
            'لينا عادل اللحياني',
            'سارة راشد الشمراني',
            'نادية فيصل الدعجاني',
            'منى نواف البلوي',
            'ريم تركي القرني',
            'نوف مشعل العمري',
            'هيفاء صالح الثبيتي',
            'غادة عبدالمجيد الحكمي',
            'وداد جابر المالكي',
            'أميرة حمد الشريف',
            'جميلة رياض العبدلي',
            'كوثر معاذ الرفاعي',
            'نائلة أسامة السلمي',
            'عبير هشام الحضرمي',
            'منال كريم البكر',
            'وفاء ضياء الطيار',
            'إيمان منصور القاسمي',
            'سميرة زكريا الحوسني',
            'لطيفة عثمان المري',
            'عزة حامد الكعبي',
            'فريدة سيف النقبي',
            'حياة راكان المهيري',
            'نعيمة غازي السويدي',
            'كريمة جمال الظاهري',
            'طيبة كمال العلي',
            'زهرة نبيل الحمادي',
            'بسمة أنس الشامسي',
            'نجوى مروان الزعابي',
            'سعاد حسام النعيمي',
            'فداء عامر الدرعي',
            'رباب باسم المقبل',
            'إلهام وائل الشويهين'
        ];

        // Department mapping in Arabic
        $departments = [
            'الهندسة والإنشاءات',
            'الإدارة العامة',
            'تقنية المعلومات',
            'الموارد البشرية',
            'المحاسبة والمالية',
            'العمليات الميدانية',
            'المشتريات واللوجستيات',
            'ضمان الجودة',
            'الأمن والسلامة',
            'الصيانة والدعم الفني'
        ];

        // Position mapping based on roles
        $rolePositions = [
            'super_admin' => ['المدير العام', 'نائب المدير العام', 'مدير تنفيذي'],
            'site_manager' => ['مدير موقع', 'نائب مدير موقع', 'رئيس مهندسين'],
            'equipment_manager' => ['مدير المعدات', 'مشرف المعدات', 'أخصائي معدات'],
            'fuel_manager' => ['مدير المحروقات', 'مشرف المحروقات', 'أخصائي محروقات'],
            'accountant' => ['محاسب عام', 'محاسب مساعد', 'مدقق حسابات'],
            'warehouse_manager' => ['مدير المخازن', 'مشرف مخازن', 'أمين مخزن'],
            'equipment_operator' => ['مشغل معدة ثقيلة', 'مشغل حفار', 'مشغل رافعة'],
            'observer' => ['مراقب جودة', 'مفتش مشاريع', 'مراقب أعمال'],
            'laborer' => ['عامل بناء', 'عامل صب خرسانة', 'عامل تشطيبات'],
            'project_manager' => ['مدير مشروع', 'منسق مشاريع', 'مهندس مشاريع'],
            'tech_manager' => ['مدير تقني', 'رئيس قسم تقني', 'مهندس نظم'],
            'hr_manager' => ['مدير موارد بشرية', 'أخصائي موارد بشرية', 'منسق شؤون موظفين']
        ];

        // Sponsorship options
        $sponsorshipStatuses = [
            'شركة الأبراج للمقاولات المحدودة',
            'فرع1 شركة الأبراج للمقاولات المحدودة',
            'فرع2 شركة الأبراج للمقاولات المحدودة'
        ];

        $nationalities = [
            'سعودي',
            'مصري',
            'أردني',
            'سوري',
            'لبناني',
            'فلسطيني',
            'يمني',
            'باكستاني',
            'هندي',
            'بنغالي',
            'فلبيني',
            'سريلانكي',
            'نيبالي'
        ];

        echo "Creating 200 employees with realistic Arabic names and varied roles...\n";

        for ($i = 0; $i < 200; $i++) {
            // Generate random national ID (starts with 1 or 2)
            $national_id = $faker->numberBetween(1, 2) . str_pad($faker->numberBetween(100000000, 999999999), 9, '0', STR_PAD_LEFT);

            // Select random gender and name
            $gender = $faker->randomElement(['male', 'female']);
            $name = $gender === 'male' ? $faker->randomElement($maleNames) : $faker->randomElement($femaleNames);

            // Create unique email from name - simplified approach
            $emailName = 'employee' . ($i + 1);
            $email = strtolower($emailName) . '@abraj.com';

            // Select random role
            $selectedRole = $faker->randomElement($roles);

            // Select position based on role
            $positions = $rolePositions[$selectedRole] ?? ['موظف عام', 'مساعد إداري'];
            $position = $faker->randomElement($positions);

            // Select department and other details
            $department = $faker->randomElement($departments);
            $sponsorship = $faker->randomElement($sponsorshipStatuses);
            $nationality = $faker->randomElement($nationalities);
            $locationId = !empty($locationIds) ? $faker->randomElement($locationIds) : null;

            // Create user account
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make('password123'), // Default password
                'role' => $selectedRole,
                'department' => $department,
                'avatar' => null
            ]);

            // Generate phone number
            $phone = '05' . $faker->numberBetween(10000000, 99999999);

            // Create employee record
            Employee::create([
                'name' => $name,
                'position' => $position,
                'department' => $department,
                'email' => $email,
                'phone' => $phone,
                'hire_date' => $faker->dateTimeBetween('-5 years', 'now')->format('Y-m-d'),
                'salary' => $this->getSalaryByRole($selectedRole),
                'status' => $faker->numberBetween(1, 100) > 5 ? 'active' : 'inactive', // 95% active
                'role' => $selectedRole,
                'sponsorship_status' => $sponsorship,
                'category' => $gender === 'male' ? 'ذكر' : 'أنثى',
                'national_id' => $national_id,
                'national_id_expiry' => $faker->dateTimeBetween('+1 year', '+10 years')->format('Y-m-d'),
                'address' => $this->generateSaudiAddress($faker),
                'nationality' => $nationality,
                'birth_date' => $faker->dateTimeBetween('-60 years', '-22 years')->format('Y-m-d'),
                'marital_status' => $faker->randomElement(['متزوج', 'أعزب']),
                'children_count' => $faker->randomElement([0, 0, 0, 1, 2, 3, 4, 5]),
                'religion' => 'مسلم',
                'location_id' => $locationId,
                'user_id' => $user->id,
                'bank_name' => $faker->randomElement(['البنك الأهلي', 'بنك الرياض', 'مصرف الراجحي', 'البنك العربي']),
                'bank_account_number' => $faker->numberBetween(1000000000, 9999999999),
                'iban' => 'SA' . str_pad($faker->numberBetween(1000000000000000, 9999999999999999), 22, '0', STR_PAD_LEFT)
            ]);

            if (($i + 1) % 50 === 0) {
                echo "Created " . ($i + 1) . " employees...\n";
            }
        }

        echo "✅ Successfully created 200 employees with realistic names and varied roles!\n";
    }

    private function getSalaryByRole($role)
    {
        $salaryRanges = [
            'super_admin' => [30000, 35000],
            'site_manager' => [25000, 30000],
            'project_manager' => [22000, 28000],
            'tech_manager' => [20000, 26000],
            'hr_manager' => [18000, 24000],
            'equipment_manager' => [16000, 22000],
            'fuel_manager' => [16000, 22000],
            'warehouse_manager' => [14000, 20000],
            'accountant' => [12000, 18000],
            'observer' => [10000, 15000],
            'equipment_operator' => [8000, 12000],
            'laborer' => [4000, 8000],
            'employee' => [6000, 10000]
        ];

        $range = $salaryRanges[$role] ?? [6000, 10000];
        $faker = \Faker\Factory::create();
        return $faker->numberBetween($range[0], $range[1]);
    }

    private function generateSaudiAddress($faker)
    {
        $cities = ['الرياض', 'جدة', 'الدمام', 'مكة المكرمة', 'المدينة المنورة', 'الطائف', 'بريدة', 'تبوك', 'خميس مشيط', 'حائل'];
        $districts = ['العليا', 'النزهة', 'السليمانية', 'الروضة', 'المروج', 'الصحافة', 'الياسمين', 'الربوة', 'الملقا', 'العقيق'];

        $city = $cities[array_rand($cities)];
        $district = $districts[array_rand($districts)];

        return "حي {$district} ، {$city} ، المملكة العربية السعودية";
    }
}

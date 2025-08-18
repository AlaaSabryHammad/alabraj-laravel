<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use App\Services\AvatarGenerator;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('ar_SA');
        $avatarGenerator = new AvatarGenerator(200);

        // الأسماء العربية للرجال والنساء
        $maleFirstNames = [
            'أحمد',
            'محمد',
            'عبدالله',
            'عبدالرحمن',
            'عبدالعزيز',
            'سعد',
            'فهد',
            'خالد',
            'عمر',
            'علي',
            'حسن',
            'حسين',
            'يوسف',
            'إبراهيم',
            'عثمان',
            'طارق',
            'ماجد',
            'سلطان',
            'بندر',
            'تركي',
            'فيصل',
            'نواف',
            'سلمان',
            'عبدالمجيد',
            'عبدالكريم',
            'زياد',
            'وليد',
            'رامي',
            'عادل',
            'صالح',
            'مشعل',
            'نايف',
            'عبدالمحسن',
            'عبدالناصر',
            'محمود',
            'حمد',
            'راشد',
            'سعود',
            'عبدالحكيم',
            'جمال'
        ];

        $femaleFirstNames = [
            'فاطمة',
            'عائشة',
            'خديجة',
            'زينب',
            'مريم',
            'سارة',
            'نورا',
            'هند',
            'لمى',
            'ريم',
            'دانا',
            'جنى',
            'لينا',
            'سلمى',
            'أمل',
            'نوف',
            'غدير',
            'شهد',
            'رند',
            'لجين',
            'جود',
            'رهف',
            'تالا',
            'ليان',
            'روان',
            'أسماء',
            'منى',
            'سهى',
            'وفاء',
            'نادية',
            'سمية',
            'حنان',
            'إيمان',
            'خولة',
            'بشرى',
            'نوال',
            'سعاد',
            'عبير',
            'نهى',
            'آمال'
        ];

        $lastNames = [
            'العتيبي',
            'الحربي',
            'الغامدي',
            'الزهراني',
            'الدوسري',
            'القحطاني',
            'الشمري',
            'المطيري',
            'العنزي',
            'الرشيدي',
            'السبيعي',
            'الخالدي',
            'الفهد',
            'النعيمي',
            'الصقر',
            'الشهري',
            'الثقفي',
            'البقمي',
            'الأحمدي',
            'المالكي',
            'العامري',
            'الجهني',
            'الحارثي',
            'البلوي',
            'العسيري',
            'الفيفي',
            'الكعبي',
            'السلمي',
            'الطيار',
            'الشيباني',
            'الناصر',
            'العبدلي',
            'الوهيبي',
            'البريكي',
            'الخثعمي',
            'الظاهري',
            'المري',
            'السويدي',
            'الهاجري',
            'الكندي'
        ];

        $roles = ['admin', 'manager', 'employee', 'finance', 'supervisor', 'engineer', 'accountant', 'hr'];
        $departments = [
            'الإدارة العامة',
            'الهندسة',
            'المحاسبة',
            'الموارد البشرية',
            'المشاريع',
            'المالية',
            'الأمن والسلامة',
            'الصيانة',
            'المشتريات',
            'الجودة',
            'التسويق',
            'تكنولوجيا المعلومات'
        ];

        // بيانات إضافية للموظفين
        $positions = [
            'admin' => 'مدير عام',
            'manager' => 'مدير قسم',
            'supervisor' => 'مشرف',
            'engineer' => 'مهندس',
            'accountant' => 'محاسب',
            'finance' => 'مسؤول مالي',
            'hr' => 'أخصائي موارد بشرية',
            'employee' => 'موظف',
            'user' => 'مستخدم'
        ];

        $nationalities = ['سعودي', 'مصري', 'أردني', 'سوري', 'لبناني', 'فلسطيني', 'يمني', 'عراقي', 'سوداني'];
        $maritalStatuses = ['أعزب', 'متزوج', 'مطلق', 'أرمل'];
        $religions = ['مسلم', 'مسيحي'];
        $sponsorshipStatuses = ['مواطن', 'مقيم', 'زيارة عمل'];
        $categories = ['إداري', 'فني', 'مهندس', 'مشرف', 'عامل'];

        // Create Admin User
        $adminName = 'مدير النظام';
        $adminAvatar = $avatarGenerator->generateSimpleAvatar($adminName);

        // تحقق من عدم وجود المستخدم مسبقاً
        $adminUser = User::where('email', 'admin@abraj.com')->first();
        if (!$adminUser) {
            $adminUser = User::create([
                'name' => $adminName,
                'email' => 'admin@abraj.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'avatar' => $adminAvatar,
                'email_verified_at' => now(),
            ]);

            // إنشاء سجل الموظف للمدير
            Employee::create([
                'user_id' => $adminUser->id,
                'name' => $adminName,
                'email' => 'admin@abraj.com',
                'phone' => $faker->phoneNumber,
                'department' => 'الإدارة العامة',
                'position' => $positions['admin'],
                'role' => 'admin',
                'salary' => $faker->numberBetween(15000, 25000),
                'hire_date' => $faker->dateTimeBetween('-5 years', '-1 year'),
                'contract_start' => $faker->dateTimeBetween('-5 years', '-1 year'),
                'contract_end' => $faker->dateTimeBetween('+1 year', '+3 years'),
                'birth_date' => $faker->dateTimeBetween('-60 years', '-30 years'),
                'nationality' => 'سعودي',
                'marital_status' => $faker->randomElement($maritalStatuses),
                'children_count' => $faker->numberBetween(0, 5),
                'religion' => 'مسلم',
                'sponsorship_status' => 'مواطن',
                'category' => 'إداري',
                'national_id' => '1' . $faker->numerify('#########'),
                'national_id_expiry' => $faker->dateTimeBetween('+1 year', '+10 years'),
                'address' => $faker->address,
                'photo' => $adminAvatar,
                'bank_name' => $faker->randomElement(['البنك الأهلي', 'الراجحي', 'ساب', 'البنك العربي']),
                'bank_account_number' => $faker->numerify('##############'),
                'iban' => 'SA' . $faker->numerify('####################'),
                'emergency_contact_name' => $faker->name,
                'emergency_contact_phone' => $faker->phoneNumber,
                'emergency_contact_relationship' => $faker->randomElement(['زوج/زوجة', 'أب', 'أم', 'أخ', 'أخت']),
                'rating' => $faker->randomFloat(2, 3.5, 5.0),
            ]);
        }

        // Create Second Admin User
        $admin2Name = 'فيصل عبدالعزيز الرشيد';
        $admin2Avatar = $avatarGenerator->generateSimpleAvatar($admin2Name);

        // تحقق من عدم وجود المستخدم مسبقاً
        $admin2User = User::where('email', 'admin123@abraj.com')->first();
        if (!$admin2User) {
            $admin2User = User::create([
                'name' => $admin2Name,
                'email' => 'admin123@abraj.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'avatar' => $admin2Avatar,
                'email_verified_at' => now(),
            ]);

            // إنشاء سجل الموظف للمدير الثاني
            Employee::create([
                'user_id' => $admin2User->id,
                'name' => $admin2Name,
                'email' => 'admin123@abraj.com',
                'phone' => $faker->phoneNumber,
                'department' => 'الإدارة العامة',
                'position' => $positions['admin'],
                'role' => 'admin',
                'salary' => $faker->numberBetween(16000, 26000),
                'hire_date' => $faker->dateTimeBetween('-4 years', '-6 months'),
                'contract_start' => $faker->dateTimeBetween('-4 years', '-6 months'),
                'contract_end' => $faker->dateTimeBetween('+1 year', '+3 years'),
                'birth_date' => $faker->dateTimeBetween('-55 years', '-32 years'),
                'nationality' => 'سعودي',
                'marital_status' => $faker->randomElement($maritalStatuses),
                'children_count' => $faker->numberBetween(1, 4),
                'religion' => 'مسلم',
                'sponsorship_status' => 'مواطن',
                'category' => 'إداري',
                'national_id' => '1' . $faker->numerify('#########'),
                'national_id_expiry' => $faker->dateTimeBetween('+1 year', '+10 years'),
                'address' => $faker->address,
                'photo' => $admin2Avatar,
                'bank_name' => $faker->randomElement(['البنك الأهلي', 'الراجحي', 'ساب', 'البنك العربي']),
                'bank_account_number' => $faker->numerify('##############'),
                'iban' => 'SA' . $faker->numerify('####################'),
                'emergency_contact_name' => $faker->name,
                'emergency_contact_phone' => $faker->phoneNumber,
                'emergency_contact_relationship' => $faker->randomElement(['زوج/زوجة', 'أب', 'أم', 'أخ', 'أخت']),
                'rating' => $faker->randomFloat(2, 3.5, 5.0),
            ]);
        }

        // إنشاء المستخدمين العاديين

        // Create Regular User
        $userName = 'أحمد محمد العتيبي';
        $userAvatar = $avatarGenerator->generateSimpleAvatar($userName);
        $regularUser = User::create([
            'name' => $userName,
            'email' => 'user@abraj.com',
            'password' => Hash::make('user123'),
            'role' => 'user',
            'avatar' => $userAvatar,
            'email_verified_at' => now(),
        ]);

        Employee::create([
            'user_id' => $regularUser->id,
            'name' => $userName,
            'email' => 'user@abraj.com',
            'phone' => $faker->phoneNumber,
            'department' => 'الجودة',
            'position' => $positions['user'],
            'role' => 'user',
            'salary' => $faker->numberBetween(5000, 8000),
            'hire_date' => $faker->dateTimeBetween('-3 years', '-6 months'),
            'contract_start' => $faker->dateTimeBetween('-3 years', '-6 months'),
            'contract_end' => $faker->dateTimeBetween('+1 year', '+3 years'),
            'birth_date' => $faker->dateTimeBetween('-45 years', '-25 years'),
            'nationality' => $faker->randomElement($nationalities),
            'marital_status' => $faker->randomElement($maritalStatuses),
            'children_count' => $faker->numberBetween(0, 4),
            'religion' => $faker->randomElement($religions),
            'sponsorship_status' => $faker->randomElement($sponsorshipStatuses),
            'category' => $faker->randomElement($categories),
            'national_id' => '1' . $faker->numerify('#########'),
            'national_id_expiry' => $faker->dateTimeBetween('+1 year', '+10 years'),
            'address' => $faker->address,
            'photo' => $userAvatar,
            'bank_name' => $faker->randomElement(['البنك الأهلي', 'الراجحي', 'ساب', 'البنك العربي']),
            'bank_account_number' => $faker->numerify('##############'),
            'iban' => 'SA' . $faker->numerify('####################'),
            'emergency_contact_name' => $faker->name,
            'emergency_contact_phone' => $faker->phoneNumber,
            'emergency_contact_relationship' => $faker->randomElement(['زوج/زوجة', 'أب', 'أم', 'أخ', 'أخت']),
            'rating' => $faker->randomFloat(2, 2.5, 4.5),
        ]);

        // Create Project Manager
        $managerName = 'سارة عبدالرحمن الحربي';
        $managerAvatar = $avatarGenerator->generateSimpleAvatar($managerName);
        $managerUser = User::create([
            'name' => $managerName,
            'email' => 'manager@abraj.com',
            'password' => Hash::make('manager123'),
            'role' => 'manager',
            'avatar' => $managerAvatar,
            'email_verified_at' => now(),
        ]);

        Employee::create([
            'user_id' => $managerUser->id,
            'name' => $managerName,
            'email' => 'manager@abraj.com',
            'phone' => $faker->phoneNumber,
            'department' => 'المشاريع',
            'position' => $positions['manager'],
            'role' => 'manager',
            'salary' => $faker->numberBetween(10000, 15000),
            'hire_date' => $faker->dateTimeBetween('-4 years', '-1 year'),
            'contract_start' => $faker->dateTimeBetween('-4 years', '-1 year'),
            'contract_end' => $faker->dateTimeBetween('+1 year', '+3 years'),
            'birth_date' => $faker->dateTimeBetween('-50 years', '-28 years'),
            'nationality' => 'سعودي',
            'marital_status' => $faker->randomElement($maritalStatuses),
            'children_count' => $faker->numberBetween(0, 3),
            'religion' => 'مسلم',
            'sponsorship_status' => 'مواطن',
            'category' => 'إداري',
            'national_id' => '1' . $faker->numerify('#########'),
            'national_id_expiry' => $faker->dateTimeBetween('+1 year', '+10 years'),
            'address' => $faker->address,
            'photo' => $managerAvatar,
            'bank_name' => $faker->randomElement(['البنك الأهلي', 'الراجحي', 'ساب', 'البنك العربي']),
            'bank_account_number' => $faker->numerify('##############'),
            'iban' => 'SA' . $faker->numerify('####################'),
            'emergency_contact_name' => $faker->name,
            'emergency_contact_phone' => $faker->phoneNumber,
            'emergency_contact_relationship' => $faker->randomElement(['زوج/زوجة', 'أب', 'أم', 'أخ', 'أخت']),
            'rating' => $faker->randomFloat(2, 3.8, 5.0),
        ]);

        // Create Employee
        $employeeName = 'خالد سعد الغامدي';
        $employeeAvatar = $avatarGenerator->generateSimpleAvatar($employeeName);
        $employeeUser = User::create([
            'name' => $employeeName,
            'email' => 'employee@abraj.com',
            'password' => Hash::make('employee123'),
            'role' => 'employee',
            'avatar' => $employeeAvatar,
            'email_verified_at' => now(),
        ]);

        Employee::create([
            'user_id' => $employeeUser->id,
            'name' => $employeeName,
            'email' => 'employee@abraj.com',
            'phone' => $faker->phoneNumber,
            'department' => 'الهندسة',
            'position' => $positions['employee'],
            'role' => 'employee',
            'salary' => $faker->numberBetween(6000, 9000),
            'hire_date' => $faker->dateTimeBetween('-2 years', '-3 months'),
            'contract_start' => $faker->dateTimeBetween('-2 years', '-3 months'),
            'contract_end' => $faker->dateTimeBetween('+1 year', '+3 years'),
            'birth_date' => $faker->dateTimeBetween('-40 years', '-25 years'),
            'nationality' => $faker->randomElement($nationalities),
            'marital_status' => $faker->randomElement($maritalStatuses),
            'children_count' => $faker->numberBetween(0, 3),
            'religion' => $faker->randomElement($religions),
            'sponsorship_status' => $faker->randomElement($sponsorshipStatuses),
            'category' => $faker->randomElement($categories),
            'national_id' => '1' . $faker->numerify('#########'),
            'national_id_expiry' => $faker->dateTimeBetween('+1 year', '+10 years'),
            'address' => $faker->address,
            'photo' => $employeeAvatar,
            'bank_name' => $faker->randomElement(['البنك الأهلي', 'الراجحي', 'ساب', 'البنك العربي']),
            'bank_account_number' => $faker->numerify('##############'),
            'iban' => 'SA' . $faker->numerify('####################'),
            'emergency_contact_name' => $faker->name,
            'emergency_contact_phone' => $faker->phoneNumber,
            'emergency_contact_relationship' => $faker->randomElement(['زوج/زوجة', 'أب', 'أم', 'أخ', 'أخت']),
            'rating' => $faker->randomFloat(2, 3.0, 4.5),
        ]);

        // Create Finance Manager
        $financeName = 'نوف فهد الدوسري';
        $financeAvatar = $avatarGenerator->generateSimpleAvatar($financeName);
        $financeUser = User::create([
            'name' => $financeName,
            'email' => 'finance@abraj.com',
            'password' => Hash::make('finance123'),
            'role' => 'finance',
            'avatar' => $financeAvatar,
            'email_verified_at' => now(),
        ]);

        Employee::create([
            'user_id' => $financeUser->id,
            'name' => $financeName,
            'email' => 'finance@abraj.com',
            'phone' => $faker->phoneNumber,
            'department' => 'المالية',
            'position' => $positions['finance'],
            'role' => 'finance',
            'salary' => $faker->numberBetween(8000, 12000),
            'hire_date' => $faker->dateTimeBetween('-3 years', '-6 months'),
            'contract_start' => $faker->dateTimeBetween('-3 years', '-6 months'),
            'contract_end' => $faker->dateTimeBetween('+1 year', '+3 years'),
            'birth_date' => $faker->dateTimeBetween('-45 years', '-28 years'),
            'nationality' => 'سعودي',
            'marital_status' => $faker->randomElement($maritalStatuses),
            'children_count' => $faker->numberBetween(0, 4),
            'religion' => 'مسلم',
            'sponsorship_status' => 'مواطن',
            'category' => 'إداري',
            'national_id' => '1' . $faker->numerify('#########'),
            'national_id_expiry' => $faker->dateTimeBetween('+1 year', '+10 years'),
            'address' => $faker->address,
            'photo' => $financeAvatar,
            'bank_name' => $faker->randomElement(['البنك الأهلي', 'الراجحي', 'ساب', 'البنك العربي']),
            'bank_account_number' => $faker->numerify('##############'),
            'iban' => 'SA' . $faker->numerify('####################'),
            'emergency_contact_name' => $faker->name,
            'emergency_contact_phone' => $faker->phoneNumber,
            'emergency_contact_relationship' => $faker->randomElement(['زوج/زوجة', 'أب', 'أم', 'أخ', 'أخت']),
            'rating' => $faker->randomFloat(2, 3.5, 4.8),
        ]);

        // إنشاء 195 موظف إضافي (ليصبح المجموع 200)
        for ($i = 1; $i <= 195; $i++) {
            // اختيار جنس الموظف عشوائياً
            $isMale = $faker->boolean();
            $firstName = $isMale ? $faker->randomElement($maleFirstNames) : $faker->randomElement($femaleFirstNames);
            $lastName = $faker->randomElement($lastNames);
            $fullName = $firstName . ' ' . $faker->randomElement($maleFirstNames) . ' ' . $lastName;

            // توزيع الأدوار بنسب منطقية
            $roleWeights = [
                'employee' => 60,    // 60% موظفين عاديين
                'engineer' => 15,    // 15% مهندسين
                'supervisor' => 10,  // 10% مشرفين
                'accountant' => 8,   // 8% محاسبين
                'manager' => 4,      // 4% مدراء
                'hr' => 2,          // 2% موارد بشرية
                'finance' => 1,     // 1% مالية
            ];

            $role = $this->getWeightedRandomRole($roleWeights);
            $department = $faker->randomElement($departments);

            // تخصيص القسم حسب الدور
            if ($role === 'engineer') {
                $department = $faker->randomElement(['الهندسة', 'المشاريع', 'الجودة']);
            } elseif ($role === 'accountant' || $role === 'finance') {
                $department = $faker->randomElement(['المحاسبة', 'المالية']);
            } elseif ($role === 'hr') {
                $department = 'الموارد البشرية';
            }

            // توليد صورة شخصية للموظف
            $avatar = $avatarGenerator->generateSimpleAvatar($fullName);

            // إنشاء المستخدم
            $user = User::create([
                'name' => $fullName,
                'email' => 'emp' . str_pad($i, 3, '0', STR_PAD_LEFT) . '@abraj.com',
                'password' => Hash::make('password123'),
                'role' => $role,
                'avatar' => $avatar,
                'email_verified_at' => now(),
            ]);

            // تحديد الراتب حسب الدور
            $salaryRanges = [
                'manager' => [10000, 15000],
                'supervisor' => [8000, 12000],
                'engineer' => [7000, 11000],
                'accountant' => [6000, 9000],
                'finance' => [6500, 9500],
                'hr' => [5500, 8500],
                'employee' => [4000, 7000],
            ];

            $salaryRange = $salaryRanges[$role] ?? [4000, 6000];
            $salary = $faker->numberBetween($salaryRange[0], $salaryRange[1]);

            // إنشاء سجل الموظف
            Employee::create([
                'user_id' => $user->id,
                'name' => $fullName,
                'email' => $user->email,
                'phone' => $faker->phoneNumber,
                'department' => $department,
                'position' => $positions[$role] ?? 'موظف',
                'role' => $role,
                'salary' => $salary,
                'hire_date' => $faker->dateTimeBetween('-5 years', '-1 month'),
                'contract_start' => $faker->dateTimeBetween('-5 years', '-1 month'),
                'contract_end' => $faker->dateTimeBetween('+6 months', '+3 years'),
                'birth_date' => $faker->dateTimeBetween('-60 years', '-22 years'),
                'nationality' => $faker->randomElement($nationalities),
                'marital_status' => $faker->randomElement($maritalStatuses),
                'children_count' => $faker->numberBetween(0, 6),
                'religion' => $faker->randomElement($religions),
                'sponsorship_status' => $faker->randomElement($sponsorshipStatuses),
                'category' => $faker->randomElement($categories),
                'national_id' => ($faker->randomElement(['1', '2'])) . $faker->numerify('#########'),
                'national_id_expiry' => $faker->dateTimeBetween('+6 months', '+10 years'),
                'address' => $faker->address,
                'photo' => $avatar,
                'bank_name' => $faker->randomElement(['البنك الأهلي التجاري', 'مصرف الراجحي', 'البنك السعودي البريطاني', 'البنك العربي الوطني', 'بنك الرياض', 'بنك الإنماء']),
                'bank_account_number' => $faker->numerify('##############'),
                'iban' => 'SA' . $faker->numerify('####################'),
                'emergency_contact_name' => $faker->name,
                'emergency_contact_phone' => $faker->phoneNumber,
                'emergency_contact_relationship' => $faker->randomElement(['زوج/زوجة', 'أب', 'أم', 'أخ', 'أخت', 'ابن', 'ابنة', 'صديق']),
                'rating' => $faker->randomFloat(2, 2.0, 5.0),
                'working_hours' => $faker->randomElement([8, 9, 10]),
            ]);
        }
    }

    /**
     * اختيار دور عشوائي بناء على الأوزان المحددة
     */
    private function getWeightedRandomRole($weights)
    {
        $faker = Faker::create();
        $totalWeight = array_sum($weights);
        $random = $faker->numberBetween(1, $totalWeight);

        foreach ($weights as $role => $weight) {
            $random -= $weight;
            if ($random <= 0) {
                return $role;
            }
        }

        return 'employee'; // افتراضي
    }
}

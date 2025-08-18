<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Employee;
use Carbon\Carbon;

class Arabic50EmployeesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = [
            // إدارة عليا
            ['أحمد محمد العبدالله', 'مدير عام', 'الإدارة العليا', '966501234567', 'ahmed.mohammed@abraj.com', 'active', 'management', 25000],
            ['فاطمة علي الحربي', 'نائب المدير العام', 'الإدارة العليا', '966501234568', 'fatima.ali@abraj.com', 'active', 'management', 22000],
            ['خالد عبدالرحمن السعيد', 'مدير العمليات', 'العمليات', '966501234569', 'khalid.abdulrahman@abraj.com', 'active', 'management', 20000],
            ['مريم سالم القحطاني', 'مدير الموارد البشرية', 'الموارد البشرية', '966501234570', 'mariam.salem@abraj.com', 'active', 'hr', 18000],
            ['عبدالله حسن المطيري', 'مدير المالية', 'المالية', '966501234571', 'abdullah.hassan@abraj.com', 'active', 'finance', 19000],

            // مهندسون وفنيون
            ['سعد فهد الغامدي', 'مهندس مدني أول', 'الهندسة', '966501234572', 'saad.fahad@abraj.com', 'active', 'engineer', 15000],
            ['نورا أحمد الزهراني', 'مهندسة معمارية', 'الهندسة', '966501234573', 'nora.ahmed@abraj.com', 'active', 'engineer', 14000],
            ['محمد عبدالعزيز الشمري', 'مهندس كهربائي', 'الهندسة', '966501234574', 'mohammed.abdulaziz@abraj.com', 'active', 'engineer', 13000],
            ['هند عبدالله الدوسري', 'مهندسة ميكانيكية', 'الهندسة', '966501234575', 'hind.abdullah@abraj.com', 'active', 'engineer', 13500],
            ['يوسف عمر الحكمي', 'مهندس صحي', 'الهندسة', '966501234576', 'youssef.omar@abraj.com', 'active', 'engineer', 12000],
            ['رانيا حمد الشهري', 'مهندسة إنشائية', 'الهندسة', '966501234577', 'rania.hamad@abraj.com', 'active', 'engineer', 14500],
            ['طارق سليمان العتيبي', 'فني كهربائي أول', 'الصيانة', '966501234578', 'tareq.sulaiman@abraj.com', 'active', 'technician', 8000],
            ['سارة عادل المالكي', 'فنية حاسوب', 'تقنية المعلومات', '966501234579', 'sara.adel@abraj.com', 'active', 'technician', 7500],
            ['ماجد فيصل الرشيد', 'فني ميكانيكي', 'الصيانة', '966501234580', 'majed.faisal@abraj.com', 'active', 'technician', 7000],
            ['لينا راشد الخالدي', 'فنية مختبر', 'مراقبة الجودة', '966501234581', 'lina.rashed@abraj.com', 'active', 'technician', 6500],

            // عمال ومشغلون
            ['عبدالرحمن ناصر العنزي', 'مشغل حفار', 'العمليات', '966501234582', 'abdulrahman.nasser@abraj.com', 'active', 'operator', 5500],
            ['حنان عبدالمحسن الفهد', 'مشغلة رافعة', 'العمليات', '966501234583', 'hanan.abdulmohsen@abraj.com', 'active', 'operator', 5200],
            ['بندر جمال الحارثي', 'سائق شاحنة ثقيلة', 'النقل', '966501234584', 'bandar.jamal@abraj.com', 'active', 'driver', 4800],
            ['أسماء تركي الشيباني', 'عاملة نظافة', 'الخدمات', '966501234585', 'asma.turki@abraj.com', 'active', 'cleaner', 3500],
            ['علي محمود الثقفي', 'عامل بناء', 'الإنشاءات', '966501234586', 'ali.mahmoud@abraj.com', 'active', 'construction', 4000],
            ['زينب فواز العسيري', 'عاملة تموين', 'الخدمات', '966501234587', 'zeinab.fawaz@abraj.com', 'active', 'catering', 3800],
            ['حسام عبدالله النعيمي', 'عامل حديد', 'الإنشاءات', '966501234588', 'hussam.abdullah@abraj.com', 'active', 'construction', 4200],
            ['منيرة صالح الطيار', 'عاملة مخازن', 'المخازن', '966501234589', 'munira.saleh@abraj.com', 'active', 'warehouse', 3600],
            ['راكان بدر الصويان', 'عامل لحام', 'الإنشاءات', '966501234590', 'rakan.badr@abraj.com', 'active', 'construction', 4500],
            ['جميلة حسين الأحمدي', 'عاملة تنظيف معدات', 'الصيانة', '966501234591', 'jamila.hussein@abraj.com', 'active', 'cleaner', 3700],

            // موظفو مكاتب وإداريون
            ['وليد عثمان البكري', 'محاسب أول', 'المالية', '966501234592', 'waleed.othman@abraj.com', 'active', 'accountant', 9000],
            ['دعاء محمد الجهني', 'محاسبة', 'المالية', '966501234593', 'duaa.mohammed@abraj.com', 'active', 'accountant', 7000],
            ['إبراهيم خالد المري', 'منسق مشاريع', 'إدارة المشاريع', '966501234594', 'ibrahim.khalid@abraj.com', 'active', 'coordinator', 8500],
            ['ريم عبدالله الرحيلي', 'سكرتيرة تنفيذية', 'الإدارة العليا', '966501234595', 'reem.abdullah@abraj.com', 'active', 'secretary', 6000],
            ['نايف سعود القباني', 'أخصائي موارد بشرية', 'الموارد البشرية', '966501234596', 'naif.saud@abraj.com', 'active', 'hr', 8000],
            ['هدى عبدالعزيز الخثعمي', 'أخصائية تدريب', 'الموارد البشرية', '966501234597', 'huda.abdulaziz@abraj.com', 'active', 'hr', 7500],
            ['مشعل راشد الأسمري', 'مسؤول أمن وسلامة', 'الأمن والسلامة', '966501234598', 'mishaal.rashed@abraj.com', 'active', 'safety', 6800],
            ['سمية عادل البلوي', 'موظفة استقبال', 'الاستقبال', '966501234599', 'sumaya.adel@abraj.com', 'active', 'receptionist', 4500],
            ['عامر حمد الفيفي', 'مسؤول مشتريات', 'المشتريات', '966501234600', 'amer.hamad@abraj.com', 'active', 'procurement', 7200],
            ['نجلاء توفيق الشامي', 'مسؤولة جودة', 'مراقبة الجودة', '966501234601', 'najlaa.tawfiq@abraj.com', 'active', 'quality', 6500],

            // مختصون وخبراء
            ['باسم عبدالحكيم الألمعي', 'مستشار قانوني', 'الشؤون القانونية', '966501234602', 'basem.abdulhakeem@abraj.com', 'active', 'legal', 12000],
            ['غادة نواف الخريجي', 'مختصة تسويق', 'التسويق', '966501234603', 'ghada.nawaf@abraj.com', 'active', 'marketing', 8500],
            ['عصام منصور السلمي', 'أخصائي تقنية معلومات', 'تقنية المعلومات', '966501234604', 'essam.mansour@abraj.com', 'active', 'it', 9500],
            ['ليلى فهد الغامدي', 'مطورة برمجيات', 'تقنية المعلومات', '966501234605', 'layla.fahad@abraj.com', 'active', 'it', 10000],
            ['حاتم عبدالله الكندي', 'مختص علاقات عامة', 'العلاقات العامة', '966501234606', 'hatem.abdullah@abraj.com', 'active', 'public_relations', 7000],
            ['فجر صلاح الحازمي', 'مختصة ترجمة', 'الخدمات اللغوية', '966501234607', 'fajar.salah@abraj.com', 'active', 'translator', 6000],
            ['عبدالمجيد رامي الغانمي', 'خبير استشارات', 'الاستشارات', '966501234608', 'abdulmajeed.ramy@abraj.com', 'active', 'consultant', 11000],
            ['رغد ماهر الشهراني', 'مديرة علاقات العملاء', 'خدمة العملاء', '966501234609', 'raghad.maher@abraj.com', 'active', 'customer_service', 7800],
            ['شاهر عمار النجار', 'مشرف عمليات ميدانية', 'العمليات الميدانية', '966501234610', 'shaher.ammar@abraj.com', 'active', 'field_supervisor', 8200],
            ['هالة سمير الجابر', 'مختصة تطوير أعمال', 'تطوير الأعمال', '966501234611', 'hala.samir@abraj.com', 'active', 'business_development', 9000],

            // عمال متخصصون
            ['زياد أحمد الرويلي', 'حارس أمن', 'الأمن', '966501234612', 'ziyad.ahmed@abraj.com', 'active', 'security', 4000],
            ['نوال فريد المرشدي', 'طباخة', 'الخدمات', '966501234613', 'nawal.fareed@abraj.com', 'active', 'chef', 3800],
            ['مازن عدنان الراشد', 'كهربائي صيانة', 'الصيانة', '966501234614', 'mazen.adnan@abraj.com', 'active', 'electrician', 5000],
            ['جواهر سعد العريفي', 'ممرضة إسعافات أولية', 'الخدمات الطبية', '966501234615', 'jawaher.saad@abraj.com', 'active', 'nurse', 5500],
            ['سلطان محمد البقمي', 'سباك', 'الصيانة', '966501234616', 'sultan.mohammed@abraj.com', 'active', 'plumber', 4500],
            ['شروق عبدالهادي الصاعدي', 'مصورة فوتوغرافية', 'الإعلام', '966501234617', 'shuruq.abdulhadi@abraj.com', 'active', 'photographer', 5200],
            ['ثامر جاسم الكثيري', 'نجار', 'الإنشاءات', '966501234618', 'thamer.jasem@abraj.com', 'active', 'carpenter', 4800],
            ['منى خليل الشريف', 'مصممة جرافيك', 'التصميم', '966501234619', 'muna.khalil@abraj.com', 'active', 'designer', 6500],
            ['راجح عبدالحليم الوادعي', 'مساح أراضي', 'المساحة', '966501234620', 'rajeh.abdulhaleem@abraj.com', 'active', 'surveyor', 7000],
            ['أمجاد وائل المحمدي', 'مترجمة فورية', 'الخدمات اللغوية', '966501234621', 'amjad.wael@abraj.com', 'active', 'interpreter', 6800]
        ];

        foreach ($employees as $index => $employeeData) {
            Employee::create([
                'name' => $employeeData[0],
                'position' => $employeeData[1],
                'department' => $employeeData[2],
                'phone' => $employeeData[3],
                'email' => $employeeData[4],
                'status' => $employeeData[5],
                'role' => $employeeData[6],
                'salary' => $employeeData[7],
                'hire_date' => Carbon::now()->subDays(rand(30, 1095)), // تاريخ توظيف عشوائي في آخر 3 سنوات
                'category' => $this->getEmployeeCategory($employeeData[6]),
                'birth_date' => Carbon::now()->subYears(rand(22, 55))->subDays(rand(1, 365)),
                'nationality' => 'سعودي',
                'marital_status' => rand(0, 1) ? 'متزوج' : 'أعزب',
                'children_count' => rand(0, 4),
                'religion' => 'مسلم',
                'contract_start' => Carbon::now()->subDays(rand(30, 1095)),
                'contract_end' => Carbon::now()->addYears(rand(1, 3)),
                'working_hours' => 8, // 8 ساعات يومياً
                'emergency_contact_name' => 'جهة اتصال طوارئ',
                'emergency_contact_phone' => '966505' . rand(100000, 999999),
                'emergency_contact_relationship' => rand(0, 1) ? 'والد' : 'والدة',
                'national_id' => '1' . rand(100000000, 999999999), // رقم هوية عشوائي
                'driving_license_number' => rand(10000000, 99999999), // رقم رخصة قيادة عشوائي
            ]);
        }

        $this->command->info('تم إنشاء 50 موظف جديد بأسماء عربية وأدوار مختلفة بنجاح!');
    }

    private function getEmployeeCategory($role)
    {
        $categories = [
            'management' => 'إداري',
            'engineer' => 'مهندس',
            'technician' => 'فني',
            'operator' => 'مشغل',
            'driver' => 'سائق',
            'construction' => 'عامل إنشاءات',
            'cleaner' => 'عامل نظافة',
            'warehouse' => 'عامل مخازن',
            'catering' => 'خدمات تموين',
            'accountant' => 'محاسب',
            'coordinator' => 'منسق',
            'secretary' => 'سكرتير',
            'hr' => 'موارد بشرية',
            'safety' => 'أمن وسلامة',
            'receptionist' => 'استقبال',
            'procurement' => 'مشتريات',
            'quality' => 'مراقبة جودة',
            'legal' => 'قانوني',
            'marketing' => 'تسويق',
            'it' => 'تقنية معلومات',
            'public_relations' => 'علاقات عامة',
            'translator' => 'مترجم',
            'consultant' => 'استشاري',
            'customer_service' => 'خدمة عملاء',
            'field_supervisor' => 'مشرف ميداني',
            'business_development' => 'تطوير أعمال',
            'security' => 'أمن',
            'chef' => 'طباخ',
            'electrician' => 'كهربائي',
            'nurse' => 'ممرض',
            'plumber' => 'سباك',
            'photographer' => 'مصور',
            'carpenter' => 'نجار',
            'designer' => 'مصمم',
            'surveyor' => 'مساح',
            'interpreter' => 'مترجم فوري',
            'finance' => 'مالية'
        ];

        return $categories[$role] ?? 'عام';
    }
}
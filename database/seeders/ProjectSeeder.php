<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;
use Carbon\Carbon;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = [
            [
                'name' => 'مجمع الأبراج السكني',
                'client_name' => 'شركة العقارات المتطورة',
                'project_manager' => 'م. أحمد العتيبي',
                'location' => 'الرياض - حي النرجس',
                'start_date' => Carbon::now()->subMonths(8)->format('Y-m-d'),
                'end_date' => Carbon::now()->addMonths(4)->format('Y-m-d'),
                'budget' => 15000000.00,
                'status' => 'active',
                'progress' => 65,
                'description' => 'مشروع سكني متكامل يتكون من 5 أبراج سكنية بارتفاع 15 طابق لكل برج، يشمل مرافق ترفيهية ومواقف سيارات تحت الأرض وحدائق للأطفال.'
            ],
            [
                'name' => 'مول النخيل التجاري',
                'client_name' => 'مجموعة النخيل للاستثمار',
                'project_manager' => 'م. سارة الحربي',
                'location' => 'جدة - طريق الملك عبدالعزيز',
                'start_date' => Carbon::now()->subMonths(12)->format('Y-m-d'),
                'end_date' => Carbon::now()->subMonths(2)->format('Y-m-d'),
                'budget' => 25000000.00,
                'status' => 'completed',
                'progress' => 100,
                'description' => 'مجمع تجاري ضخم يضم أكثر من 300 محل تجاري، صالات سينما، مطاعم ومقاهي، ومنطقة ألعاب للأطفال على مساحة 50,000 متر مربع.'
            ],
            [
                'name' => 'مستشفى الملك فهد الجديد',
                'client_name' => 'وزارة الصحة',
                'project_manager' => 'م. محمد القحطاني',
                'location' => 'الدمام - حي الشاطئ',
                'start_date' => Carbon::now()->addMonth()->format('Y-m-d'),
                'end_date' => Carbon::now()->addMonths(18)->format('Y-m-d'),
                'budget' => 45000000.00,
                'status' => 'planning',
                'progress' => 15,
                'description' => 'مستشفى حديث بسعة 400 سرير، يشمل أقسام الطوارئ، العمليات، العناية المركزة، والعيادات الخارجية مع أحدث التقنيات الطبية.'
            ],
            [
                'name' => 'جسر الملك سلمان',
                'client_name' => 'وزارة النقل والخدمات اللوجستية',
                'project_manager' => 'م. عبدالرحمن الدوسري',
                'location' => 'الرياض - طريق الملك خالد',
                'start_date' => Carbon::now()->subMonths(6)->format('Y-m-d'),
                'end_date' => Carbon::now()->addMonths(8)->format('Y-m-d'),
                'budget' => 8500000.00,
                'status' => 'active',
                'progress' => 42,
                'description' => 'جسر علوي بطول 2.5 كيلومتر لتخفيف الازدحام المروري، يشمل 4 حارات في كل اتجاه ومسارات للمشاة والدراجات.'
            ],
            [
                'name' => 'مصنع الأسمنت الحديث',
                'client_name' => 'الشركة السعودية للأسمنت',
                'project_manager' => 'م. نوف الشهراني',
                'location' => 'الطائف - المنطقة الصناعية',
                'start_date' => Carbon::now()->subMonths(10)->format('Y-m-d'),
                'end_date' => Carbon::now()->subMonth()->format('Y-m-d'),
                'budget' => 32000000.00,
                'status' => 'on_hold',
                'progress' => 78,
                'description' => 'مصنع أسمنت بطاقة إنتاجية 2000 طن يومياً، يستخدم تقنيات صديقة للبيئة ونظم إدارة النفايات المتقدمة.'
            ],
            [
                'name' => 'منتجع البحر الأحمر',
                'client_name' => 'شركة البحر الأحمر للتطوير',
                'project_manager' => 'م. خالد الغامدي',
                'location' => 'تبوك - ساحل البحر الأحمر',
                'start_date' => Carbon::now()->subMonths(4)->format('Y-m-d'),
                'end_date' => Carbon::now()->addMonths(14)->format('Y-m-d'),
                'budget' => 55000000.00,
                'status' => 'active',
                'progress' => 28,
                'description' => 'منتجع سياحي فاخر على البحر الأحمر يضم 200 فيلا و 3 فنادق 5 نجوم ومرافق ترفيهية ومائية ومارينا لليخوت.'
            ],
            [
                'name' => 'مدرسة المستقبل النموذجية',
                'client_name' => 'وزارة التعليم',
                'project_manager' => 'م. هند المطيري',
                'location' => 'المدينة المنورة - حي الأزهري',
                'start_date' => Carbon::now()->subMonths(3)->format('Y-m-d'),
                'end_date' => Carbon::now()->addMonths(9)->format('Y-m-d'),
                'budget' => 12000000.00,
                'status' => 'active',
                'progress' => 35,
                'description' => 'مدرسة نموذجية للبنات بسعة 1000 طالبة، تشمل مختبرات علمية حديثة، مكتبة رقمية، صالة رياضية، ومسرح.'
            ],
            [
                'name' => 'محطة تحلية المياه',
                'client_name' => 'شركة المياه الوطنية',
                'project_manager' => 'م. عبدالله البقمي',
                'location' => 'ينبع - المنطقة الصناعية',
                'start_date' => Carbon::now()->addMonths(2)->format('Y-m-d'),
                'end_date' => Carbon::now()->addMonths(20)->format('Y-m-d'),
                'budget' => 38000000.00,
                'status' => 'planning',
                'progress' => 8,
                'description' => 'محطة تحلية مياه بحر بطاقة إنتاجية 100,000 متر مكعب يومياً باستخدام تقنية التناضح العكسي المتطورة.'
            ],
            [
                'name' => 'مطار الأمير محمد بن سلمان',
                'client_name' => 'الهيئة العامة للطيران المدني',
                'project_manager' => 'م. فهد العنزي',
                'location' => 'نيوم - تبوك',
                'start_date' => Carbon::now()->subMonths(18)->format('Y-m-d'),
                'end_date' => Carbon::now()->addMonths(6)->format('Y-m-d'),
                'budget' => 85000000.00,
                'status' => 'active',
                'progress' => 82,
                'description' => 'مطار دولي حديث بسعة 10 مليون مسافر سنوياً، يشمل مدرجين ومبنى ركاب بتصميم عصري ومرافق تجارية متقدمة.'
            ],
            [
                'name' => 'حديقة الملك سلمان المركزية',
                'client_name' => 'أمانة منطقة الرياض',
                'project_manager' => 'م. ريم الخالدي',
                'location' => 'الرياض - وسط المدينة',
                'start_date' => Carbon::now()->subMonths(5)->format('Y-m-d'),
                'end_date' => Carbon::now()->addMonths(7)->format('Y-m-d'),
                'budget' => 18500000.00,
                'status' => 'active',
                'progress' => 48,
                'description' => 'حديقة مركزية على مساحة 30 هكتار تشمل بحيرة صناعية، مسارات للمشي والجري، ملاعب رياضية، ومدرج مكشوف.'
            ]
        ];

        foreach ($projects as $project) {
            Project::create($project);
        }
    }
}

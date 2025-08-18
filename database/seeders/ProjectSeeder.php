<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\Employee;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = Employee::all();
        $manager = $employees->first();

        // نوع الضمان البنكي يجب أن يكون performance, advance_payment, maintenance, other
        $bankGuaranteeTypes = ['performance', 'advance_payment', 'maintenance', 'other'];

        $projects = [
            [
                'name' => 'مشروع تطوير الطريق الدائري',
                'description' => 'إنشاء وتطوير الطريق الدائري الجديد مع الجسور والأنفاق',
                'status' => Project::STATUS_ACTIVE,
                'start_date' => now()->subMonths(2),
                'end_date' => now()->addYears(2),
                'budget' => 75000000,
                'bank_guarantee_amount' => 7500000,
                'bank_guarantee_type' => 'performance',
                'client_name' => 'وزارة النقل',
                'location' => 'الرياض - المنطقة الشرقية',
                'project_number' => 'PRJ-2025-001',
                'government_entity' => 'وزارة النقل',
                'consulting_office' => 'مكتب الاستشارات الهندسية المتحدة',
                'project_scope' => 'إنشاء طريق دائري بطول 45 كم مع 5 جسور و3 أنفاق',
                'project_manager_id' => $manager->id,
                'project_manager' => $manager->name,
                'progress' => 25
            ],
            [
                'name' => 'مشروع إنشاء مجمع تعليمي',
                'description' => 'بناء مجمع تعليمي متكامل يشمل مدارس ومرافق رياضية',
                'status' => Project::STATUS_PLANNING,
                'start_date' => now()->addMonth(),
                'end_date' => now()->addMonths(18),
                'budget' => 45000000,
                'bank_guarantee_amount' => 4500000,
                'bank_guarantee_type' => 'advance_payment',
                'client_name' => 'وزارة التعليم',
                'location' => 'جدة - حي النزهة',
                'project_number' => 'PRJ-2025-002',
                'government_entity' => 'وزارة التعليم',
                'consulting_office' => 'دار الهندسة للاستشارات',
                'project_scope' => 'مجمع تعليمي على مساحة 50,000 متر مربع',
                'project_manager_id' => $manager->id,
                'project_manager' => $manager->name,
                'progress' => 0
            ],
            [
                'name' => 'مشروع توسعة مستشفى الملك فهد',
                'description' => 'توسعة وتطوير مستشفى الملك فهد مع إضافة أقسام جديدة',
                'status' => Project::STATUS_ACTIVE,
                'start_date' => now()->subMonths(6),
                'end_date' => now()->addYear(),
                'budget' => 120000000,
                'bank_guarantee_amount' => 12000000,
                'bank_guarantee_type' => 'performance',
                'client_name' => 'وزارة الصحة',
                'location' => 'الدمام - حي الشاطئ',
                'project_number' => 'PRJ-2025-003',
                'government_entity' => 'وزارة الصحة',
                'consulting_office' => 'مكتب التصميم العالمي',
                'project_scope' => 'إضافة مبنى جديد بسعة 200 سرير وقسم للطوارئ',
                'project_manager_id' => $manager->id,
                'project_manager' => $manager->name,
                'progress' => 45
            ],
            [
                'name' => 'مشروع إنشاء مجمع سكني',
                'description' => 'إنشاء مجمع سكني متكامل للموظفين الحكوميين',
                'status' => Project::STATUS_ON_HOLD,
                'start_date' => now()->subMonths(3),
                'end_date' => now()->addMonths(15),
                'budget' => 85000000,
                'bank_guarantee_amount' => 8500000,
                'bank_guarantee_type' => 'maintenance',
                'client_name' => 'وزارة الإسكان',
                'location' => 'الخبر - حي الراكة',
                'project_number' => 'PRJ-2025-004',
                'government_entity' => 'وزارة الإسكان',
                'consulting_office' => 'المكتب الاستشاري السعودي',
                'project_scope' => 'مجمع سكني يضم 200 وحدة سكنية مع المرافق',
                'project_manager_id' => $manager->id,
                'project_manager' => $manager->name,
                'progress' => 30
            ],
            [
                'name' => 'مشروع تطوير محطة معالجة المياه',
                'description' => 'تطوير وتوسعة محطة معالجة المياه الرئيسية',
                'status' => Project::STATUS_ACTIVE,
                'start_date' => now()->subMonths(4),
                'end_date' => now()->addMonths(8),
                'budget' => 65000000,
                'bank_guarantee_amount' => 6500000,
                'bank_guarantee_type' => 'performance',
                'client_name' => 'وزارة البيئة والمياه والزراعة',
                'location' => 'أبها - المنطقة الصناعية',
                'project_number' => 'PRJ-2025-005',
                'government_entity' => 'وزارة البيئة والمياه والزراعة',
                'consulting_office' => 'مكتب الخبرات البيئية',
                'project_scope' => 'رفع طاقة المحطة إلى 100,000 متر مكعب يومياً',
                'project_manager_id' => $manager->id,
                'project_manager' => $manager->name,
                'progress' => 60
            ],
            [
                'name' => 'مشروع بناء مركز ثقافي',
                'description' => 'إنشاء مركز ثقافي متعدد الاستخدامات',
                'status' => Project::STATUS_COMPLETED,
                'start_date' => now()->subYear(),
                'end_date' => now()->subMonth(),
                'budget' => 35000000,
                'bank_guarantee_amount' => 3500000,
                'bank_guarantee_type' => 'performance',
                'client_name' => 'وزارة الثقافة',
                'location' => 'المدينة المنورة - المنطقة المركزية',
                'project_number' => 'PRJ-2024-006',
                'government_entity' => 'وزارة الثقافة',
                'consulting_office' => 'دار العمران للاستشارات',
                'project_scope' => 'مركز ثقافي يضم مسرح ومكتبة ومعارض',
                'project_manager_id' => $manager->id,
                'project_manager' => $manager->name,
                'progress' => 100
            ],
            [
                'name' => 'مشروع تطوير المنطقة الصناعية',
                'description' => 'تطوير وتحديث البنية التحتية للمنطقة الصناعية',
                'status' => Project::STATUS_ACTIVE,
                'start_date' => now()->subMonths(8),
                'end_date' => now()->addMonths(16),
                'budget' => 95000000,
                'bank_guarantee_amount' => 9500000,
                'bank_guarantee_type' => 'maintenance',
                'client_name' => 'الهيئة السعودية للمدن الصناعية',
                'location' => 'الرياض - المدينة الصناعية الثانية',
                'project_number' => 'PRJ-2024-007',
                'government_entity' => 'وزارة الصناعة',
                'consulting_office' => 'مكتب التطوير الصناعي',
                'project_scope' => 'تطوير شبكات الطرق والكهرباء والمياه',
                'project_manager_id' => $manager->id,
                'project_manager' => $manager->name,
                'progress' => 55
            ],
            [
                'name' => 'مشروع إنشاء مركز رياضي',
                'description' => 'إنشاء مركز رياضي متكامل مع ملاعب متعددة',
                'status' => Project::STATUS_PLANNING,
                'start_date' => now()->addMonths(2),
                'end_date' => now()->addYear(),
                'budget' => 55000000,
                'bank_guarantee_amount' => 5500000,
                'bank_guarantee_type' => 'advance_payment',
                'client_name' => 'وزارة الرياضة',
                'location' => 'جدة - حي الصفا',
                'project_number' => 'PRJ-2025-008',
                'government_entity' => 'وزارة الرياضة',
                'consulting_office' => 'مكتب الإنشاءات الرياضية',
                'project_scope' => 'مركز رياضي يشمل ملعب كرة قدم وصالات متعددة',
                'project_manager_id' => $manager->id,
                'project_manager' => $manager->name,
                'progress' => 0
            ],
            [
                'name' => 'مشروع تطوير المطار الإقليمي',
                'description' => 'توسعة وتطوير مبنى المطار الإقليمي',
                'status' => Project::STATUS_ACTIVE,
                'start_date' => now()->subMonths(5),
                'end_date' => now()->addYears(2),
                'budget' => 250000000,
                'bank_guarantee_amount' => 25000000,
                'bank_guarantee_type' => 'performance',
                'client_name' => 'الهيئة العامة للطيران المدني',
                'location' => 'تبوك',
                'project_number' => 'PRJ-2025-009',
                'government_entity' => 'الهيئة العامة للطيران المدني',
                'consulting_office' => 'المكتب الهندسي العالمي',
                'project_scope' => 'توسعة المطار لاستيعاب 5 مليون مسافر سنوياً',
                'project_manager_id' => $manager->id,
                'project_manager' => $manager->name,
                'progress' => 35
            ],
            [
                'name' => 'مشروع إنشاء مجمع تجاري',
                'description' => 'إنشاء مجمع تجاري حديث متعدد الاستخدامات',
                'status' => Project::STATUS_ON_HOLD,
                'start_date' => now()->subMonths(1),
                'end_date' => now()->addMonths(14),
                'budget' => 180000000,
                'bank_guarantee_amount' => 18000000,
                'bank_guarantee_type' => 'maintenance',
                'client_name' => 'أمانة منطقة الرياض',
                'location' => 'الرياض - طريق الملك فهد',
                'project_number' => 'PRJ-2025-010',
                'government_entity' => 'أمانة منطقة الرياض',
                'consulting_office' => 'مكتب التصميم المعماري',
                'project_scope' => 'مجمع تجاري على مساحة 100,000 متر مربع',
                'project_manager_id' => $manager->id,
                'project_manager' => $manager->name,
                'progress' => 15
            ]
        ];

        foreach ($projects as $project) {
            Project::create($project);
        }
    }
}

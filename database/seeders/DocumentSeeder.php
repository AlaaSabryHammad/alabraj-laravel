<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Document;

class DocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $documents = [
            [
                'title' => 'عقد مشروع أبراج الرياض التجارية',
                'type' => 'عقد',
                'file_path' => '/documents/contract_riyadh_towers.pdf',
                'file_size' => 2048000,
                'uploaded_by' => 'أحمد محمد الأحمد',
                'description' => 'عقد إنشاء مجمع أبراج الرياض التجارية مع شركة التطوير العقاري',
                'tags' => ['مشروع', 'عقد', 'الرياض', 'تجاري'],
                'expiry_date' => '2025-12-31'
            ],
            [
                'title' => 'ترخيص البناء - مشروع جدة الساحلية',
                'type' => 'ترخيص',
                'file_path' => '/documents/building_permit_jeddah.pdf',
                'file_size' => 1536000,
                'uploaded_by' => 'فاطمة علي السالم',
                'description' => 'ترخيص البناء الصادر من أمانة جدة لمشروع المجمع الساحلي',
                'tags' => ['ترخيص', 'جدة', 'ساحلي', 'أمانة'],
                'expiry_date' => '2025-06-15'
            ],
            [
                'title' => 'شهادة السلامة للمعدات الثقيلة',
                'type' => 'شهادة',
                'file_path' => '/documents/safety_certificate_equipment.pdf',
                'file_size' => 890000,
                'uploaded_by' => 'خالد عبدالله الخالد',
                'description' => 'شهادة السلامة والفحص الدوري للمعدات الثقيلة والرافعات',
                'tags' => ['سلامة', 'معدات', 'فحص', 'شهادة'],
                'expiry_date' => '2025-03-20'
            ],
            [
                'title' => 'بوليصة التأمين الشاملة للشركة',
                'type' => 'تأمين',
                'file_path' => '/documents/insurance_policy_company.pdf',
                'file_size' => 1200000,
                'uploaded_by' => 'نورا سعد الدوسري',
                'description' => 'بوليصة التأمين الشاملة على أعمال المقاولات والمعدات',
                'tags' => ['تأمين', 'شامل', 'مقاولات', 'حماية'],
                'expiry_date' => '2025-09-10'
            ],
            [
                'title' => 'فاتورة شراء الحفار الجديد',
                'type' => 'فاتورة',
                'file_path' => '/documents/invoice_excavator_new.pdf',
                'file_size' => 678000,
                'uploaded_by' => 'محمد سالم القحطاني',
                'description' => 'فاتورة شراء حفار كاتربيلر 320D من وكيل المعدات',
                'tags' => ['فاتورة', 'حفار', 'كاتربيلر', 'شراء'],
                'expiry_date' => null
            ],
            [
                'title' => 'تقرير التقدم الشهري - يناير 2025',
                'type' => 'تقرير',
                'file_path' => '/documents/monthly_progress_jan2025.pdf',
                'file_size' => 3200000,
                'uploaded_by' => 'أحمد محمد الأحمد',
                'description' => 'تقرير شامل عن تقدم جميع المشاريع خلال شهر يناير 2025',
                'tags' => ['تقرير', 'شهري', 'تقدم', 'يناير'],
                'expiry_date' => null
            ]
        ];

        foreach ($documents as $document) {
            Document::create($document);
        }
    }
}

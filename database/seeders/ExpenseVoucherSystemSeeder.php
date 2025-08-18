<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ExpenseCategory;
use App\Models\ExpenseEntity;

class ExpenseVoucherSystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إنشاء فئات الصرف
        $expenseCategories = [
            ['name' => 'رواتب ومكافآت', 'code' => 'SAL', 'description' => 'رواتب الموظفين والمكافآت'],
            ['name' => 'صيانة المعدات', 'code' => 'MNT', 'description' => 'صيانة وإصلاح المعدات والآليات'],
            ['name' => 'وقود ومواد التشغيل', 'code' => 'FUEL', 'description' => 'الوقود والزيوت ومواد التشغيل'],
            ['name' => 'مواد البناء', 'code' => 'MAT', 'description' => 'مواد البناء والإنشاءات'],
            ['name' => 'خدمات مقاولين', 'code' => 'CON', 'description' => 'خدمات المقاولين من الباطن'],
            ['name' => 'نقل ومواصلات', 'code' => 'TRP', 'description' => 'خدمات النقل والمواصلات'],
            ['name' => 'اتصالات وإنترنت', 'code' => 'TEL', 'description' => 'خدمات الاتصالات والإنترنت'],
            ['name' => 'كهرباء ومياه', 'code' => 'UTL', 'description' => 'فواتير الكهرباء والمياه'],
            ['name' => 'تأمينات', 'code' => 'INS', 'description' => 'أقساط التأمين للمعدات والمشاريع'],
            ['name' => 'مصروفات إدارية', 'code' => 'ADM', 'description' => 'المصروفات الإدارية العامة'],
            ['name' => 'تدريب وتطوير', 'code' => 'TRN', 'description' => 'تدريب وتطوير الموظفين'],
            ['name' => 'رسوم حكومية', 'code' => 'GOV', 'description' => 'الرسوم والضرائب الحكومية'],
            ['name' => 'أمن وسلامة', 'code' => 'SAF', 'description' => 'متطلبات الأمن والسلامة'],
            ['name' => 'تقنية معلومات', 'code' => 'IT', 'description' => 'خدمات ومعدات تقنية المعلومات'],
            ['name' => 'تسويق وإعلان', 'code' => 'MKT', 'description' => 'أنشطة التسويق والإعلان']
        ];

        foreach ($expenseCategories as $category) {
            ExpenseCategory::updateOrCreate(
                ['code' => $category['code']], 
                $category + ['is_active' => true]
            );
        }

        // إنشاء جهات الصرف
        $expenseEntities = [
            // موردين
            ['name' => 'شركة البناء المتطور', 'type' => 'supplier', 'contact_person' => 'أحمد محمد', 'phone' => '966501234567', 'email' => 'ahmed@build.com'],
            ['name' => 'مؤسسة المواد الإنشائية', 'type' => 'supplier', 'contact_person' => 'سعد العبدالله', 'phone' => '966501234568', 'email' => 'saad@materials.com'],
            ['name' => 'شركة الوقود الذهبي', 'type' => 'supplier', 'contact_person' => 'خالد الأحمد', 'phone' => '966501234569', 'email' => 'khalid@fuel.com'],
            ['name' => 'مؤسسة قطع الغيار', 'type' => 'supplier', 'contact_person' => 'محمد الحارثي', 'phone' => '966501234570', 'email' => 'mohammed@parts.com'],
            
            // مقاولين
            ['name' => 'مقاولون الشرق', 'type' => 'contractor', 'contact_person' => 'فيصل الشمري', 'phone' => '966501234571', 'email' => 'faisal@east.com'],
            ['name' => 'شركة البناء السريع', 'type' => 'contractor', 'contact_person' => 'عبدالرحمن الغامدي', 'phone' => '966501234572', 'email' => 'abdulrahman@quick.com'],
            ['name' => 'مقاولو الأساسات', 'type' => 'contractor', 'contact_person' => 'نايف القحطاني', 'phone' => '966501234573', 'email' => 'naif@foundation.com'],
            
            // جهات حكومية
            ['name' => 'أمانة المنطقة الشرقية', 'type' => 'government', 'contact_person' => 'إدارة التراخيص', 'phone' => '966138001000', 'email' => 'licenses@eastern.gov.sa'],
            ['name' => 'هيئة الزكاة والضريبة', 'type' => 'government', 'contact_person' => 'قسم المنشآت', 'phone' => '966112901000', 'email' => 'support@zatca.gov.sa'],
            ['name' => 'المؤسسة العامة للتأمينات', 'type' => 'government', 'contact_person' => 'قسم الاشتراكات', 'phone' => '966112903000', 'email' => 'subscriptions@gosi.gov.sa'],
            
            // بنوك
            ['name' => 'البنك الأهلي السعودي', 'type' => 'bank', 'contact_person' => 'مدير الحسابات التجارية', 'phone' => '966920001000', 'email' => 'business@alahli.com'],
            ['name' => 'بنك الرياض', 'type' => 'bank', 'contact_person' => 'خدمة الشركات', 'phone' => '966920003000', 'email' => 'corporate@riyadbank.com'],
            
            // أخرى
            ['name' => 'شركة الكهرباء السعودية', 'type' => 'other', 'contact_person' => 'خدمة العملاء', 'phone' => '966920001100', 'email' => 'customer@se.com.sa'],
            ['name' => 'شركة الاتصالات السعودية', 'type' => 'other', 'contact_person' => 'قسم الشركات', 'phone' => '966900200200', 'email' => 'corporate@stc.com.sa'],
            ['name' => 'محطة وقود أرامكو', 'type' => 'supplier', 'contact_person' => 'مدير المحطة', 'phone' => '966138881000', 'email' => 'station@aramco.com']
        ];

        foreach ($expenseEntities as $entity) {
            ExpenseEntity::create($entity + [
                'status' => 'active',
                'address' => 'المملكة العربية السعودية',
                'tax_number' => '3' . rand(10000000, 99999999) . '03',
                'commercial_record' => '1010' . rand(100000, 999999)
            ]);
        }

        $this->command->info('تم إنشاء فئات الصرف وجهات الصرف بنجاح!');
    }
}
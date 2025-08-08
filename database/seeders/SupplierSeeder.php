<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Supplier;
use Faker\Factory as Faker;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('ar_SA');

        // Predefined suppliers data for construction industry
        $suppliersData = [
            // Building Materials Suppliers
            [
                'name' => 'أحمد محمد الراجحي',
                'company_name' => 'مؤسسة الراجحي لمواد البناء',
                'category' => 'building_materials',
                'phone' => '011-4567890',
                'phone_2' => '0555123456',
                'email' => 'info@alrajhi-building.com',
                'address' => 'شارع الملك فهد، حي الملز',
                'city' => 'الرياض',
                'country' => 'المملكة العربية السعودية',
                'tax_number' => '300123456789003',
                'cr_number' => '1010123456',
                'contact_person' => 'أحمد الراجحي',
                'contact_person_phone' => '0555123456',
                'payment_terms' => 'آجل 30 يوم',
                'credit_limit' => 500000.00,
                'status' => 'نشط',
                'notes' => 'مورد موثوق لجميع أنواع مواد البناء والخرسانة'
            ],
            [
                'name' => 'فهد عبدالعزيز السديس',
                'company_name' => 'شركة السديس للحديد والصلب',
                'category' => 'steel_materials',
                'phone' => '013-7891234',
                'phone_2' => '0556789012',
                'email' => 'sales@alsudais-steel.com',
                'address' => 'المنطقة الصناعية الثانية',
                'city' => 'الدمام',
                'country' => 'المملكة العربية السعودية',
                'tax_number' => '300987654321003',
                'cr_number' => '2050987654',
                'contact_person' => 'فهد السديس',
                'contact_person_phone' => '0556789012',
                'payment_terms' => 'آجل 30 يوم',
                'credit_limit' => 750000.00,
                'status' => 'نشط',
                'notes' => 'متخصص في الحديد والصلب بأعلى جودة'
            ],

            // Equipment Suppliers
            [
                'name' => 'خالد بن محمد العتيبي',
                'company_name' => 'مؤسسة العتيبي للمعدات الثقيلة',
                'category' => 'heavy_equipment',
                'phone' => '012-3456789',
                'phone_2' => '0557890123',
                'email' => 'equipment@alotaibi.com',
                'address' => 'طريق الملك فهد، حي الصناعي',
                'city' => 'جدة',
                'country' => 'المملكة العربية السعودية',
                'tax_number' => '300456789012003',
                'cr_number' => '4030456789',
                'contact_person' => 'خالد العتيبي',
                'contact_person_phone' => '0557890123',
                'payment_terms' => 'نقدي',
                'credit_limit' => 1000000.00,
                'status' => 'نشط',
                'notes' => 'تأجير ومبيعات المعدات الثقيلة والرافعات'
            ],

            // Electrical Suppliers
            [
                'name' => 'عبدالله صالح المطيري',
                'company_name' => 'شركة المطيري للمواد الكهربائية',
                'category' => 'electrical',
                'phone' => '011-2345678',
                'phone_2' => '0558901234',
                'email' => 'electrical@almutairi.com',
                'address' => 'حي الدرعية، شارع عثمان بن عفان',
                'city' => 'الرياض',
                'country' => 'المملكة العربية السعودية',
                'tax_number' => '300234567890003',
                'cr_number' => '1010234567',
                'contact_person' => 'عبدالله المطيري',
                'contact_person_phone' => '0558901234',
                'payment_terms' => 'آجل 60 يوم',
                'credit_limit' => 300000.00,
                'status' => 'نشط',
                'notes' => 'مواد كهربائية وإضاءة بأسعار تنافسية'
            ],

            // Construction Tools
            [
                'name' => 'نايف عبدالرحمن القحطاني',
                'company_name' => 'متجر القحطاني للأدوات والعدد',
                'category' => 'tools_equipment',
                'phone' => '017-5678901',
                'phone_2' => '0559012345',
                'email' => 'tools@alqahtani.com',
                'address' => 'شارع الأمير سلطان، حي النزهة',
                'city' => 'أبها',
                'country' => 'المملكة العربية السعودية',
                'tax_number' => '300567890123003',
                'cr_number' => '5400567890',
                'contact_person' => 'نايف القحطاني',
                'contact_person_phone' => '0559012345',
                'payment_terms' => 'آجل 30 يوم',
                'credit_limit' => 150000.00,
                'status' => 'نشط',
                'notes' => 'أدوات وعدد البناء والصيانة'
            ],

            // Safety Equipment
            [
                'name' => 'سعد محمد الشمري',
                'company_name' => 'مؤسسة الشمري لمعدات السلامة',
                'category' => 'safety_equipment',
                'phone' => '016-6789012',
                'phone_2' => '0550123456',
                'email' => 'safety@alshammari.com',
                'address' => 'المنطقة الصناعية الأولى',
                'city' => 'حائل',
                'country' => 'المملكة العربية السعودية',
                'tax_number' => '300678901234003',
                'cr_number' => '3200678901',
                'contact_person' => 'سعد الشمري',
                'contact_person_phone' => '0550123456',
                'payment_terms' => 'آجل 30 يوم',
                'credit_limit' => 200000.00,
                'status' => 'نشط',
                'notes' => 'معدات السلامة المهنية والحماية الشخصية'
            ],

            // Transportation
            [
                'name' => 'محمد علي الغامدي',
                'company_name' => 'شركة الغامدي للنقل والشحن',
                'category' => 'transportation',
                'phone' => '012-7890123',
                'phone_2' => '0551234567',
                'email' => 'transport@alghamdi.com',
                'address' => 'طريق مكة السريع، حي الروضة',
                'city' => 'جدة',
                'country' => 'المملكة العربية السعودية',
                'tax_number' => '300789012345003',
                'cr_number' => '4030789012',
                'contact_person' => 'محمد الغامدي',
                'contact_person_phone' => '0551234567',
                'payment_terms' => 'نقدي',
                'credit_limit' => 400000.00,
                'status' => 'نشط',
                'notes' => 'خدمات النقل والشحن للمشاريع الكبرى'
            ],

            // Concrete Suppliers
            [
                'name' => 'عبدالعزيز فهد الدوسري',
                'company_name' => 'مصنع الدوسري للخرسانة الجاهزة',
                'category' => 'concrete',
                'phone' => '011-8901234',
                'phone_2' => '0552345678',
                'email' => 'concrete@aldosari.com',
                'address' => 'طريق الملك خالد، المنطقة الصناعية',
                'city' => 'الرياض',
                'country' => 'المملكة العربية السعودية',
                'tax_number' => '300890123456003',
                'cr_number' => '1010890123',
                'contact_person' => 'عبدالعزيز الدوسري',
                'contact_person_phone' => '0552345678',
                'payment_terms' => 'آجل 30 يوم',
                'credit_limit' => 600000.00,
                'status' => 'نشط',
                'notes' => 'خرسانة جاهزة بمختلف الدرجات والمقاومات'
            ]
        ];

        // Insert the predefined suppliers
        foreach ($suppliersData as $supplierData) {
            Supplier::create($supplierData);
        }

        // Create additional random suppliers
        $categories = ['building_materials', 'steel_materials', 'electrical', 'plumbing', 'tools_equipment', 'safety_equipment', 'transportation', 'concrete', 'heavy_equipment'];
        $cities = ['الرياض', 'جدة', 'الدمام', 'الخبر', 'مكة المكرمة', 'المدينة المنورة', 'الطائف', 'أبها', 'تبوك', 'حائل', 'جازان', 'نجران', 'الجبيل', 'ينبع', 'القطيف'];
        $paymentTerms = ['نقدي', 'آجل 30 يوم', 'آجل 60 يوم', 'آجل 90 يوم'];
        $statuses = ['نشط', 'نشط', 'نشط', 'غير نشط']; // 75% active

        for ($i = 1; $i <= 30; $i++) {
            $firstName = $faker->firstNameMale();
            $lastName = $faker->lastName();
            $fullName = $firstName . ' ' . $lastName;
            
            // Generate company name variations
            $companyTypes = ['مؤسسة', 'شركة', 'متجر', 'معرض', 'مصنع'];
            $companyType = $faker->randomElement($companyTypes);
            $companyName = $companyType . ' ' . $lastName . ' للتجارة';
            
            $category = $faker->randomElement($categories);
            $city = $faker->randomElement($cities);
            $paymentTerm = $faker->randomElement($paymentTerms);
            
            Supplier::create([
                'name' => $fullName,
                'company_name' => $companyName,
                'category' => $category,
                'phone' => $faker->regexify('01[1-9]-[0-9]{7}'),
                'phone_2' => $faker->regexify('055[0-9]{7}'),
                'email' => strtolower(str_replace(' ', '', $lastName)) . '@gmail.com',
                'address' => $faker->streetAddress(),
                'city' => $city,
                'country' => 'المملكة العربية السعودية',
                'tax_number' => '300' . $faker->numerify('###########') . '003',
                'cr_number' => $faker->numerify('##########'),
                'contact_person' => $fullName,
                'contact_person_phone' => $faker->regexify('055[0-9]{7}'),
                'payment_terms' => $paymentTerm,
                'credit_limit' => $faker->randomFloat(2, 50000, 800000),
                'status' => $faker->randomElement($statuses), // 75% active
                'notes' => $faker->optional(0.7)->sentence()
            ]);
        }

        $this->command->info('تم إنشاء ' . (count($suppliersData) + 30) . ' مورد بنجاح');
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RevenueEntity;

class RevenueEntitySeeder extends Seeder
{
    public function run(): void
    {
        $entities = [
            [
                'name' => 'شركة النور للمقاولات',
                'type' => 'company',
                'contact_person' => 'أحمد النور',
                'phone' => '0551234567',
                'email' => 'info@alnoor.com',
                'address' => 'الرياض - حي العليا',
                'tax_number' => '3101234567',
                'commercial_record' => '1012345678',
                'status' => 'active',
            ],
            [
                'name' => 'وزارة النقل',
                'type' => 'government',
                'contact_person' => 'م. خالد العتيبي',
                'phone' => '0112345678',
                'email' => 'contact@mot.gov.sa',
                'address' => 'الرياض - طريق الملك عبدالعزيز',
                'tax_number' => '3109876543',
                'commercial_record' => null,
                'status' => 'active',
            ],
            [
                'name' => 'مؤسسة البناء الحديث',
                'type' => 'company',
                'contact_person' => 'سعيد الحربي',
                'phone' => '0569876543',
                'email' => 'info@modernbuild.com',
                'address' => 'جدة - حي الشاطئ',
                'tax_number' => '3105555555',
                'commercial_record' => '1023456789',
                'status' => 'inactive',
            ],
            [
                'name' => 'عبدالله صالح',
                'type' => 'individual',
                'contact_person' => 'عبدالله صالح',
                'phone' => '0598765432',
                'email' => 'abdullah@gmail.com',
                'address' => 'مكة - العوالي',
                'tax_number' => null,
                'commercial_record' => null,
                'status' => 'active',
            ],
            [
                'name' => 'شركة الخليج للاستثمار',
                'type' => 'company',
                'contact_person' => 'محمد الخليفي',
                'phone' => '0533333333',
                'email' => 'info@gulfinvest.com',
                'address' => 'الدمام - حي الشاطئ',
                'tax_number' => '3102222222',
                'commercial_record' => '1034567890',
                'status' => 'active',
            ],
        ];
        foreach ($entities as $entity) {
            RevenueEntity::create($entity);
        }
    }
}

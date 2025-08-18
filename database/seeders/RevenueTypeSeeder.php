<?php

namespace Database\Seeders;

use App\Models\RevenueType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RevenueTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $revenueTypes = [
            [
                'name' => 'إيرادات المشاريع',
                'code' => 'PRJ001',
                'description' => 'إيرادات من تنفيذ المشاريع الإنشائية',
                'is_active' => true
            ],
            [
                'name' => 'خدمات الصيانة',
                'code' => 'MNT001',
                'description' => 'إيرادات من خدمات صيانة المباني والمعدات',
                'is_active' => true
            ],
            [
                'name' => 'تأجير المعدات',
                'code' => 'RNT001',
                'description' => 'إيرادات من تأجير المعدات والآلات',
                'is_active' => true
            ],
            [
                'name' => 'استشارات هندسية',
                'code' => 'CON001',
                'description' => 'إيرادات من الخدمات الاستشارية والهندسية',
                'is_active' => true
            ],
            [
                'name' => 'مبيعات المواد',
                'code' => 'SAL001',
                'description' => 'إيرادات من بيع مواد البناء والإنشاء',
                'is_active' => true
            ],
            [
                'name' => 'عقود التشغيل',
                'code' => 'OPR001',
                'description' => 'إيرادات من عقود تشغيل المرافق',
                'is_active' => true
            ],
            [
                'name' => 'الإيرادات الأخرى',
                'code' => 'OTH001',
                'description' => 'إيرادات متنوعة أخرى',
                'is_active' => true
            ]
        ];

        foreach ($revenueTypes as $type) {
            RevenueType::create($type);
        }
    }
}

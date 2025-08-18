<?php

namespace Database\Seeders;

use App\Models\ExpenseCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExpenseCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $expenseCategories = [
            [
                'name' => 'أجور العمالة',
                'code' => 'LAB001',
                'description' => 'أجور العمال والموظفين والمقاولين',
                'is_active' => true
            ],
            [
                'name' => 'مواد البناء',
                'code' => 'MAT001',
                'description' => 'مواد البناء والإنشاء مثل الخرسانة والحديد والرمل',
                'is_active' => true
            ],
            [
                'name' => 'معدات ومعدات',
                'code' => 'EQP001',
                'description' => 'تكاليف استئجار وصيانة المعدات والآلات',
                'is_active' => true
            ],
            [
                'name' => 'وقود ومحروقات',
                'code' => 'FUEL01',
                'description' => 'تكاليف الوقود للمعدات والمركبات',
                'is_active' => true
            ],
            [
                'name' => 'النقل والشحن',
                'code' => 'SHIP01',
                'description' => 'تكاليف نقل المواد والمعدات',
                'is_active' => true
            ],
            [
                'name' => 'المرافق العامة',
                'code' => 'UTIL01',
                'description' => 'الكهرباء، المياه، الاتصالات',
                'is_active' => true
            ],
            [
                'name' => 'التأمين والضمانات',
                'code' => 'INS001',
                'description' => 'تكاليف التأمين على المعدات والعمال',
                'is_active' => true
            ],
            [
                'name' => 'المصروفات الإدارية',
                'code' => 'ADM001',
                'description' => 'المصروفات الإدارية العامة للمكتب',
                'is_active' => true
            ]
        ];

        foreach ($expenseCategories as $category) {
            ExpenseCategory::create($category);
        }
    }
}

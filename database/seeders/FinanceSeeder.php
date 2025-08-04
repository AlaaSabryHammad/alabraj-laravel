<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Finance;
use Carbon\Carbon;

class FinanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $finances = [
            [
                'type' => 'income',
                'category' => 'مشاريع البناء',
                'description' => 'دفعة مقدمة من مشروع أبراج الرياض',
                'amount' => 250000.00,
                'transaction_date' => Carbon::now()->subDays(5),
                'payment_method' => 'تحويل بنكي',
                'reference_number' => 'PAY-2025-001',
                'notes' => 'دفعة أولى من العميل - 30% من قيمة المشروع',
                'status' => 'completed',
            ],
            [
                'type' => 'expense',
                'category' => 'المواد والمعدات',
                'description' => 'شراء مواد البناء - أسمنت وحديد',
                'amount' => 45000.00,
                'transaction_date' => Carbon::now()->subDays(3),
                'payment_method' => 'شيك',
                'reference_number' => 'CHK-2025-015',
                'notes' => 'مواد لمشروع المجمع التجاري',
                'status' => 'completed',
            ],
            [
                'type' => 'expense',
                'category' => 'الرواتب والأجور',
                'description' => 'رواتب الموظفين - شهر يوليو',
                'amount' => 85000.00,
                'transaction_date' => Carbon::now()->subDays(10),
                'payment_method' => 'تحويل بنكي',
                'reference_number' => 'SAL-2025-07',
                'notes' => 'رواتب شهر 7/2025',
                'status' => 'completed',
            ],
            [
                'type' => 'income',
                'category' => 'استشارات هندسية',
                'description' => 'رسوم استشارات هندسية للفيلا السكنية',
                'amount' => 15000.00,
                'transaction_date' => Carbon::now()->subDays(7),
                'payment_method' => 'نقداً',
                'reference_number' => 'CONS-2025-008',
                'notes' => 'استشارة هندسية وتصميم معماري',
                'status' => 'completed',
            ],
            [
                'type' => 'expense',
                'category' => 'النقل والمواصلات',
                'description' => 'وقود ومصاريف نقل المعدات',
                'amount' => 8500.00,
                'transaction_date' => Carbon::now()->subDays(2),
                'payment_method' => 'بطاقة ائتمان',
                'reference_number' => 'FUEL-2025-032',
                'notes' => 'تكاليف نقل المعدات الثقيلة',
                'status' => 'completed',
            ],
            [
                'type' => 'expense',
                'category' => 'صيانة وإصلاح',
                'description' => 'صيانة المعدات الثقيلة',
                'amount' => 12000.00,
                'transaction_date' => Carbon::now()->subDays(6),
                'payment_method' => 'تحويل بنكي',
                'reference_number' => 'MAINT-2025-019',
                'notes' => 'صيانة دورية للحفارات والرافعات',
                'status' => 'completed',
            ],
            [
                'type' => 'income',
                'category' => 'مبيعات المواد',
                'description' => 'بيع مواد فائضة من مشروع سابق',
                'amount' => 7500.00,
                'transaction_date' => Carbon::now()->subDays(4),
                'payment_method' => 'نقداً',
                'reference_number' => 'SALE-2025-003',
                'notes' => 'بيع مواد متبقية بحالة جيدة',
                'status' => 'completed',
            ],
            [
                'type' => 'expense',
                'category' => 'مصاريف إدارية',
                'description' => 'فواتير الكهرباء والمياه للمكاتب',
                'amount' => 3200.00,
                'transaction_date' => Carbon::now()->subDays(1),
                'payment_method' => 'تحويل بنكي',
                'reference_number' => 'UTIL-2025-024',
                'notes' => 'فواتير الخدمات العامة لشهر يوليو',
                'status' => 'completed',
            ]
        ];

        foreach ($finances as $finance) {
            Finance::create($finance);
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Transport;
use Carbon\Carbon;

class TransportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $transports = [
            [
                'vehicle_type' => 'شاحنة كبيرة',
                'vehicle_number' => 'أ ب ج 1234',
                'driver_name' => 'أحمد محمد العلي',
                'source_location' => 'مستودع المواد الرئيسي',
                'destination' => 'مشروع أبراج الرياض - المرحلة الأولى',
                'departure_time' => Carbon::now()->addHours(2),
                'arrival_time' => null,
                'cargo_description' => 'أعمدة خرسانية مسبقة الصب - 20 عمود',
                'fuel_cost' => 180.00,
            ],
            [
                'vehicle_type' => 'خلاطة خرسانة',
                'vehicle_number' => 'د ه و 5678',
                'driver_name' => 'محمد سالم الأحمدي',
                'source_location' => 'محطة الخلط المركزية',
                'destination' => 'مشروع مجمع الأعمال التجاري',
                'departure_time' => Carbon::now()->subHours(1),
                'arrival_time' => null,
                'cargo_description' => 'خرسانة جاهزة - 8 متر مكعب',
                'fuel_cost' => 120.00,
            ],
            [
                'vehicle_type' => 'قلاب',
                'vehicle_number' => 'ز ح ط 9012',
                'driver_name' => 'عبدالله يوسف الزهراني',
                'source_location' => 'مقلع الرمل والحصى',
                'destination' => 'مشروع الفيلا السكنية - حي النخيل',
                'departure_time' => Carbon::yesterday()->addHours(8),
                'arrival_time' => Carbon::yesterday()->addHours(10),
                'cargo_description' => 'رمل وحصى للأساسات - 12 طن',
                'fuel_cost' => 95.00,
            ],
            [
                'vehicle_type' => 'رافعة',
                'vehicle_number' => 'ي ك ل 3456',
                'driver_name' => 'خالد عبدالرحمن المطيري',
                'source_location' => 'قاعدة المعدات الثقيلة',
                'destination' => 'برج الأعمال المركزي',
                'departure_time' => Carbon::now()->addDays(1)->addHours(6),
                'arrival_time' => null,
                'cargo_description' => 'رفع ونقل عناصر الواجهة الزجاجية',
                'fuel_cost' => 250.00,
            ],
            [
                'vehicle_type' => 'شاحنة متوسطة',
                'vehicle_number' => 'م ن س 7890',
                'driver_name' => 'سعد فهد القحطاني',
                'source_location' => 'مخزن الأدوات والمعدات',
                'destination' => 'مستودع المواد - المنطقة الصناعية',
                'departure_time' => Carbon::now()->subDays(2)->addHours(14),
                'arrival_time' => Carbon::now()->subDays(2)->addHours(16),
                'cargo_description' => 'أدوات ومعدات البناء - شحنة متنوعة',
                'fuel_cost' => 65.00,
            ],
            [
                'vehicle_type' => 'حفار',
                'vehicle_number' => 'ع ف ص 2468',
                'driver_name' => 'ماجد عبدالعزيز الشهري',
                'source_location' => 'ورشة الصيانة المركزية',
                'destination' => 'موقع المشروع الجديد - شمال المدينة',
                'departure_time' => Carbon::now()->subHours(3),
                'arrival_time' => null,
                'cargo_description' => 'نقل حفار للموقع الجديد',
                'fuel_cost' => 320.00,
            ],
            [
                'vehicle_type' => 'شاحنة صغيرة',
                'vehicle_number' => 'ق ر ش 1357',
                'driver_name' => 'عمر حسن الغامدي',
                'source_location' => 'مكتب الإدارة الرئيسي',
                'destination' => 'مكتب المشروع الميداني',
                'departure_time' => Carbon::now()->addHours(4),
                'arrival_time' => null,
                'cargo_description' => 'مستلزمات مكتبية ومواد صغيرة',
                'fuel_cost' => 40.00,
            ],
            [
                'vehicle_type' => 'شاحنة كبيرة',
                'vehicle_number' => 'ت ث خ 9753',
                'driver_name' => 'فيصل أحمد الحربي',
                'source_location' => 'مصنع الحديد والصلب',
                'destination' => 'مشروع المجمع السكني الجديد',
                'departure_time' => Carbon::now()->subDays(1)->addHours(7),
                'arrival_time' => Carbon::now()->subDays(1)->addHours(9),
                'cargo_description' => 'حديد التسليح - 15 طن',
                'fuel_cost' => 195.00,
            ]
        ];

        foreach ($transports as $transport) {
            Transport::create($transport);
        }
    }
}

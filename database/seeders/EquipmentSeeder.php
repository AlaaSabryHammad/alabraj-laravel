<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Equipment;

class EquipmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $equipment = [
            [
                'name' => 'حفار كاتربيلر 320D',
                'type' => 'حفار',
                'model' => 'CAT 320D',
                'serial_number' => 'CAT-2023-001',
                'status' => 'available',
                'location' => 'مستودع الرياض الرئيسي',
                'purchase_date' => '2023-03-15',
                'purchase_price' => 450000.00,
                'warranty_expiry' => '2025-03-15'
            ],
            [
                'name' => 'رافعة ليبهر LTM 1100',
                'type' => 'رافعة',
                'model' => 'LTM 1100-5.2',
                'serial_number' => 'LIE-2022-002',
                'status' => 'in_use',
                'location' => 'مشروع أبراج الرياض',
                'purchase_date' => '2022-08-20',
                'purchase_price' => 750000.00,
                'warranty_expiry' => '2024-08-20'
            ],
            [
                'name' => 'شاحنة مرسيدس أكتروس',
                'type' => 'شاحنة',
                'model' => 'Actros 3351',
                'serial_number' => 'MER-2023-003',
                'status' => 'available',
                'location' => 'مستودع جدة',
                'purchase_date' => '2023-01-10',
                'purchase_price' => 320000.00,
                'warranty_expiry' => '2026-01-10'
            ],
            [
                'name' => 'جرافة كوماتسو D65',
                'type' => 'جرافة',
                'model' => 'D65PX-18',
                'serial_number' => 'KOM-2021-004',
                'status' => 'maintenance',
                'location' => 'ورشة الصيانة - الرياض',
                'purchase_date' => '2021-11-05',
                'purchase_price' => 380000.00,
                'warranty_expiry' => '2023-11-05',
                'last_maintenance' => '2024-07-15'
            ],
            [
                'name' => 'خلاطة خرسانة فولفو',
                'type' => 'خلاطة خرسانة',
                'model' => 'FMX 500',
                'serial_number' => 'VOL-2023-005',
                'status' => 'in_use',
                'location' => 'مشروع مجمع الدمام التجاري',
                'purchase_date' => '2023-05-22',
                'purchase_price' => 280000.00,
                'warranty_expiry' => '2025-05-22'
            ],
            [
                'name' => 'مولد كهربائي كاتربيلر',
                'type' => 'مولد كهربائي',
                'model' => 'C18 ACERT',
                'serial_number' => 'CAT-GEN-006',
                'status' => 'out_of_order',
                'location' => 'ورشة الصيانة - جدة',
                'purchase_date' => '2020-12-08',
                'purchase_price' => 150000.00,
                'warranty_expiry' => '2022-12-08'
            ]
        ];

        foreach ($equipment as $item) {
            Equipment::create($item);
        }
    }
}

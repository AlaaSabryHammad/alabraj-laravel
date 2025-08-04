<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = [
            [
                'name' => 'أحمد محمد الأحمد',
                'position' => 'مدير المشاريع',
                'department' => 'الإدارة',
                'email' => 'ahmed.mohamed@albarajco.com',
                'phone' => '+966501234567',
                'hire_date' => '2020-01-15',
                'salary' => 12000.00,
                'national_id' => '1234567890',
                'address' => 'الرياض، حي النرجس'
            ],
            [
                'name' => 'فاطمة علي السالم',
                'position' => 'محاسبة رئيسية',
                'department' => 'المالية',
                'email' => 'fatima.ali@albarajco.com',
                'phone' => '+966501234568',
                'hire_date' => '2019-03-20',
                'salary' => 8500.00,
                'national_id' => '2234567890',
                'address' => 'الرياض، حي الملقا'
            ],
            [
                'name' => 'خالد عبدالله الخالد',
                'position' => 'مهندس مدني',
                'department' => 'الهندسة',
                'email' => 'khalid.abdullah@albarajco.com',
                'phone' => '+966501234569',
                'hire_date' => '2021-06-10',
                'salary' => 9500.00,
                'national_id' => '3234567890',
                'address' => 'الرياض، حي العليا'
            ],
            [
                'name' => 'نورا سعد الدوسري',
                'position' => 'مديرة الموارد البشرية',
                'department' => 'الموارد البشرية',
                'email' => 'nora.saad@albarajco.com',
                'phone' => '+966501234570',
                'hire_date' => '2020-09-05',
                'salary' => 11000.00,
                'national_id' => '4234567890',
                'address' => 'الرياض، حي الياسمين'
            ],
            [
                'name' => 'محمد سالم القحطاني',
                'position' => 'فني معدات',
                'department' => 'الصيانة',
                'email' => 'mohammed.salem@albarajco.com',
                'phone' => '+966501234571',
                'hire_date' => '2022-02-14',
                'salary' => 6500.00,
                'national_id' => '5234567890',
                'address' => 'الرياض، حي الربوة'
            ]
        ];

        foreach ($employees as $employee) {
            Employee::create($employee);
        }
    }
}

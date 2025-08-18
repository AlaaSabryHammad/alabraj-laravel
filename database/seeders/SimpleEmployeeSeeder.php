<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class SimpleEmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('ar_SA');

        // Simple Arabic names
        $maleNames = [
            'أحمد محمد العلي',
            'محمد عبدالله السعد',
            'عبدالرحمن أحمد الشمري',
            'فهد خالد المطيري',
            'سعد عبدالعزيز النهدي',
            'عبدالله محمد القرني',
            'خالد سعد الغامدي',
            'عبدالعزيز فهد الدوسري',
            'مشعل أحمد العتيبي',
            'بندر محمد الحربي',
            'طلال عبدالله الراشد',
            'نواف سعد المالكي',
            'ماجد خالد السبيعي',
            'حسام عبدالرحمن الزهراني',
            'وليد محمد العمري',
            'عمر عبدالله البقمي'
        ];

        $femaleNames = [
            'فاطمة أحمد العلي',
            'عائشة محمد السعد',
            'خديجة عبدالله الشمري',
            'مريم خالد المطيري',
            'زينب سعد النهدي',
            'سارة محمد القرني',
            'نورا خالد الغامدي',
            'هيفاء عبدالعزيز الدوسري',
            'ريم أحمد العتيبي',
            'لطيفة محمد الحربي',
            'أمل عبدالله الراشد',
            'هند سعد المالكي',
            'ندى خالد السبيعي',
            'رهف عبدالرحمن الزهراني',
            'شيماء محمد العمري',
            'دعاء عبدالله البقمي'
        ];

        $roles = ['employee', 'manager', 'accountant', 'supervisor'];
        $departments = ['الإدارة العامة', 'المحاسبة', 'الهندسة', 'الموارد البشرية'];

        echo "Creating 50 simple employees...\n";

        for ($i = 0; $i < 50; $i++) {
            $gender = $faker->randomElement(['male', 'female']);
            $name = $gender === 'male' ? $faker->randomElement($maleNames) : $faker->randomElement($femaleNames);
            $role = $faker->randomElement($roles);
            $department = $faker->randomElement($departments);

            // Create user
            $user = User::create([
                'name' => $name,
                'email' => 'emp' . ($i + 1) . '@abraj.com',
                'password' => Hash::make('password123'),
                'role' => $role,
                'department' => $department
            ]);

            // Create employee - only with existing fields
            Employee::create([
                'name' => $name,
                'email' => $user->email,
                'phone' => '05' . $faker->numberBetween(10000000, 99999999),
                'department' => $department,
                'position' => $role,
                'role' => $role,
                'salary' => $faker->numberBetween(5000, 15000),
                'status' => 'active',
                'user_id' => $user->id,
                'category' => $gender === 'male' ? 'ذكر' : 'أنثى',
                'nationality' => $faker->randomElement(['سعودي', 'مصري', 'أردني'])
            ]);

            if (($i + 1) % 10 === 0) {
                echo "Created " . ($i + 1) . " employees...\n";
            }
        }

        echo "✅ Successfully created 50 employees!\n";
    }
}

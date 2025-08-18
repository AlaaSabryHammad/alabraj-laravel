<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use function now;

class EmployeeDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = \App\Models\Role::where('is_active', true)->pluck('id', 'name')->toArray();
        $arabicNames = [
            'أحمد محمد',
            'محمد علي',
            'خالد حسن',
            'سعيد إبراهيم',
            'يوسف محمود',
            'عبدالله سالم',
            'سلمان فهد',
            'راشد ناصر',
            'سامي عادل',
            'طارق يوسف',
            'فهد عبدالعزيز',
            'سيف راشد',
            'حسن أحمد',
            'علي سعيد',
            'ماجد خالد',
            'سالم محمد',
            'ناصر عبدالله',
            'عادل فهد',
            'محمود راشد',
            'إبراهيم سامي',
            'عبدالرحمن طارق',
            'سعيد فهد',
            'سامي سالم',
            'خالد عادل',
            'محمد ماجد',
            'أحمد ناصر',
            'سلمان حسن',
            'راشد يوسف',
            'سيف محمود',
            'حسن عبدالله',
            'علي فهد',
            'ماجد راشد',
            'سالم سامي',
            'ناصر طارق',
            'عادل عبدالعزيز',
            'محمود سيف',
            'إبراهيم حسن',
            'عبدالرحمن علي',
            'سعيد ماجد',
            'سامي سالم',
            'خالد ناصر',
            'محمد عادل',
            'أحمد فهد',
            'سلمان راشد',
            'راشد سامي',
            'سيف طارق',
            'حسن عبدالعزيز',
            'علي سيف',
            'ماجد حسن',
            'سالم علي'
        ];
        $rolesList = array_keys($roles);
        for ($i = 0; $i < 50; $i++) {
            $roleName = $rolesList[$i % count($rolesList)];
            // Create user
            $user = \App\Models\User::create([
                'name' => $arabicNames[$i],
                'email' => 'employee' . ($i + 1) . '@demo.com',
                'password' => bcrypt('password123'),
            ]);
            $user->assignRole($roleName);
            // Create employee
            \App\Models\Employee::create([
                'name' => $arabicNames[$i],
                'email' => 'employee' . ($i + 1) . '@demo.com',
                'user_id' => $user->id,
                'status' => 'active',
                'department' => 'الإدارة',
                'position' => 'موظف',
                'hire_date' => now()->subDays(10 + $i),
                'salary' => 3000 + ($i * 50),
                'role' => $roleName,
            ]);
        }
    }
}

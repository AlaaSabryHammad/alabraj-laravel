<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = ['management', 'engineering', 'it', 'hr', 'finance', 'operations'];

        for ($i = 0; $i < 200; $i++) {
            $national_id = '2' . str_pad(mt_rand(0, 999999999), 9, '0', STR_PAD_LEFT);
            $email = $national_id . '@abraj.com';

            $user = User::create([
                'name' => 'Employee ' . ($i + 1),
                'email' => $email,
                'password' => Hash::make($national_id),
                'department' => $departments[array_rand($departments)],
            ]);

            Employee::create([
                'name' => $user->name,
                'email' => $user->email,
                'national_id' => $national_id,
                'phone' => '05' . mt_rand(10000000, 99999999),
                'department' => $user->department,
                'position' => 'Position ' . ($i + 1),
                'salary' => mt_rand(3000, 15000),
                'status' => 'active',
                'user_id' => $user->id,
            ]);
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'مدير النظام',
            'email' => 'admin@abraj.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Create Regular User
        User::create([
            'name' => 'أحمد محمد العتيبي',
            'email' => 'user@abraj.com',
            'password' => Hash::make('user123'),
            'role' => 'user',
            'email_verified_at' => now(),
        ]);

        // Create Project Manager
        User::create([
            'name' => 'سارة عبدالرحمن الحربي',
            'email' => 'manager@abraj.com',
            'password' => Hash::make('manager123'),
            'role' => 'manager',
            'email_verified_at' => now(),
        ]);

        // Create Employee
        User::create([
            'name' => 'خالد سعد الغامدي',
            'email' => 'employee@abraj.com',
            'password' => Hash::make('employee123'),
            'role' => 'employee',
            'email_verified_at' => now(),
        ]);

        // Create Finance Manager
        User::create([
            'name' => 'نوف فهد الدوسري',
            'email' => 'finance@abraj.com',
            'password' => Hash::make('finance123'),
            'role' => 'finance',
            'email_verified_at' => now(),
        ]);
    }
}

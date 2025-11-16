<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class MinimalUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إنشاء المستخدم الوحيد محمد الشهراني
        $generalManagerRole = Role::where('name', 'general_manager')->first();

        User::create([
            'name' => 'Alaa Sabry Hammad',
            'email' => 'alaa.handaza@gmail.com',
            'phone' => '0501234567',
            'password' => Hash::make('123456789'),
            'email_verified_at' => now(),
            'role' => 'admin',
            'department' => 'الإدارة العامة',
            'role_id' => $generalManagerRole ? $generalManagerRole->id : null,
        ]);
    }
}

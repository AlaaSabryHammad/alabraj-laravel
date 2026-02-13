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
     * إنشاء حساب المدير الوحيد - alaa.handaza@gmail.com
     */
    public function run(): void
    {
        $generalManagerRole = Role::where('name', 'general_manager')->first();

        $admin = User::firstOrCreate(
            ['email' => 'alaa.handaza@gmail.com'],
            [
                'name' => 'Alaa Sabry Hammad',
                'phone' => '0501234567',
                'password' => Hash::make('123456789'),
                'email_verified_at' => now(),
                'role' => 'admin',
                'department' => 'الإدارة العامة',
                'role_id' => $generalManagerRole?->id,
            ]
        );

        // ربط دور المدير العام في جدول user_roles (many-to-many)
        if ($generalManagerRole && !$admin->hasRole('general_manager')) {
            $admin->roles()->attach($generalManagerRole->id);
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Employee;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class CreateMohamedUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // التحقق من وجود المستخدم مسبقاً
        $existingUser = User::where('email', 'mohamed@abraj.com')->first();
        if ($existingUser) {
            $this->command->info("⚠️  المستخدم موجود مسبقاً: " . $existingUser->name);
            return;
        }

        // إنشاء المستخدم محمد الشهراني
        $user = User::create([
            'name' => 'محمد الشهراني',
            'email' => 'mohamed@abraj.com',
            'password' => Hash::make('mohamed123'),
            'email_verified_at' => now(),
            'phone' => '0501234567',
        ]);

        // البحث عن دور المدير العام
        $generalManagerRole = Role::where('name', 'general-manager')
            ->orWhere('name', 'super_admin')
            ->orWhere('name', 'admin')
            ->first();

        if ($generalManagerRole) {
            $user->roles()->attach($generalManagerRole->id);
        }

        // إنشاء سجل في جدول الموظفين
        $employee = Employee::create([
            'name' => 'محمد الشهراني',
            'user_id' => $user->id,
            'position' => 'مدير عام',
            'role' => 'super_admin',
            'department' => 'الإدارة العليا',
            'hire_date' => now(),
            'email' => 'mohamed@abraj.com',
            'phone' => '0501234567',
            'status' => 'active',
            'nationality' => 'سعودي',
            'national_id' => '1234567890',
            'salary' => 25000.00,
        ]);

        $this->command->info("✅ تم إنشاء المستخدم: محمد الشهراني");
        $this->command->info("📧 الإيميل: mohamed@abraj.com");
        $this->command->info("🔑 كلمة المرور: mohamed123");
        $this->command->info("👤 ID الموظف: " . $employee->id);
    }
}

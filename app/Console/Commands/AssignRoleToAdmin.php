<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class AssignRoleToAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:assign-role-to-admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign roles to admin users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('تعيين الأدوار للمديرين...');

        // العثور على دور المدير العام
        $superAdminRole = Role::where('name', 'super_admin')->first();

        if (!$superAdminRole) {
            $this->error('لم يتم العثور على دور المدير العام');
            return;
        }

        // تعيين دور المدير العام لكلا الحسابين
        $adminUsers = User::whereIn('email', ['admin@abraj.com', 'admin123@abraj.com'])->get();

        foreach ($adminUsers as $user) {
            // حذف الأدوار السابقة
            DB::table('user_roles')->where('user_id', $user->id)->delete();

            // إضافة دور المدير العام
            DB::table('user_roles')->insert([
                'user_id' => $user->id,
                'role_id' => $superAdminRole->id,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $this->info("تم تعيين دور المدير العام للمستخدم: {$user->email}");
        }

        $this->info('تم تعيين الأدوار بنجاح!');
    }
}

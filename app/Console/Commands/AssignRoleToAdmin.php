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
        $generalManagerRole = Role::where('name', 'general_manager')->first();

        if (!$generalManagerRole) {
            $this->error('لم يتم العثور على دور المدير العام');
            return;
        }

        // تعيين دور المدير العام للمستخدمين الرئيسيين
        $adminUsers = User::whereIn('email', ['mohamed.alshahrani@example.com', '2406418059@alabraaj.com.sa'])->get();

        foreach ($adminUsers as $user) {
            // تحديث role_id
            $user->update([
                'role_id' => $generalManagerRole->id,
                'role' => 'general_manager'
            ]);

            $this->info("تم تعيين دور المدير العام للمستخدم: {$user->email}");
        }

        $this->info('تم تعيين الأدوار بنجاح!');
    }
}

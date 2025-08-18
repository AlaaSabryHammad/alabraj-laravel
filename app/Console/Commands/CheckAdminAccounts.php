<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class CheckAdminAccounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-admin-accounts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Display admin account information';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== بيانات حسابات المديرين ===');
        $this->line('');

        $users = User::whereIn('email', ['admin@abraj.com', 'admin123@abraj.com'])
            ->with('roles')
            ->get();

        if ($users->isEmpty()) {
            $this->error('لا توجد حسابات مدير بهذه الإيميلات.');
            return;
        }

        foreach ($users as $user) {
            $this->info("الإيميل: " . $user->email);
            $this->info("الاسم: " . $user->name);
            $this->info("كلمة المرور: admin123");
            $this->info("الأدوار: " . $user->roles->pluck('display_name')->join(', '));
            $this->line('---');
        }
    }
}

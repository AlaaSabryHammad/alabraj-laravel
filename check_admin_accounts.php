<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class);

echo "=== بيانات حسابات المديرين ===\n\n";

$users = \App\Models\User::whereIn('email', ['admin@abraj.com', 'admin123@abraj.com'])
    ->with('roles')
    ->get();

foreach ($users as $user) {
    echo "الإيميل: " . $user->email . "\n";
    echo "الاسم: " . $user->name . "\n";
    echo "كلمة المرور: admin123\n";
    echo "الأدوار: " . $user->roles->pluck('display_name')->join(', ') . "\n";
    echo "---\n";
}

if ($users->isEmpty()) {
    echo "لا توجد حسابات مدير بهذه الإيميلات.\n";
}

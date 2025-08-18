<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🎯 إضافة دور رئيس مجلس الإدارة\n";
echo "=============================\n\n";

// إضافة الدور الجديد
$boardChairmanRole = [
    'name' => 'board_chairman',
    'display_name' => 'رئيس مجلس الإدارة',
    'category' => 'executive',
    'description' => 'رئيس مجلس الإدارة - أعلى منصب في الشركة',
    'created_at' => now(),
    'updated_at' => now()
];

// التحقق من وجود الدور
$existingRole = \DB::table('roles')->where('name', 'board_chairman')->first();

if (!$existingRole) {
    $roleId = \DB::table('roles')->insertGetId($boardChairmanRole);
    echo "✅ تم إنشاء دور رئيس مجلس الإدارة (ID: {$roleId})\n";
} else {
    echo "ℹ️ دور رئيس مجلس الإدارة موجود مسبقاً (ID: {$existingRole->id})\n";
}

echo "\n💡 الآن يمكن تعديل MANAGER_CHAIN:\n";
echo "=====================================\n";
echo "'general_manager' => 'board_chairman'\n\n";

echo "🔧 هل تريد تطبيق هذا التغيير؟ (y/n): ";

<?php
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Permission;

echo "📋 صلاحيات المشاريع الموجودة:\n";
$projectPermissions = Permission::where('category', 'LIKE', '%مشار%')
    ->orWhere('name', 'LIKE', 'project%')
    ->get(['name', 'display_name', 'category']);

if ($projectPermissions->count() > 0) {
    foreach ($projectPermissions as $perm) {
        echo "• {$perm->display_name} ({$perm->name}) - {$perm->category}\n";
    }
} else {
    echo "لا توجد صلاحيات للمشاريع حالياً.\n";
}

echo "\n📊 إجمالي الصلاحيات: " . Permission::count() . "\n";
echo "📂 الفئات الموجودة:\n";
$categories = Permission::distinct('category')->pluck('category')->toArray();
foreach ($categories as $category) {
    echo "  - {$category}\n";
}

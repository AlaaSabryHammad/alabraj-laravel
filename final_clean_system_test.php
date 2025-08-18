<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

use App\Models\User;
use App\Models\Employee;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;

echo "🎯 اختبار النظام النظيف النهائي\n";
echo "==============================\n\n";

try {
    echo "📊 الإحصائيات النهائية:\n";
    echo "   🔑 الأدوار: " . Role::count() . "\n";
    echo "   🛡️ الصلاحيات: " . Permission::count() . "\n";
    echo "   📍 أنواع المواقع: " . DB::table('location_types')->count() . "\n";
    echo "   👥 المستخدمين: " . User::count() . "\n";
    echo "   👤 الموظفين: " . Employee::count() . "\n\n";

    echo "🔍 التحقق من الجداول الفارغة:\n";
    $emptyTables = [
        'equipment' => DB::table('equipment')->count(),
        'locations' => DB::table('locations')->count(),
        'suppliers' => DB::table('suppliers')->count(),
        'documents' => DB::table('documents')->count(),
        'transports' => DB::table('transports')->count(),
        'finances' => DB::table('finances')->count(),
        'projects' => DB::table('projects')->count(),
        'equipment_types' => DB::table('equipment_types')->count(),
    ];

    foreach ($emptyTables as $table => $count) {
        $status = $count === 0 ? '✅ فارغ' : "⚠️ يحتوي على {$count} سجل";
        echo "   📋 {$table}: {$status}\n";
    }

    echo "\n👨‍💼 المستخدم الوحيد:\n";
    $user = User::first();
    if ($user) {
        echo "   الاسم: {$user->name}\n";
        echo "   الإيميل: {$user->email}\n";
        echo "   الهاتف: {$user->phone}\n";

        $employee = Employee::where('user_id', $user->id)->first();
        if ($employee) {
            echo "   المنصب: {$employee->position}\n";
            echo "   القسم: {$employee->department}\n";
            echo "   الدور: {$employee->role}\n";
        }
    }

    echo "\n🎭 عينة من الأدوار:\n";
    foreach (Role::limit(5)->get() as $role) {
        echo "   - {$role->display_name} ({$role->name})\n";
    }

    echo "\n🌐 الموقع: http://127.0.0.1:8000\n";
    echo "🔐 تسجيل الدخول: mohamed@abraj.com / mohamed123\n";

    echo "\n✅ النظام نظيف ومبسط!\n";
} catch (Exception $e) {
    echo "❌ خطأ: " . $e->getMessage() . "\n";
}

echo "\n🎉 الاختبار مكتمل!\n";

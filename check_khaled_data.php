<?php

// Set up Laravel environment
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI'] = '/';

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$request = Illuminate\Http\Request::capture();
$response = $kernel->handle($request);

use App\Models\Employee;
use App\Models\User;

echo "=== فحص بيانات خالد جمال منصور ===\n\n";

// Find Khaled
$khaled = Employee::where('name', 'خالد جمال منصور')->first();

if ($khaled) {
    echo "تفاصيل خالد جمال منصور:\n";
    echo "- ID: {$khaled->id}\n";
    echo "- الاسم: {$khaled->name}\n";
    echo "- الدور: {$khaled->role}\n";
    echo "- المنصب: {$khaled->position}\n";
    echo "- القسم: {$khaled->department}\n";
    echo "- الموقع ID: {$khaled->location_id}\n";
    echo "- الموقع: " . ($khaled->location ? $khaled->location->name : 'غير محدد') . "\n";
    echo "- الحالة: {$khaled->status}\n";
    echo "- البريد: {$khaled->email}\n";

    // Check if he has a user account
    $user = User::find($khaled->user_id);
    if ($user) {
        echo "- حساب المستخدم: موجود (ID: {$user->id})\n";
        echo "- اسم المستخدم: {$user->name}\n";
        echo "- دور المستخدم: {$user->role}\n";
    } else {
        echo "- حساب المستخدم: غير موجود\n";
    }

    echo "\n=== البحث عن موظفين آخرين بنفس الاسم ===\n";
    $duplicates = Employee::where('name', 'LIKE', '%خالد%')->get();
    foreach ($duplicates as $emp) {
        echo "- {$emp->name} (ID: {$emp->id}) - الدور: {$emp->role}\n";
    }

    echo "\n=== البحث عن موظفين ميدانيين ===\n";
    $fieldWorkers = Employee::where('role', 'LIKE', '%ميداني%')
        ->orWhere('role', 'LIKE', '%field%')
        ->orWhere('position', 'LIKE', '%ميداني%')
        ->get();

    if ($fieldWorkers->count() > 0) {
        echo "الموظفين الميدانيين الموجودين:\n";
        foreach ($fieldWorkers as $worker) {
            echo "- {$worker->name} (ID: {$worker->id}) - الدور: {$worker->role} - المنصب: {$worker->position}\n";

            // Check if this worker might be related to Khaled
            if ($worker->id != $khaled->id) {
                echo "  → موقعه: " . ($worker->location ? $worker->location->name : 'غير محدد') . "\n";

                // Check user account
                $workerUser = User::find($worker->user_id);
                if ($workerUser) {
                    echo "  → حساب المستخدم: {$workerUser->name}\n";
                }
            }
        }
    } else {
        echo "لا يوجد موظفين ميدانيين صريحين في النظام\n";
    }

    echo "\n=== فحص جميع الموظفين في موقع خالد ===\n";
    if ($khaled->location_id) {
        $sameLocationEmployees = Employee::where('location_id', $khaled->location_id)
            ->where('id', '!=', $khaled->id)
            ->get();

        if ($sameLocationEmployees->count() > 0) {
            echo "الموظفين في نفس الموقع ({$khaled->location->name}):\n";
            foreach ($sameLocationEmployees as $emp) {
                echo "- {$emp->name} (ID: {$emp->id}) - الدور: {$emp->role}\n";
            }
        } else {
            echo "لا يوجد موظفين آخرين في نفس الموقع\n";
        }
    } else {
        echo "خالد غير مرتبط بموقع محدد\n";
    }
} else {
    echo "لم يتم العثور على موظف بالاسم 'خالد جمال منصور'\n";

    echo "\nالبحث عن أي موظف يحتوي على 'خالد':\n";
    $khaledVariants = Employee::where('name', 'LIKE', '%خالد%')->get();
    foreach ($khaledVariants as $emp) {
        echo "- {$emp->name} (ID: {$emp->id}) - الدور: {$emp->role}\n";
    }
}

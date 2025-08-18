<?php

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Illuminate\Http\Request::capture();
$response = $kernel->handle($request);

use App\Models\Employee;

$emad = Employee::with('location')->where('name', 'عماد الحميدي')->first();
echo "عماد الحميدي: ID={$emad->id}, location_id={$emad->location_id}, location=" . ($emad->location ? $emad->location->name : 'غير محدد') . "\n";

// أيضاً دعني أتحقق من موظف في طريق الملك فهد
$employeesInFahd = Employee::with('location')->where('location_id', 4)->get();
echo "\nالموظفين في طريق الملك فهد:\n";
foreach ($employeesInFahd as $emp) {
    echo "- {$emp->name} (ID: {$emp->id})\n";
}

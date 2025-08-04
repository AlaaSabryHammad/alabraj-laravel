<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Employee;

try {
    $employee = new Employee();
    $employee->name = 'Test Employee Direct';
    $employee->position = 'Test Position';
    $employee->department = 'Test Department';
    $employee->email = 'test-direct2@test.com';
    $employee->phone = '123456789';
    $employee->hire_date = now();
    $employee->salary = 5000;
    $employee->national_id = '9876543210';
    $employee->role = 'عامل';
    $employee->sponsorship = 'شركة الأبراج للمقاولات المحدودة';
    $employee->category = 'A';
    $employee->status = 'active';

    $result = $employee->save();

    echo "Employee created: " . ($result ? 'SUCCESS' : 'FAILED') . PHP_EOL;
    echo "Employee ID: " . $employee->id . PHP_EOL;
    echo "Total employees now: " . Employee::count() . PHP_EOL;

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
    echo "Trace: " . $e->getTraceAsString() . PHP_EOL;
}

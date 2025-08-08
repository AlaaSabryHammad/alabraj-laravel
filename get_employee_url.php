<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$employee = \App\Models\Employee::where('name', 'يوسف نواف العبدلي')->first();
if ($employee) {
    echo "ID الموظف: {$employee->id}\n";
    echo "URL: http://127.0.0.1:8000/employees/{$employee->id}\n";
} else {
    echo "لم يتم العثور على الموظف\n";
}

<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Illuminate\Http\Request::capture();
$kernel->bootstrap();

use App\Models\Employee;
use App\Models\EmployeeBalance;
use Illuminate\Support\Facades\Auth;

echo "=== Creating Test Balance Data for Employees ===" . PHP_EOL;

// Get first 5 employees
$employees = Employee::limit(5)->get();

foreach ($employees as $index => $employee) {
    echo "\n=== Employee: {$employee->name} ===" . PHP_EOL;
    
    // Clear existing balances
    EmployeeBalance::where('employee_id', $employee->id)->delete();
    echo "- Cleared existing balances" . PHP_EOL;
    
    // Create different balance scenarios
    switch ($index % 3) {
        case 0:
            // Employee with credit balance
            EmployeeBalance::create([
                'employee_id' => $employee->id,
                'type' => 'credit',
                'amount' => 2500,
                'notes' => 'مكافأة أداء متميز',
                'transaction_date' => now(),
                'created_by' => 1
            ]);
            
            EmployeeBalance::create([
                'employee_id' => $employee->id,
                'type' => 'debit',
                'amount' => 800,
                'notes' => 'خصم تأخير',
                'transaction_date' => now(),
                'created_by' => 1
            ]);
            
            echo "- Created credit balance scenario (Net: +1700 SAR)" . PHP_EOL;
            break;
            
        case 1:
            // Employee with debit balance
            EmployeeBalance::create([
                'employee_id' => $employee->id,
                'type' => 'credit',
                'amount' => 500,
                'notes' => 'بدل مواصلات',
                'transaction_date' => now(),
                'created_by' => 1
            ]);
            
            EmployeeBalance::create([
                'employee_id' => $employee->id,
                'type' => 'debit',
                'amount' => 1200,
                'notes' => 'سلفة راتب',
                'transaction_date' => now(),
                'created_by' => 1
            ]);
            
            echo "- Created debit balance scenario (Net: -700 SAR)" . PHP_EOL;
            break;
            
        case 2:
            // Employee with zero balance
            EmployeeBalance::create([
                'employee_id' => $employee->id,
                'type' => 'credit',
                'amount' => 1000,
                'notes' => 'مكافأة شهرية',
                'transaction_date' => now(),
                'created_by' => 1
            ]);
            
            EmployeeBalance::create([
                'employee_id' => $employee->id,
                'type' => 'debit',
                'amount' => 1000,
                'notes' => 'استقطاع تأمينات',
                'transaction_date' => now(),
                'created_by' => 1
            ]);
            
            echo "- Created zero balance scenario (Net: 0 SAR)" . PHP_EOL;
            break;
    }
}

echo "\n✅ Test balance data created successfully!" . PHP_EOL;
echo "Now visit the payroll page and select employees to see balance buttons" . PHP_EOL;

<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Employee;
use App\Models\EmployeeBalance;
use Carbon\Carbon;

echo "=== Testing Employee Balance Data ===\n";

// Get a few employees to test
$employees = Employee::where('status', 'active')->limit(3)->get();

foreach ($employees as $employee) {
    echo "\n--- Employee: {$employee->name} (ID: {$employee->id}) ---\n";
    
    // Check current balances
    $credits = $employee->balances()->where('type', 'credit')->sum('amount');
    $debits = $employee->balances()->where('type', 'debit')->sum('amount');
    $net = $credits - $debits;
    
    echo "Credit Balance: {$credits}\n";
    echo "Debit Balance: {$debits}\n";
    echo "Net Balance: {$net}\n";
    
    // Check recent transactions
    $recentTransactions = $employee->balances()->orderBy('created_at', 'desc')->limit(2)->get();
    echo "Recent Transactions:\n";
    foreach ($recentTransactions as $transaction) {
        echo "  - {$transaction->type}: {$transaction->amount} ({$transaction->notes}) - {$transaction->created_at->format('Y-m-d')}\n";
    }
    
    // If no balances exist, create sample data
    if ($employee->balances()->count() == 0) {
        echo "No balance data found. Creating sample data...\n";
        
        // Add a credit transaction
        EmployeeBalance::create([
            'employee_id' => $employee->id,
            'type' => 'credit',
            'amount' => 1500.00,
            'notes' => 'بدل عمل إضافي',
            'transaction_date' => Carbon::now()->subDays(5),
            'created_by' => 1
        ]);
        
        // Add a debit transaction
        EmployeeBalance::create([
            'employee_id' => $employee->id,
            'type' => 'debit',
            'amount' => 500.00,
            'notes' => 'خصم غياب',
            'transaction_date' => Carbon::now()->subDays(2),
            'created_by' => 1
        ]);
        
        echo "Sample data created!\n";
        
        // Recalculate
        $credits = $employee->balances()->where('type', 'credit')->sum('amount');
        $debits = $employee->balances()->where('type', 'debit')->sum('amount');
        $net = $credits - $debits;
        
        echo "Updated - Credit: {$credits}, Debit: {$debits}, Net: {$net}\n";
    }
}

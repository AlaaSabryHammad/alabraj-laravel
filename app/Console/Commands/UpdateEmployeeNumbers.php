<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Employee;

class UpdateEmployeeNumbers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'employees:update-numbers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update employee numbers for existing employees';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $employees = Employee::whereNull('employee_number')
            ->orWhere('employee_number', '')
            ->get();

        $this->info("Found {$employees->count()} employees with empty employee numbers");

        $updated = 0;
        $bar = $this->output->createProgressBar($employees->count());
        $bar->start();

        foreach ($employees as $employee) {
            $employee->employee_number = str_pad($employee->id, 3, '0', STR_PAD_LEFT);
            $employee->save();
            $updated++;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Successfully updated {$updated} employees with employee numbers");

        return 0;
    }
}

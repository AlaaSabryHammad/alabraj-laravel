<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Attendance;
use App\Models\Employee;
use Carbon\Carbon;

echo "=== Checking and Creating Overtime Hours Data ===\n";

// Check current month's data
$currentMonth = Carbon::now()->format('Y-m');
$startOfMonth = Carbon::createFromFormat('Y-m', $currentMonth)->startOfMonth();
$endOfMonth = Carbon::createFromFormat('Y-m', $currentMonth)->endOfMonth();

echo "Checking month: {$currentMonth}\n";
echo "Period: {$startOfMonth->format('Y-m-d')} to {$endOfMonth->format('Y-m-d')}\n\n";

// Get all attendance records for current month
$attendanceRecords = Attendance::whereBetween('date', [$startOfMonth, $endOfMonth])
    ->with('employee')
    ->get();

$totalRecords = $attendanceRecords->count();
$recordsWithOvertime = $attendanceRecords->where('overtime_hours', '>', 0)->count();

echo "Total attendance records: {$totalRecords}\n";
echo "Records with overtime: {$recordsWithOvertime}\n";

if ($recordsWithOvertime < ($totalRecords * 0.1)) { // If less than 10% have overtime
    echo "\nCreating realistic overtime data...\n";
    
    $employees = Employee::where('status', 'active')->get();
    $overtimeCreated = 0;
    
    foreach ($employees as $employee) {
        // Get employee's attendance for the month
        $employeeAttendances = $attendanceRecords->where('employee_id', $employee->id);
        
        foreach ($employeeAttendances as $attendance) {
            // Randomly assign overtime (30% chance)
            if (rand(1, 100) <= 30 && $attendance->overtime_hours == 0) {
                // Generate realistic overtime (0.5 to 4 hours)
                $overtimeHours = (rand(1, 8) * 0.5);
                
                // Only add overtime for present or late status
                if (in_array($attendance->status, ['present', 'late'])) {
                    $attendance->update([
                        'overtime_hours' => $overtimeHours
                    ]);
                    
                    echo "Added {$overtimeHours}h overtime for {$employee->name} on {$attendance->date->format('Y-m-d')}\n";
                    $overtimeCreated++;
                }
            }
        }
    }
    
    echo "\nTotal overtime records created: {$overtimeCreated}\n";
} else {
    echo "\nSufficient overtime data already exists.\n";
}

// Show final statistics
$updatedRecords = Attendance::whereBetween('date', [$startOfMonth, $endOfMonth])
    ->where('overtime_hours', '>', 0)
    ->get();

echo "\n=== Final Statistics ===\n";
echo "Records with overtime now: " . $updatedRecords->count() . "\n";

foreach ($updatedRecords->take(10) as $record) {
    echo "- {$record->employee->name}: {$record->overtime_hours}h on {$record->date->format('Y-m-d')}\n";
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing attendance data
        DB::table('attendances')->delete();
        
        $employees = Employee::all();
        $startDate = Carbon::parse('2025-01-01');
        $endDate = Carbon::now(); // Current date
        
        $this->command->info('Generating attendance data from ' . $startDate->format('Y-m-d') . ' to ' . $endDate->format('Y-m-d'));
        $this->command->info('Total employees: ' . $employees->count());
        
        $totalDays = $startDate->diffInDays($endDate) + 1;
        $totalRecords = $employees->count() * $totalDays;
        
        $this->command->info("Generating {$totalRecords} attendance records...");
        
        $attendanceData = [];
        $batchSize = 1000;
        $recordCount = 0;
        
        foreach ($employees as $employee) {
            $currentDate = $startDate->copy();
            
            while ($currentDate->lte($endDate)) {
                // Skip Fridays (weekend in Saudi Arabia) 
                if ($currentDate->dayOfWeek !== Carbon::FRIDAY) {
                    $attendance = $this->generateAttendanceRecord($employee->id, $currentDate->copy());
                    $attendanceData[] = $attendance;
                    $recordCount++;
                    
                    // Insert in batches to avoid memory issues
                    if (count($attendanceData) >= $batchSize) {
                        DB::table('attendances')->insert($attendanceData);
                        $attendanceData = [];
                        $this->command->info("Inserted {$recordCount} records...");
                    }
                }
                
                $currentDate->addDay();
            }
        }
        
        // Insert remaining records
        if (!empty($attendanceData)) {
            DB::table('attendances')->insert($attendanceData);
        }
        
        $this->command->info("Successfully generated {$recordCount} attendance records!");
    }
    
    /**
     * Generate a single attendance record for an employee on a specific date
     */
    private function generateAttendanceRecord(int $employeeId, Carbon $date): array
    {
        // Define probabilities for different scenarios
        $scenario = rand(1, 100);
        
        if ($scenario <= 5) { // 5% absent
            return [
                'employee_id' => $employeeId,
                'date' => $date->format('Y-m-d'),
                'check_in' => null,
                'check_out' => null,
                'status' => 'absent',
                'notes' => $this->getRandomAbsentReason(),
                'late_minutes' => 0,
                'working_hours' => 0,
                'overtime_hours' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        if ($scenario <= 8) { // 3% on leave
            return [
                'employee_id' => $employeeId,
                'date' => $date->format('Y-m-d'),
                'check_in' => null,
                'check_out' => null,
                'status' => rand(1, 2) === 1 ? 'leave' : 'sick_leave',
                'notes' => rand(1, 2) === 1 ? 'إجازة اعتيادية' : 'إجازة مرضية',
                'late_minutes' => 0,
                'working_hours' => 0,
                'overtime_hours' => 0,
                'created_at' => now(),
                'updated_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        // Generate working day attendance
        $baseCheckIn = Carbon::parse('08:00');
        $baseCheckOut = Carbon::parse('17:00');
        
        // Add some variation to check-in time (-30 min to +60 min)
        $checkInVariation = rand(-30, 60);
        $checkIn = $baseCheckIn->copy()->addMinutes($checkInVariation);
        
        // Determine if late
        $lateMinutes = max(0, $checkInVariation);
        $status = $lateMinutes > 15 ? 'late' : 'present';
        
        // Generate check-out time with overtime possibility
        $overtimeChance = rand(1, 100);
        $overtimeHours = 0;
        
        if ($overtimeChance <= 30) { // 30% chance of overtime
            $overtimeMinutes = rand(60, 240); // 1-4 hours overtime
            $checkOut = $baseCheckOut->copy()->addMinutes($overtimeMinutes);
            $overtimeHours = round($overtimeMinutes / 60, 2);
        } else {
            // Normal working hours with slight variation
            $checkOutVariation = rand(-30, 60);
            $checkOut = $baseCheckOut->copy()->addMinutes($checkOutVariation);
        }
        
        // Calculate working hours (excluding 1 hour lunch break)
        $totalMinutes = $checkOut->diffInMinutes($checkIn);
        $workingHours = max(0, ($totalMinutes - 60) / 60); // Subtract 1 hour lunch break
        
        // Adjust overtime hours based on actual working time
        if ($workingHours > 8) {
            $overtimeHours = round($workingHours - 8, 2);
        }
        
        // Add some randomness for sick days, half days, etc.
        if (rand(1, 100) <= 2) { // 2% half day
            $workingHours = 4;
            $overtimeHours = 0;
            $checkOut = $checkIn->copy()->addHours(4);
        }
        
        return [
            'employee_id' => $employeeId,
            'date' => $date->format('Y-m-d'),
            'check_in' => $checkIn->format('H:i:s'),
            'check_out' => $checkOut->format('H:i:s'),
            'status' => $status,
            'notes' => $this->getRandomNotes($status, $workingHours, $overtimeHours),
            'late_minutes' => $lateMinutes,
            'working_hours' => round($workingHours, 2),
            'overtime_hours' => $overtimeHours,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
    
    /**
     * Get random absent reason
     */
    private function getRandomAbsentReason(): string
    {
        $reasons = [
            'غياب بدون إذن',
            'ظروف شخصية',
            'حالة طارئة',
            'مرض مفاجئ',
            'ظروف عائلية'
        ];
        
        return $reasons[array_rand($reasons)];
    }
    
    /**
     * Get random notes based on status and working hours
     */
    private function getRandomNotes(string $status, float $workingHours, float $overtimeHours = 0): ?string
    {
        if ($status === 'late') {
            $lateReasons = [
                'تأخير في المواصلات',
                'ازدحام مروري',
                'ظروف شخصية',
                'موعد طبي',
                'عذر مقبول',
                null // No note sometimes
            ];
            return $lateReasons[array_rand($lateReasons)];
        }
        
        if ($overtimeHours > 0) {
            $overtimeReasons = [
                'عمل إضافي لإنهاء المشروع',
                'اجتماعات مطولة',
                'ساعات إضافية مطلوبة',
                'عمل إضافي اختياري',
                'إنهاء مهام عاجلة',
                'تغطية نقص في العمالة',
                'عمل إضافي مدفوع الأجر',
                null
            ];
            return $overtimeReasons[array_rand($overtimeReasons)];
        }
        
        // Random general notes (or null for most records)
        if (rand(1, 100) <= 10) { // 10% chance of having a note
            $generalNotes = [
                'يوم عمل عادي',
                'أداء ممتاز',
                'حضور منتظم',
                'التزام بالمواعيد',
                'إنجاز جيد للمهام',
                'تعاون مع الفريق'
            ];
            return $generalNotes[array_rand($generalNotes)];
        }
        
        return null;
    }
}
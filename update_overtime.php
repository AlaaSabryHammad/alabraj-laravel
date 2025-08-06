<?php

use Illuminate\Support\Facades\DB;
use App\Models\Attendance;

// Update attendance records with realistic overtime hours
$attendances = Attendance::whereBetween('date', ['2025-01-01', '2025-08-06'])
    ->whereIn('status', ['present', 'late'])
    ->get();

$updateCount = 0;
$totalRecords = $attendances->count();

echo "Found {$totalRecords} attendance records to process...\n";

foreach ($attendances as $attendance) {
    // 35% chance of having overtime
    if (rand(1, 100) <= 35) {
        // Generate realistic working hours (8-12 hours)
        $baseHours = 8 + (rand(-30, 30) / 60); // 7.5 to 8.5 base hours
        $overtimeHours = 0.5 + (rand(0, 350) / 100); // 0.5 to 4 overtime hours
        $totalHours = $baseHours + $overtimeHours;
        
        // Generate realistic overtime notes
        $overtimeReasons = [
            'عمل إضافي لإنهاء المشروع',
            'اجتماعات مطولة',
            'ساعات إضافية مطلوبة',
            'عمل إضافي اختياري',
            'تسليم مهام عاجلة',
            'دعم فني إضافي',
            'إنهاء التقارير الشهرية',
            'العمل في أوقات الذروة',
            'دوام إضافي بطلب الإدارة'
        ];
        
        $overtimeNote = $overtimeReasons[array_rand($overtimeReasons)];
        $newNote = $attendance->notes ? $attendance->notes . ' - ' . $overtimeNote : $overtimeNote;
        
        // Update the record
        $attendance->update([
            'working_hours' => round($totalHours, 2),
            'notes' => $newNote
        ]);
        
        $updateCount++;
    }
}

echo "✅ Successfully updated {$updateCount} records with overtime hours!\n";
echo "📊 Percentage updated: " . round(($updateCount / $totalRecords) * 100, 1) . "%\n";

// Show final statistics
$overtimeRecords = Attendance::whereBetween('date', ['2025-01-01', '2025-08-06'])
    ->where('working_hours', '>', 8)
    ->count();

$totalOvertimeHours = Attendance::whereBetween('date', ['2025-01-01', '2025-08-06'])
    ->where('working_hours', '>', 8)
    ->get()
    ->sum(function ($attendance) {
        return max(0, floatval($attendance->working_hours) - 8);
    });

echo "📈 Records with overtime (>8 hours): {$overtimeRecords}\n";
echo "🕒 Total overtime hours generated: " . round($totalOvertimeHours, 2) . "\n";

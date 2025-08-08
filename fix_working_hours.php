<?php
require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "بدء إصلاح ساعات العمل...\n";

// Get all attendance records that have check_in and check_out
$records = DB::table('attendances')
    ->whereNotNull('check_in')
    ->whereNotNull('check_out')
    ->whereIn('status', ['present', 'late'])
    ->get();

echo "العثور على " . $records->count() . " سجل للتحديث...\n";

$updatedCount = 0;

foreach ($records as $record) {
    $checkIn = Carbon::parse($record->check_in);
    $checkOut = Carbon::parse($record->check_out);
    
    // Calculate total minutes worked
    $totalMinutes = $checkOut->diffInMinutes($checkIn);
    
    // Subtract 1 hour lunch break and convert to hours
    $workingHours = max(0, ($totalMinutes - 60) / 60);
    
    // Calculate overtime (anything over 8 hours is overtime)
    $overtimeHours = max(0, $workingHours - 8);
    
    // Update the record
    DB::table('attendances')
        ->where('id', $record->id)
        ->update([
            'working_hours' => round($workingHours, 2),
            'overtime_hours' => round($overtimeHours, 2)
        ]);
    
    $updatedCount++;
    
    if ($updatedCount % 1000 == 0) {
        echo "تم تحديث $updatedCount سجل...\n";
    }
}

echo "تم تحديث $updatedCount سجل بنجاح!\n";

// Show updated statistics
echo "\n=== الإحصائيات المحدثة ===\n";
echo "إجمالي ساعات العمل: " . DB::table('attendances')->sum('working_hours') . "\n";
echo "إجمالي ساعات العمل الإضافي: " . DB::table('attendances')->sum('overtime_hours') . "\n";
echo "متوسط ساعات العمل اليومية: " . round(DB::table('attendances')->where('working_hours', '>', 0)->avg('working_hours'), 2) . "\n";
echo "أعلى ساعات عمل في يوم واحد: " . DB::table('attendances')->max('working_hours') . "\n";
?>

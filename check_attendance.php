<?php
require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== إحصائيات الحضور المولدة ===\n";
echo "إجمالي سجلات الحضور: " . DB::table('attendances')->count() . "\n";
echo "الموظفون الحاضرون: " . DB::table('attendances')->where('status', 'present')->count() . "\n";
echo "الموظفون المتأخرون: " . DB::table('attendances')->where('status', 'late')->count() . "\n";
echo "الموظفون الغائبون: " . DB::table('attendances')->where('status', 'absent')->count() . "\n";
echo "الإجازات: " . DB::table('attendances')->whereIn('status', ['leave', 'sick_leave'])->count() . "\n";
echo "إجمالي ساعات العمل الإضافي: " . DB::table('attendances')->sum('overtime_hours') . "\n";
echo "متوسط ساعات العمل اليومية: " . round(DB::table('attendances')->where('working_hours', '>', 0)->avg('working_hours'), 2) . "\n\n";

echo "=== عينة من السجلات الحديثة ===\n";
$recentRecords = DB::table('attendances')
    ->join('employees', 'attendances.employee_id', '=', 'employees.id')
    ->select('employees.name', 'attendances.date', 'attendances.check_in', 'attendances.check_out', 
             'attendances.status', 'attendances.working_hours', 'attendances.overtime_hours', 'attendances.notes')
    ->where('attendances.date', '>=', '2025-08-01')
    ->limit(15)
    ->get();

foreach ($recentRecords as $record) {
    echo sprintf(
        "%s - %s - حضور: %s - انصراف: %s - حالة: %s - ساعات: %s - إضافي: %s - ملاحظات: %s\n",
        $record->name,
        $record->date,
        $record->check_in ?? 'لا يوجد',
        $record->check_out ?? 'لا يوجد',
        $record->status,
        $record->working_hours,
        $record->overtime_hours,
        $record->notes ?? 'لا توجد'
    );
}

echo "\n=== أعلى ساعات عمل إضافي هذا الشهر ===\n";
$topOvertime = DB::table('attendances')
    ->join('employees', 'attendances.employee_id', '=', 'employees.id')
    ->select('employees.name', 'attendances.date', 'attendances.overtime_hours', 'attendances.notes')
    ->where('attendances.date', '>=', '2025-08-01')
    ->where('attendances.overtime_hours', '>', 0)
    ->orderBy('attendances.overtime_hours', 'desc')
    ->limit(10)
    ->get();

foreach ($topOvertime as $record) {
    echo sprintf(
        "%s - %s - ساعات إضافية: %s - ملاحظات: %s\n",
        $record->name,
        $record->date,
        $record->overtime_hours,
        $record->notes ?? 'لا توجد'
    );
}
?>

<?php
require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== تقرير شامل لبيانات الحضور المولدة ===\n\n";

// Overall statistics
$overallStats = DB::selectOne("
    SELECT 
        COUNT(*) as total_records,
        COUNT(CASE WHEN status = 'present' THEN 1 END) as present_count,
        COUNT(CASE WHEN status = 'late' THEN 1 END) as late_count,
        COUNT(CASE WHEN status = 'absent' THEN 1 END) as absent_count,
        COUNT(CASE WHEN status IN ('leave', 'sick_leave') THEN 1 END) as leave_count,
        ROUND(SUM(working_hours), 2) as total_working_hours,
        ROUND(SUM(overtime_hours), 2) as total_overtime_hours,
        ROUND(AVG(CASE WHEN working_hours > 0 THEN working_hours END), 2) as avg_working_hours,
        ROUND(MAX(working_hours), 2) as max_working_hours,
        MIN(date) as start_date,
        MAX(date) as end_date
    FROM attendances
");

echo "إجمالي الإحصائيات:\n";
echo "- إجمالي السجلات: " . number_format($overallStats->total_records) . "\n";
echo "- الفترة الزمنية: من " . $overallStats->start_date . " إلى " . $overallStats->end_date . "\n";
echo "- الحضور العادي: " . number_format($overallStats->present_count) . " (" . round($overallStats->present_count / $overallStats->total_records * 100, 1) . "%)\n";
echo "- التأخير: " . number_format($overallStats->late_count) . " (" . round($overallStats->late_count / $overallStats->total_records * 100, 1) . "%)\n";
echo "- الغياب: " . number_format($overallStats->absent_count) . " (" . round($overallStats->absent_count / $overallStats->total_records * 100, 1) . "%)\n";
echo "- الإجازات: " . number_format($overallStats->leave_count) . " (" . round($overallStats->leave_count / $overallStats->total_records * 100, 1) . "%)\n";
echo "- إجمالي ساعات العمل: " . number_format($overallStats->total_working_hours) . " ساعة\n";
echo "- إجمالي ساعات العمل الإضافي: " . number_format($overallStats->total_overtime_hours) . " ساعة\n";
echo "- متوسط ساعات العمل اليومية: " . $overallStats->avg_working_hours . " ساعة\n";
echo "- أعلى ساعات عمل في يوم واحد: " . $overallStats->max_working_hours . " ساعة\n\n";

// Monthly breakdown
echo "=== التوزيع الشهري ===\n";
$monthlyStats = DB::select("
    SELECT 
        DATE_FORMAT(date, '%Y-%m') as month,
        COUNT(*) as total_records,
        COUNT(CASE WHEN status IN ('present', 'late') THEN 1 END) as working_days,
        ROUND(SUM(working_hours), 2) as total_hours,
        ROUND(SUM(overtime_hours), 2) as overtime_hours
    FROM attendances 
    GROUP BY DATE_FORMAT(date, '%Y-%m')
    ORDER BY month
");

foreach ($monthlyStats as $month) {
    echo sprintf(
        "%s: %s يوم عمل من أصل %s سجل - %s ساعة عمل (%s إضافي)\n",
        $month->month,
        number_format($month->working_days),
        number_format($month->total_records),
        number_format($month->total_hours),
        number_format($month->overtime_hours)
    );
}

echo "\n=== أعلى 10 موظفين في ساعات العمل الإضافي ===\n";
$topOvertimeEmployees = DB::select("
    SELECT 
        e.name,
        ROUND(SUM(a.overtime_hours), 2) as total_overtime,
        COUNT(CASE WHEN a.overtime_hours > 0 THEN 1 END) as overtime_days,
        ROUND(AVG(CASE WHEN a.overtime_hours > 0 THEN a.overtime_hours END), 2) as avg_overtime_per_day
    FROM attendances a
    JOIN employees e ON a.employee_id = e.id
    GROUP BY a.employee_id, e.name
    HAVING total_overtime > 0
    ORDER BY total_overtime DESC
    LIMIT 10
");

foreach ($topOvertimeEmployees as $emp) {
    echo sprintf(
        "%s: %.2f ساعة إضافية إجمالي (%.2f ساعة في المتوسط عبر %d يوم)\n",
        $emp->name,
        $emp->total_overtime,
        $emp->avg_overtime_per_day ?? 0,
        $emp->overtime_days
    );
}

echo "\n=== إحصائيات الحضور والغياب ===\n";
$attendanceStats = DB::select("
    SELECT 
        status,
        COUNT(*) as count,
        ROUND(COUNT(*) * 100.0 / (SELECT COUNT(*) FROM attendances), 2) as percentage
    FROM attendances 
    GROUP BY status
    ORDER BY count DESC
");

foreach ($attendanceStats as $stat) {
    $statusName = [
        'present' => 'حضور عادي',
        'late' => 'تأخير',
        'absent' => 'غياب',
        'leave' => 'إجازة اعتيادية',
        'sick_leave' => 'إجازة مرضية',
        'excused' => 'غياب بعذر'
    ][$stat->status] ?? $stat->status;
    
    echo sprintf(
        "%s: %s سجل (%.2f%%)\n",
        $statusName,
        number_format($stat->count),
        $stat->percentage
    );
}

echo "\n=== أيام بأعلى نشاط (ساعات عمل إضافي) ===\n";
$topOvertimeDays = DB::select("
    SELECT 
        date,
        COUNT(CASE WHEN overtime_hours > 0 THEN 1 END) as employees_with_overtime,
        ROUND(SUM(overtime_hours), 2) as total_overtime_day
    FROM attendances 
    WHERE overtime_hours > 0
    GROUP BY date
    ORDER BY total_overtime_day DESC
    LIMIT 10
");

foreach ($topOvertimeDays as $day) {
    echo sprintf(
        "%s: %d موظف عملوا %s ساعة إضافية\n",
        $day->date,
        $day->employees_with_overtime,
        number_format($day->total_overtime_day)
    );
}

echo "\n=== العينة الأخيرة من البيانات (آخر 5 أيام) ===\n";
$recentSample = DB::table('attendances')
    ->join('employees', 'attendances.employee_id', '=', 'employees.id')
    ->select('employees.name', 'attendances.date', 'attendances.check_in', 'attendances.check_out', 
             'attendances.status', 'attendances.working_hours', 'attendances.overtime_hours', 'attendances.notes')
    ->where('attendances.date', '>=', '2025-08-03')
    ->where('attendances.status', 'present')
    ->orderBy('attendances.date', 'desc')
    ->orderBy('attendances.working_hours', 'desc')
    ->limit(15)
    ->get();

foreach ($recentSample as $record) {
    echo sprintf(
        "%s | %s | %s-%s | %s | %.2fس عمل (%.2fس إضافي) | %s\n",
        $record->date,
        substr($record->name, 0, 15),
        $record->check_in,
        $record->check_out,
        $record->status,
        $record->working_hours,
        $record->overtime_hours,
        $record->notes ? substr($record->notes, 0, 20) : 'لا توجد'
    );
}

echo "\n=== تم إنشاء البيانات بنجاح! ===\n";
echo "يمكنك الآن زيارة http://127.0.0.1:8000/employees/attendance/tracker لعرض تقارير الحضور\n";
?>

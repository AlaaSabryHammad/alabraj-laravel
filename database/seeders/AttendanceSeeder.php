<?php

namespace Database\Seeders;


use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = Employee::where('status', 'active')->get();
        $today = Carbon::today();

        foreach ($employees as $employee) {
            $statusOptions = ['present', 'absent', 'late', 'leave'];
            $status = $statusOptions[array_rand($statusOptions)];

            $checkIn = null;
            $checkOut = null;
            $lateMinutes = 0;
            $workingHours = null;

            switch ($status) {
                case 'present':
                    $checkIn = $today->copy()->setTime(random_int(7, 8), random_int(0, 59));
                    $checkOut = $today->copy()->setTime(random_int(16, 18), random_int(0, 59));
                    $workingHours = $checkOut->diffInHours($checkIn, true);
                    break;

                case 'late':
                    $checkIn = $today->copy()->setTime(random_int(8, 10), random_int(0, 59));
                    $checkOut = $today->copy()->setTime(random_int(16, 18), random_int(0, 59));
                    $lateMinutes = max(0, $checkIn->diffInMinutes($today->copy()->setTime(8, 0)));
                    $workingHours = $checkOut->diffInHours($checkIn, true);
                    break;

                case 'absent':
                    // No check-in or check-out for absent employees
                    break;

                case 'leave':
                    // On leave - no check-in or check-out
                    break;
            }

            Attendance::create([
                'employee_id' => $employee->id,
                'date' => $today,
                'check_in' => $checkIn ? $checkIn->format('H:i') : null,
                'check_out' => $checkOut ? $checkOut->format('H:i') : null,
                'status' => $status,
                'late_minutes' => $lateMinutes,
                'working_hours' => $workingHours,
                'notes' => $this->getRandomNote($status),
            ]);
        }
    }

    private function getRandomNote($status): ?string
    {
        $notes = [
            'present' => ['حضور منتظم', 'وصل في الوقت المحدد', null],
            'late' => ['تأخر بسبب الزحام', 'مشكلة في المواصلات', 'ظروف طارئة'],
            'absent' => ['غياب بدون إذن', 'مرض', 'ظروف عائلية'],
            'leave' => ['إجازة سنوية', 'إجازة طارئة', 'إجازة مرضية']
        ];

        $statusNotes = $notes[$status] ?? [null];
        return $statusNotes[array_rand($statusNotes)];
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Attendance extends Model
{
    protected $fillable = [
        'employee_id',
        'date',
        'check_in',
        'check_out',
        'status',
        'notes',
        'late_minutes',
        'working_hours'
    ];

    protected $casts = [
        'date' => 'date',
        'working_hours' => 'decimal:2'
    ];

    /**
     * Get the employee that owns the attendance record
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Calculate working hours between check-in and check-out
     */
    public function calculateWorkingHours(): float
    {
        if (!$this->check_in || !$this->check_out) {
            return 0;
        }

        $checkIn = Carbon::parse($this->check_in);
        $checkOut = Carbon::parse($this->check_out);

        return $checkOut->diffInHours($checkIn, true);
    }

    /**
     * Calculate late minutes based on standard work start time (8:00 AM)
     */
    public function calculateLateMinutes(): int
    {
        if (!$this->check_in) {
            return 0;
        }

        $standardStartTime = Carbon::parse('08:00');
        $checkInTime = Carbon::parse($this->check_in);

        if ($checkInTime->gt($standardStartTime)) {
            return $checkInTime->diffInMinutes($standardStartTime);
        }

        return 0;
    }

    /**
     * Get status in Arabic
     */
    public function getStatusTextAttribute(): string
    {
        $statusTexts = [
            'present' => 'حاضر',
            'absent' => 'غائب',
            'late' => 'متأخر',
            'leave' => 'إجازة',
            'sick_leave' => 'إجازة مرضية',
            'excused' => 'مُعتذر'
        ];

        return $statusTexts[$this->status] ?? $this->status;
    }
}

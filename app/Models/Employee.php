<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'position',
        'department',
        'email',
        'phone',
        'hire_date',
        'salary',
        'status',
        'role',
        'sponsorship',
        'category',
        'national_id',
        'national_id_expiry_date',
        'address',
        'photo',
        'national_id_photo',
        'passport_number',
        'passport_issue_date',
        'passport_expiry_date',
        'passport_photo',
        'work_permit_number',
        'work_permit_issue_date',
        'work_permit_expiry_date',
        'work_permit_photo',
        'driving_license_issue_date',
        'driving_license_expiry_date',
        'driving_license_photo',
        'location_id',
        'location_assignment_date',
        'user_id',
        'bank_name',
        'iban',
        'birth_date',
        'nationality',
        'religion',
        'medical_insurance_status',
        'location_type',
        'additional_documents',
        'rating',
        'direct_manager_id'
    ];

    protected $casts = [
        'hire_date' => 'date',
        'salary' => 'decimal:2',
        'birth_date' => 'date',
        'national_id_expiry_date' => 'datetime',
        'passport_issue_date' => 'datetime',
        'passport_expiry_date' => 'datetime',
        'work_permit_issue_date' => 'datetime',
        'work_permit_expiry_date' => 'datetime',
        'driving_license_issue_date' => 'datetime',
        'driving_license_expiry_date' => 'datetime',
        'location_assignment_date' => 'datetime',
        'additional_documents' => 'array'
    ];

    // Helper method to check if a date field is expired
    public function isDateExpired($field)
    {
        if (!$this->$field) {
            return false;
        }
        return $this->$field->isPast();
    }

    // Helper method to get days until expiry
    public function getDaysUntilExpiry($field)
    {
        if (!$this->$field) {
            return null;
        }
        return round($this->$field->diffInDays());
    }

    // Location relationship
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    // User relationship
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Equipment relationship - equipment that this employee drives
    public function drivenEquipment()
    {
        return $this->hasMany(Equipment::class, 'driver_id');
    }

    // Attendance relationship
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    // Get today's attendance
    public function todayAttendance()
    {
        return $this->attendances()->whereDate('date', today());
    }

    // التقارير السرية للموظف
    public function reports()
    {
        return $this->hasMany(EmployeeReport::class);
    }

    // المدير المباشر
    public function directManager()
    {
        return $this->belongsTo(Employee::class, 'direct_manager_id');
    }

    // الموظفون التابعون (المرؤوسون)
    public function subordinates()
    {
        return $this->hasMany(Employee::class, 'direct_manager_id');
    }

    // تعيينات المدير - سجل التعيينات
    public function managerAssignments()
    {
        return $this->hasMany(ManagerAssignment::class, 'employee_id')->orderBy('assigned_at', 'desc');
    }

    // تعيينات المدير التي قام بها هذا الموظف
    public function assignmentsMade()
    {
        return $this->hasMany(ManagerAssignment::class, 'assigned_by')->orderBy('assigned_at', 'desc');
    }

    // علاقة مع سجلات مسيرات الرواتب
    public function payrollEmployees()
    {
        return $this->hasMany(PayrollEmployee::class);
    }
}

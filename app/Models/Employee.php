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
        'sponsorship_status',
        'category',
        'national_id',
        'national_id_expiry',
        'driving_license_issue_date',
        'driving_license_expiry_date',
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
        'driving_license_expiry',
        'driving_license_photo',
        'location_id',

        'user_id',
        'bank_name',
        'bank_account_number',
        'iban',
        'birth_date',
        'nationality',
        'marital_status',
        'children_count',
        'religion',


        'additional_documents',
        'rating',
        'direct_manager_id',
        'working_hours',
        'contract_start',
        'contract_end',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relationship',
    ];

    protected $casts = [
        'hire_date' => 'date',
        'salary' => 'decimal:2',
        'birth_date' => 'date',
        'national_id_expiry' => 'date',
        'passport_issue_date' => 'date',
        'passport_expiry_date' => 'date',
        'work_permit_issue_date' => 'date',
        'work_permit_expiry_date' => 'date',
        'driving_license_issue_date' => 'date',
        'driving_license_issue_date' => 'date',
        'driving_license_expiry' => 'date',

        'contract_start' => 'date',
        'contract_end' => 'date',
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

    // Helper method to get document status
    public function getDocumentStatus($field)
    {
        if (!$this->$field) {
            return ['status' => 'غير محدد', 'class' => 'bg-gray-100 text-gray-800'];
        }

        // Check if expired (past date)
        if ($this->$field->isPast()) {
            return ['status' => 'منتهي الصلاحية', 'class' => 'bg-red-100 text-red-800'];
        }

        // Check if expires soon (within 30 days from now)
        $daysUntilExpiry = now()->diffInDays($this->$field, false);
        if ($daysUntilExpiry >= 0 && $daysUntilExpiry <= 30) {
            return ['status' => 'ينتهي قريباً', 'class' => 'bg-yellow-100 text-yellow-800'];
        }

        // Valid (more than 30 days remaining)
        return ['status' => 'ساري', 'class' => 'bg-green-100 text-green-800'];
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

    // Internal Trucks relationship - trucks that this employee drives
    public function internalTrucks()
    {
        return $this->hasMany(\App\Models\InternalTruck::class, 'driver_id');
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

    // علاقة مع أرصدة الموظف
    public function balances()
    {
        return $this->hasMany(EmployeeBalance::class);
    }

    // حساب صافي الرصيد
    public function getNetBalance()
    {
        $credits = $this->balances()->where('type', 'credit')->sum('amount');
        $debits = $this->balances()->where('type', 'debit')->sum('amount');
        return $credits - $debits;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    /**
     * Hierarchical chain mapping: current role => required direct manager role
     */
    public const MANAGER_CHAIN = [
        // Arabic roles
        'عامل' => 'مشرف موقع',
        'مشرف موقع' => 'مهندس',
        'مهندس' => 'مدير مشاريع',
        'مدير مشاريع' => 'مدير',
        'مدير' => 'مدير عام',
        'محاسب' => 'مدير مالي',
        'مشغل معدة' => 'مشرف موقع',
        'سائق تانك محروقات' => 'مدير',
        'سائق شاحنة' => 'مدير',
        'امين مستودع' => 'مدير',
        'مدير ورشة' => 'مدير',

        // English roles (matching database)
        'worker' => 'site_manager',
        'site_manager' => 'engineer',
        'engineer' => 'project_manager',
        'project_manager' => 'general_manager',
        'general_manager' => null, // المدير العام في أعلى التسلسل
        'financial_manager' => 'general_manager',
        'hr_manager' => 'general_manager',
        'operations_manager' => 'general_manager',
        'manager' => 'general_manager',
        'accountant' => 'financial_manager',
        'equipment_operator' => 'site_manager',
        'driver' => 'site_manager',
        'security' => 'site_manager',
        'warehouse_manager' => 'manager',
        'workship_manager' => 'manager',
        'fuel_manager' => 'manager',
        'truck_driver' => 'site_manager',
    ];

    /**
     * Translation map (both directions). Keys can be Arabic or English.
     */
    public const ROLE_TRANSLATIONS = [
        'employee' => 'عامل',
        'عامل' => 'عامل',
        'supervisor' => 'مشرف موقع',
        'مشرف موقع' => 'مشرف موقع',
        'engineer' => 'مهندس',
        'مهندس' => 'مهندس',
        'project_manager' => 'مدير مشاريع',
        'مدير مشاريع' => 'مدير مشاريع',
        'manager' => 'مدير',
        'مدير' => 'مدير',
        'general_manager' => 'مدير عام',
        'مدير عام' => 'مدير عام',
        'accountant' => 'محاسب',
        'محاسب' => 'محاسب',
        'equipment_operator' => 'مشغل معدة',
        'مشغل معدة' => 'مشغل معدة',
        'fuel_tank_driver' => 'سائق تانك محروقات',
        'سائق تانك محروقات' => 'سائق تانك محروقات',
        'truck_driver' => 'سائق شاحنة',
        'سائق شاحنة' => 'سائق شاحنة',
        'store_keeper' => 'امين مستودع',
        'امين مستودع' => 'امين مستودع',
        'workshop_manager' => 'مدير ورشة',
        'مدير ورشة' => 'مدير ورشة',
        'admin' => 'إداري',
        'إداري' => 'إداري',
    ];

    /** Normalize any stored role (Arabic or English) to displayed Arabic */
    public static function roleToArabic(?string $role): ?string
    {
        if ($role === null) return null;
        $trimmed = trim($role);
        return self::ROLE_TRANSLATIONS[$trimmed] ?? $trimmed; // fallback to itself
    }

    /** Given an Arabic role, return all possible stored variants (Arabic + English keys). */
    public static function variantsForArabic(string $arabic): array
    {
        $arabic = trim($arabic);
        $variants = [];
        foreach (self::ROLE_TRANSLATIONS as $key => $value) {
            if ($value === $arabic) {
                $variants[] = $key; // include original key (English or Arabic)
            }
        }
        // Always include the arabic itself
        if (!in_array($arabic, $variants, true)) {
            $variants[] = $arabic;
        }
        // Unique & return
        return array_values(array_unique($variants));
    }

    /** Build distinct Arabic roles list from employees table */
    public static function distinctArabicRoles(): \Illuminate\Support\Collection
    {
        $raw = self::query()->select('role')->whereNotNull('role')->pluck('role');
        return $raw->map(fn($r) => self::roleToArabic($r))
            ->filter()
            ->unique()
            ->sort()
            ->values();
    }

    /**
     * Get the required manager role for this employee (or null if top-level)
     */
    public function requiredManagerRole(): ?string
    {
        return self::MANAGER_CHAIN[$this->role] ?? null;
    }
    public function custodies()
    {
        return $this->hasMany(Custody::class);
    }

    public function expenseVouchers()
    {
        return $this->hasMany(ExpenseVoucher::class);
    }
    use HasFactory;

    protected $attributes = [
        'status' => 'inactive',
    ];

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
        'driving_license_expiry',
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

    protected static function boot()
    {
        parent::boot();

        // Auto-generate employee number when creating a new employee
        // static::creating(function ($employee) {
        //     if (empty($employee->employee_number)) {
        //         // Get the next ID that will be assigned
        //         $nextId = Employee::max('id') + 1;
        //         $employee->employee_number = str_pad($nextId, 3, '0', STR_PAD_LEFT);
        //     }
        // });
    }

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

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollEmployee extends Model
{
    use HasFactory;

    protected $fillable = [
        'payroll_id',
        'employee_id',
        'base_salary',
        'total_deductions',
        'total_bonuses',
        'net_salary',
        'is_eligible',
        'notes',
    ];

    protected $casts = [
        'base_salary' => 'decimal:2',
        'total_deductions' => 'decimal:2',
        'total_bonuses' => 'decimal:2',
        'net_salary' => 'decimal:2',
        'is_eligible' => 'boolean',
    ];

    // Relations
    public function payroll()
    {
        return $this->belongsTo(Payroll::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function deductions()
    {
        return $this->hasMany(PayrollDeduction::class);
    }

    public function bonuses()
    {
        return $this->hasMany(PayrollBonus::class);
    }

    // Methods
    public function calculateNetSalary()
    {
        $totalDeductions = $this->deductions()->sum('amount');
        $totalBonuses = $this->bonuses()->sum('amount');

        $netSalary = $this->is_eligible
            ? ($this->base_salary + $totalBonuses - $totalDeductions)
            : 0;

        $this->update([
            'total_deductions' => $totalDeductions,
            'total_bonuses' => $totalBonuses,
            'net_salary' => max(0, $netSalary), // Ensure net salary is not negative
        ]);

        return $this->net_salary;
    }

    public function getFormattedBaseSalaryAttribute()
    {
        return number_format((float)$this->base_salary, 2);
    }

    public function getFormattedNetSalaryAttribute()
    {
        return number_format((float)$this->net_salary, 2);
    }

    public function getFormattedTotalDeductionsAttribute()
    {
        return number_format((float)$this->total_deductions, 2);
    }

    public function getFormattedTotalBonusesAttribute()
    {
        return number_format((float)$this->total_bonuses, 2);
    }
}

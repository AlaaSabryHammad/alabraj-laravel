<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollBonus extends Model
{
    use HasFactory;

    protected $fillable = [
        'payroll_employee_id',
        'type',
        'amount',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    // Relations
    public function payrollEmployee()
    {
        return $this->belongsTo(PayrollEmployee::class);
    }

    // Methods
    public function getFormattedAmountAttribute()
    {
        return number_format((float)$this->amount, 2);
    }

    // Common bonus types
    public static function getCommonTypes()
    {
        return [
            'بدل مواصلات',
            'بدل سكن',
            'بدل اتصالات',
            'بدل طعام',
            'مكافأة أداء',
            'بدل خطر',
            'ساعات إضافية',
            'عمولة',
            'أخرى',
        ];
    }
}

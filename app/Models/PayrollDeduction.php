<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollDeduction extends Model
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

    // Common deduction types
    public static function getCommonTypes()
    {
        return [
            'تأمينات اجتماعية',
            'ضريبة دخل',
            'غياب',
            'تأخير',
            'قرض',
            'تأمين صحي',
            'أخرى',
        ];
    }
}

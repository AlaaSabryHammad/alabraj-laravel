<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExpenseEntity extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'contact_person',
        'phone',
        'email',
        'address',
        'tax_number',
        'commercial_record',
        'status',
        'notes'
    ];

    // علاقة مع سندات الصرف
    public function expenseVouchers(): HasMany
    {
        return $this->hasMany(ExpenseVoucher::class);
    }

    // نطاق للجهات النشطة
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // نص نوع الجهة
    public function getTypeTextAttribute(): string
    {
        return match($this->type) {
            'supplier' => 'مورد',
            'contractor' => 'مقاول',
            'government' => 'جهة حكومية',
            'bank' => 'بنك',
            'employee' => 'موظف',
            'other' => 'أخرى',
            default => 'غير محدد'
        };
    }

    // نص حالة الجهة
    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            'active' => 'نشط',
            'inactive' => 'غير نشط',
            default => 'غير محدد'
        };
    }
}

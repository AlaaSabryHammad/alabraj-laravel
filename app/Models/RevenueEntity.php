<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RevenueEntity extends Model
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
        'status'
    ];

    // العلاقات
    public function revenueVouchers(): HasMany
    {
        return $this->hasMany(RevenueVoucher::class);
    }

    // نص نوع الجهة
    public function getTypeTextAttribute(): string
    {
        return match($this->type) {
            'client' => 'عميل',
            'government' => 'جهة حكومية',
            'company' => 'شركة',
            'individual' => 'فرد',
            default => 'غير محدد'
        };
    }

    // نطاقات
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class RevenueVoucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'voucher_number',
        'voucher_date',
        'revenue_entity_id',
        'amount',
        'description',
        'payment_method',
        'tax_type',
        'project_id',
        'location_id',
        'attachment_path',
        'notes',
        'status',
        'created_by',
        'approved_by',
        'approved_at'
    ];

    protected $casts = [
        'voucher_date' => 'date',
        'amount' => 'decimal:2',
        'approved_at' => 'datetime'
    ];

    // توليد رقم السند التسلسلي
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($voucher) {
            if (empty($voucher->voucher_number)) {
                $year = Carbon::now()->year;
                $lastVoucher = static::whereYear('created_at', $year)
                    ->orderBy('id', 'desc')
                    ->first();
                
                $sequence = $lastVoucher ? (int) substr($lastVoucher->voucher_number, -4) + 1 : 1;
                $voucher->voucher_number = 'RV-' . $year . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    // العلاقات
    public function revenueEntity(): BelongsTo
    {
        return $this->belongsTo(RevenueEntity::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // نص طريقة الدفع
    public function getPaymentMethodTextAttribute(): string
    {
        return match($this->payment_method) {
            'cash' => 'نقداً',
            'bank_transfer' => 'تحويل بنكي',
            'check' => 'شيك',
            'credit_card' => 'بطاقة ائتمانية',
            'other' => 'أخرى',
            default => 'غير محدد'
        };
    }

    // نص نوع الضريبة
    public function getTaxTypeTextAttribute(): string
    {
        return match($this->tax_type) {
            'taxable' => 'ضريبي',
            'non_taxable' => 'غير ضريبي',
            default => 'غير محدد'
        };
    }

    // نص حالة السند
    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            'pending' => 'في الانتظار',
            'approved' => 'معتمد',
            'received' => 'تم الاستلام',
            'cancelled' => 'ملغى',
            default => 'غير محدد'
        };
    }

    // لون حالة السند
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'yellow',
            'approved' => 'blue',
            'received' => 'green',
            'cancelled' => 'red',
            default => 'gray'
        };
    }

    // المبلغ مُنسق
    public function getFormattedAmountAttribute(): string
    {
        return number_format($this->amount, 2) . ' ر.س';
    }

    // نطاقات
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeReceived($query)
    {
        return $query->where('status', 'received');
    }
}
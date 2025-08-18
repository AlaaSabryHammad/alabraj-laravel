<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class ExpenseVoucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'voucher_number',
        'voucher_date',
        'expense_category_id',
        'employee_id',
        'amount',
        'payment_method',
        'tax_type',
        'description',
        'expense_entity_id',
        'project_id',
        'location_id',
        'status',
        'notes',
        'reference_number',
        'attachment_path',
        'created_by',
        'approved_by',
        'approved_at'
    ];

    protected $casts = [
        'voucher_date' => 'date',
        'amount' => 'decimal:2',
        'attachments' => 'array',
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
                $voucher->voucher_number = 'EV-' . $year . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    // العلاقات
    public function expenseCategory(): BelongsTo
    {
        return $this->belongsTo(ExpenseCategory::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function expenseEntity(): BelongsTo
    {
        return $this->belongsTo(ExpenseEntity::class);
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
        return match ($this->payment_method) {
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
        return match ($this->tax_type) {
            'taxable' => 'ضريبي',
            'non_taxable' => 'غير ضريبي',
            default => 'غير محدد'
        };
    }

    // نص حالة السند
    public function getStatusTextAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'في الانتظار',
            'approved' => 'معتمد',
            'cancelled' => 'ملغى',
            default => 'غير محدد'
        };
    }

    // لون حالة السند
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'yellow',
            'approved' => 'green',
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
}

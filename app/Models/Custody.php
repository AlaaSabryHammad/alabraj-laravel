<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class Custody extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'amount',
        'disbursement_date',
        'receipt_method',
        'notes',
        'status',
        'approved_by',
        'approved_at'
    ];

    protected $casts = [
        'disbursement_date' => 'date',
        'amount' => 'decimal:2',
        'approved_at' => 'datetime'
    ];

    protected $attributes = [
        'status' => 'pending'
    ];

    public const STATUS_PENDING = 'pending';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_CLOSED = 'closed';

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(CustodyType::class, 'custody_type_id');
    }

    public function getReceiptMethodTextAttribute(): string
    {
        return match ($this->receipt_method) {
            'cash' => 'نقداً',
            'bank_transfer' => 'تحويل بنكي',
            'check' => 'شيك',
            'other' => 'أخرى',
            default => 'غير محدد'
        };
    }

    public function getStatusTextAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'في الانتظار',
            self::STATUS_ACTIVE => 'عهدة نشطة',
            self::STATUS_CLOSED => 'مغلقة',
            default => 'غير محدد'
        };
    }
}

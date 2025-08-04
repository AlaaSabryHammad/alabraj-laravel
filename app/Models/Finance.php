<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Finance extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'category',
        'description',
        'amount',
        'transaction_date',
        'payment_method',
        'reference_number',
        'notes',
        'status'
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'amount' => 'decimal:2'
    ];

    // Type constants
    const TYPE_INCOME = 'income';
    const TYPE_EXPENSE = 'expense';

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    // Get type label in Arabic
    public function getTypeLabelAttribute()
    {
        return match($this->type) {
            self::TYPE_INCOME => 'إيراد',
            self::TYPE_EXPENSE => 'مصروف',
            default => 'غير محدد'
        };
    }

    // Get type color
    public function getTypeColorAttribute()
    {
        return match($this->type) {
            self::TYPE_INCOME => 'green',
            self::TYPE_EXPENSE => 'red',
            default => 'gray'
        };
    }

    // Get status label in Arabic
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            self::STATUS_PENDING => 'معلق',
            self::STATUS_COMPLETED => 'مكتمل',
            self::STATUS_CANCELLED => 'ملغي',
            default => 'غير محدد'
        };
    }

    // Get status color
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            self::STATUS_PENDING => 'yellow',
            self::STATUS_COMPLETED => 'green',
            self::STATUS_CANCELLED => 'red',
            default => 'gray'
        };
    }

    // Format amount with currency
    public function getFormattedAmountAttribute()
    {
        return number_format((float)$this->amount, 2) . ' ر.س';
    }

    // Check if transaction is income
    public function getIsIncomeAttribute()
    {
        return $this->type === self::TYPE_INCOME;
    }

    // Check if transaction is expense
    public function getIsExpenseAttribute()
    {
        return $this->type === self::TYPE_EXPENSE;
    }
}

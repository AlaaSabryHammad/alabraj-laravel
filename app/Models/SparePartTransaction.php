<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SparePartTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'spare_part_id',
        'location_id',
        'equipment_id',
        'user_id',
        'transaction_type',
        'quantity',
        'unit_price',
        'total_amount',
        'reference_number',
        'notes',
        'transaction_date',
        'transaction_time',
        'destination_location_id',
        'created_by',
        'additional_data',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'transaction_date' => 'date',
        'additional_data' => 'array',
    ];

    // العلاقات
    public function sparePart()
    {
        return $this->belongsTo(SparePart::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessors
    public function getTransactionTypeDisplayAttribute()
    {
        return match($this->transaction_type) {
            'استلام' => 'استلام',
            'تصدير' => 'تصدير',
            'تحويل' => 'تحويل',
            'جرد' => 'جرد',
            'إتلاف' => 'إتلاف',
            default => $this->transaction_type,
        };
    }

    public function getTransactionTypeColorAttribute()
    {
        return match($this->transaction_type) {
            'استلام' => 'green',
            'تصدير' => 'red',
            'تحويل' => 'blue',
            'جرد' => 'yellow',
            'إتلاف' => 'gray',
            default => 'gray',
        };
    }
}

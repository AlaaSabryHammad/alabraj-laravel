<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    use HasFactory;

    protected $fillable = [
        'equipment_id',
        'date',
        'type',
        'description',
        'cost',
        'technician',
        'status',
    ];

    protected $casts = [
        'date' => 'date',
        'cost' => 'decimal:2',
    ];

    /**
     * Get the equipment that owns the maintenance record.
     */
    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }
}

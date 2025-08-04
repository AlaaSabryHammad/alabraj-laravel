<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EquipmentFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'equipment_id',
        'file_name',
        'original_name',
        'file_path',
        'file_type',
        'file_size',
        'expiry_date',
        'description'
    ];

    protected $casts = [
        'expiry_date' => 'date'
    ];

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }
}

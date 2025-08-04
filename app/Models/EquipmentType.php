<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    // Relationship with Equipment
    public function equipment()
    {
        return $this->hasMany(Equipment::class, 'type_id');
    }

    // Scope for active types
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}

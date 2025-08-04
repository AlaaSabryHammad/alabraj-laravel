<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocationType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'color',
        'icon',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // العلاقة مع المواقع
    public function locations()
    {
        return $this->hasMany(Location::class);
    }

    // scope للأنواع النشطة
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}

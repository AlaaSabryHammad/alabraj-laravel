<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MaterialUnit extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'symbol',
        'type',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Unit types
    const TYPE_VOLUME = 'volume';
    const TYPE_WEIGHT = 'weight';
    const TYPE_LENGTH = 'length';
    const TYPE_AREA = 'area';
    const TYPE_COUNT = 'count';

    /**
     * Get type label in Arabic
     */
    public function getTypeLabelAttribute()
    {
        return match($this->type) {
            self::TYPE_VOLUME => 'حجم',
            self::TYPE_WEIGHT => 'وزن',
            self::TYPE_LENGTH => 'طول',
            self::TYPE_AREA => 'مساحة',
            self::TYPE_COUNT => 'عدد',
            default => 'غير محدد'
        };
    }

    /**
     * Scope for active units
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get full unit display (name + symbol)
     */
    public function getFullNameAttribute()
    {
        return $this->name . ' (' . $this->symbol . ')';
    }
}

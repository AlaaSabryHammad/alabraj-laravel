<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentMovementHistory extends Model
{
    use HasFactory;

    protected $table = 'equipment_movement_history';

    protected $fillable = [
        'equipment_id',
        'from_location_id',
        'to_location_id',
        'from_location_text',
        'to_location_text',
        'moved_by',
        'moved_at',
        'movement_reason',
        'notes',
        'movement_type'
    ];

    protected $casts = [
        'moved_at' => 'datetime',
    ];

    // Relationships
    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    public function fromLocation()
    {
        return $this->belongsTo(Location::class, 'from_location_id');
    }

    public function toLocation()
    {
        return $this->belongsTo(Location::class, 'to_location_id');
    }

    public function movedBy()
    {
        return $this->belongsTo(User::class, 'moved_by');
    }

    public function movedByUser()
    {
        return $this->belongsTo(User::class, 'moved_by');
    }

    // Helper methods
    public function getFromLocationNameAttribute()
    {
        return $this->fromLocation ? $this->fromLocation->name : $this->from_location_text;
    }

    public function getToLocationNameAttribute()
    {
        return $this->toLocation ? $this->toLocation->name : $this->to_location_text;
    }

    public function getMovementTypeTextAttribute()
    {
        $types = [
            'location_change' => 'تغيير موقع',
            'initial_assignment' => 'تعيين أولي',
            'maintenance' => 'صيانة',
            'disposal' => 'إتلاف',
            'other' => 'أخرى'
        ];

        return $types[$this->movement_type] ?? $this->movement_type;
    }
}

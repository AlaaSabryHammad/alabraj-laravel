<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentDriverHistory extends Model
{
    use HasFactory;

    protected $table = 'equipment_driver_history';

    protected $fillable = [
        'equipment_id',
        'driver_id',
        'assigned_by',
        'assigned_at',
        'released_at',
        'assignment_notes',
        'release_notes',
        'status'
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'released_at' => 'datetime',
    ];

    // Relationships
    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    public function driver()
    {
        return $this->belongsTo(Employee::class, 'driver_id');
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    // Helper methods
    public function getDurationAttribute()
    {
        if (!$this->released_at) {
            return now()->diff($this->assigned_at);
        }

        return $this->released_at->diff($this->assigned_at);
    }

    public function isActive()
    {
        return $this->status === 'active' && !$this->released_at;
    }
}

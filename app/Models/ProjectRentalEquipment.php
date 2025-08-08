<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectRentalEquipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'equipment_type',
        'equipment_name',
        'rental_company',
        'rental_start_date',
        'rental_end_date',
        'daily_rate',
        'currency',
        'notes',
        'recorded_by'
    ];

    protected $casts = [
        'rental_start_date' => 'date',
        'rental_end_date' => 'date',
        'daily_rate' => 'decimal:2',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function getEquipmentTypeNameAttribute()
    {
        $types = [
            'excavator' => 'حفار',
            'bulldozer' => 'جرافة',
            'crane' => 'رافعة',
            'truck' => 'شاحنة',
            'concrete_mixer' => 'خلاطة خرسانة',
            'generator' => 'مولد كهرباء',
            'compressor' => 'ضاغط هواء',
            'other' => 'أخرى'
        ];

        return $types[$this->equipment_type] ?? $this->equipment_type;
    }

    public function getTotalCostAttribute()
    {
        if ($this->daily_rate && $this->rental_start_date && $this->rental_end_date) {
            $days = $this->rental_start_date->diffInDays($this->rental_end_date) + 1;
            return $this->daily_rate * $days;
        }
        return null;
    }
}

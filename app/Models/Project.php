<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'status',
        'start_date',
        'end_date',
        'budget',
        'client_name',
        'location',
        'project_manager',
        'project_number',
        'government_entity',
        'consulting_office',
        'project_scope',
        'project_manager_id',
        'progress_percentage'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'budget' => 'decimal:2'
    ];

    // Status constants
    const STATUS_PLANNING = 'planning';
    const STATUS_ACTIVE = 'active';
    const STATUS_ON_HOLD = 'on_hold';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    // Get status label in Arabic
    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            self::STATUS_PLANNING => 'في التخطيط',
            self::STATUS_ACTIVE => 'نشط',
            self::STATUS_ON_HOLD => 'معلق',
            self::STATUS_COMPLETED => 'مكتمل',
            self::STATUS_CANCELLED => 'ملغي',
            default => 'غير محدد'
        };
    }

    // Get status color
    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            self::STATUS_PLANNING => 'blue',
            self::STATUS_ACTIVE => 'green',
            self::STATUS_ON_HOLD => 'yellow',
            self::STATUS_COMPLETED => 'gray',
            self::STATUS_CANCELLED => 'red',
            default => 'gray'
        };
    }

    // Calculate duration in days
    public function getDurationAttribute()
    {
        if ($this->end_date && $this->start_date) {
            return $this->start_date->diffInDays($this->end_date);
        }
        return null;
    }

    // Check if project is active
    public function getIsActiveAttribute()
    {
        return in_array($this->status, [self::STATUS_PLANNING, self::STATUS_ACTIVE]);
    }

    // Format budget with currency
    public function getFormattedBudgetAttribute()
    {
        return number_format((float)$this->budget, 0) . ' ر.س';
    }

    // Calculate days remaining
    public function getDaysRemainingAttribute()
    {
        if ($this->end_date && $this->status === self::STATUS_ACTIVE) {
            $remaining = Carbon::now()->diffInDays($this->end_date, false);
            return max(0, $remaining);
        }
        return null;
    }

    // Relationships
    public function projectManager()
    {
        return $this->belongsTo(Employee::class, 'project_manager_id');
    }

    public function projectFiles()
    {
        return $this->hasMany(ProjectFile::class);
    }

    public function projectImages()
    {
        return $this->hasMany(ProjectImage::class);
    }

    public function deliveryRequests()
    {
        return $this->hasMany(ProjectDeliveryRequest::class);
    }

    public function projectItems()
    {
        return $this->hasMany(ProjectItem::class);
    }
}

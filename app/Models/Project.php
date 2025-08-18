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
        'bank_guarantee_amount',
        'bank_guarantee_type',
        'client_name',
        'location',
        'project_manager',
        'project_number',
        'government_entity',
        'consulting_office',
        'project_scope',
        'project_manager_id',
        'progress'
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

    // Format bank guarantee amount with currency
    public function getFormattedBankGuaranteeAmountAttribute()
    {
        if ($this->bank_guarantee_amount) {
            return number_format((float)$this->bank_guarantee_amount, 0) . ' ر.س';
        }
        return 'غير محدد';
    }

    // Get bank guarantee type in Arabic
    public function getBankGuaranteeTypeNameAttribute()
    {
        switch ($this->bank_guarantee_type) {
            case 'cash':
                return 'كاش';
            case 'facilities':
                return 'تسهيلات';
            case 'performance':
                return 'أداء';
            case 'advance_payment':
                return 'سلفة';
            case 'maintenance':
                return 'صيانة';
            case 'other':
                return 'أخرى';
            default:
                return 'غير محدد';
        }
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

    public function projectExtracts()
    {
        return $this->hasMany(ProjectExtract::class);
    }

    public function locations()
    {
        return $this->hasMany(Location::class, 'project_id');
    }

    public function equipment()
    {
        return $this->hasManyThrough(Equipment::class, Location::class, 'project_id', 'location_id');
    }

    public function extensions()
    {
        return $this->hasMany(ProjectExtension::class);
    }

    public function visits()
    {
        return $this->hasMany(ProjectVisit::class);
    }

    public function rentalEquipment()
    {
        return $this->hasMany(ProjectRentalEquipment::class);
    }

    public function loans()
    {
        return $this->hasMany(ProjectLoan::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}

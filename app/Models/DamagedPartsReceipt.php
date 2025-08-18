<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DamagedPartsReceipt extends Model
{
    use HasFactory;

    protected $fillable = [
        'receipt_number',
        'receipt_date',
        'receipt_time',
        'project_id',
        'equipment_id',
        'spare_part_id',
        'quantity_received',
        'damage_condition',
        'damage_description',
        'damage_cause',
        'technician_notes',
        'received_by',
        'sent_by',
        'warehouse_id',
        'storage_location',
        'estimated_repair_cost',
        'replacement_cost',
        'processing_status',
        'photos',
        'documents',
        'evaluation_date',
        'decision_date',
        'completion_date'
    ];

    protected $casts = [
        'receipt_date' => 'date',
        'estimated_repair_cost' => 'decimal:2',
        'replacement_cost' => 'decimal:2',
        'photos' => 'array',
        'documents' => 'array',
        'evaluation_date' => 'datetime',
        'decision_date' => 'datetime',
        'completion_date' => 'datetime'
    ];

    // العلاقات
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class);
    }

    public function sparePart(): BelongsTo
    {
        return $this->belongsTo(SparePart::class);
    }

    public function receivedByEmployee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'received_by');
    }

    public function sentByEmployee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'sent_by');
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'warehouse_id');
    }

    // Helper Methods
    public function getDamageConditionTextAttribute(): string
    {
        return match ($this->damage_condition) {
            'repairable' => 'قابلة للإصلاح',
            'non_repairable' => 'غير قابلة للإصلاح',
            'replacement_needed' => 'تحتاج لاستبدال',
            'for_evaluation' => 'تحتاج لتقييم',
            default => 'غير محدد'
        };
    }

    public function getProcessingStatusTextAttribute(): string
    {
        return match ($this->processing_status) {
            'received' => 'تم الاستلام',
            'under_evaluation' => 'تحت التقييم',
            'approved_repair' => 'موافقة على الإصلاح',
            'approved_replace' => 'موافقة على الاستبدال',
            'disposed' => 'تم التخلص منها',
            'returned_fixed' => 'تم إرجاعها بعد الإصلاح',
            default => 'غير محدد'
        };
    }

    public function generateReceiptNumber(): string
    {
        $date = now()->format('Ymd');
        $count = static::whereDate('created_at', now())->count() + 1;
        return "DMG-{$date}-" . str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    // Boot method to auto-generate receipt number
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->receipt_number) {
                $model->receipt_number = $model->generateReceiptNumber();
            }
        });
    }
}

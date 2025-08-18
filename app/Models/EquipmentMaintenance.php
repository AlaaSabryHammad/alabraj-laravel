<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentMaintenance extends Model
{
    use HasFactory;

    protected $fillable = [
        'equipment_id',
        'user_id',
        'maintenance_date',
        'maintenance_type',
        'status',
        'external_cost',
        'maintenance_center',
        'invoice_number',
        'invoice_image',
        'notes',
        'description',
        'expected_completion_date',
        'actual_completion_date',
    ];

    protected $casts = [
        'maintenance_date' => 'date',
        'expected_completion_date' => 'date',
        'actual_completion_date' => 'date',
        'external_cost' => 'decimal:2',
    ];

    // العلاقات
    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessors للحصول على النصوص المترجمة
    public function getMaintenanceTypeTextAttribute()
    {
        return $this->maintenance_type === 'internal' ? 'داخلية' : 'خارجية';
    }

    public function getStatusTextAttribute()
    {
        return match ($this->status) {
            'in_progress' => 'قيد التنفيذ',
            'completed' => 'مكتملة',
            'cancelled' => 'ملغاة',
            default => 'غير محدد'
        };
    }

    // Scope للبحث حسب نوع الصيانة
    public function scopeInternal($query)
    {
        return $query->where('maintenance_type', 'internal');
    }

    public function scopeExternal($query)
    {
        return $query->where('maintenance_type', 'external');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}

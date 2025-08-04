<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Correspondence extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'reference_number',
        'external_number',
        'subject',
        'from_entity',
        'to_entity',
        'correspondence_date',
        'priority',
        'status',
        'replied_at',
        'notes',
        'file_path',
        'file_name',
        'file_size',
        'file_type',
        'project_id',
        'assigned_to',
        'created_by',
    ];

    protected $casts = [
        'correspondence_date' => 'date',
        'replied_at' => 'datetime',
        'file_size' => 'integer',
    ];

    // Relations
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignedEmployee()
    {
        return $this->belongsTo(Employee::class, 'assigned_to');
    }

    public function replies()
    {
        return $this->hasMany(CorrespondenceReply::class);
    }

    // Accessors
    public function getTypeDisplayAttribute()
    {
        return match($this->type) {
            'incoming' => 'وارد',
            'outgoing' => 'صادر',
            default => $this->type,
        };
    }

    public function getPriorityDisplayAttribute()
    {
        return match($this->priority) {
            'low' => 'منخفضة',
            'medium' => 'متوسطة',
            'high' => 'عالية',
            'urgent' => 'عاجل',
            default => $this->priority,
        };
    }

    public function getStatusDisplayAttribute()
    {
        return match($this->status) {
            'pending' => 'في الانتظار',
            'in_progress' => 'قيد المعالجة',
            'replied' => 'تم الرد',
            'closed' => 'مغلق',
            default => $this->status,
        };
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'yellow',
            'in_progress' => 'blue',
            'replied' => 'green',
            'closed' => 'gray',
            default => 'gray',
        };
    }

    public function getPriorityColorAttribute()
    {
        return match($this->priority) {
            'low' => 'green',
            'medium' => 'blue',
            'high' => 'orange',
            'urgent' => 'red',
            default => 'gray',
        };
    }

    // Scopes
    public function scopeIncoming($query)
    {
        return $query->where('type', 'incoming');
    }

    public function scopeOutgoing($query)
    {
        return $query->where('type', 'outgoing');
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    // Generate reference number
    public static function generateReferenceNumber($type)
    {
        $prefix = $type === 'incoming' ? 'IN' : 'OUT';
        $year = date('Y');
        $count = self::where('type', $type)
                    ->whereYear('created_at', $year)
                    ->count() + 1;

        return $prefix . '-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}

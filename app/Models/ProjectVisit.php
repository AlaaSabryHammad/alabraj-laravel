<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectVisit extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'visit_date',
        'visit_time',
        'visitor_id',
        'visitor_name',
        'visit_type',
        'visit_notes',
        'recorded_by'
    ];

    protected $casts = [
        'visit_date' => 'date',
        'visit_time' => 'datetime:H:i',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function visitor()
    {
        return $this->belongsTo(Employee::class, 'visitor_id');
    }

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function getVisitTypeNameAttribute()
    {
        $types = [
            'inspection' => 'جولة تفتيش',
            'meeting' => 'اجتماع',
            'supervision' => 'إشراف',
            'coordination' => 'تنسيق',
            'other' => 'أخرى'
        ];

        return $types[$this->visit_type] ?? $this->visit_type;
    }
}

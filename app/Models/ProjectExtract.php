<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectExtract extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'extract_number',
        'description',
        'extract_date',
        'status',
        'total_amount',
        'file_path',
        'items_data',
        'created_by',
    ];

    protected $casts = [
        'extract_date' => 'date',
        'total_amount' => 'decimal:2',
        'items_data' => 'array',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function extractItems()
    {
        return $this->hasMany(ProjectExtractItem::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getStatusDisplayAttribute()
    {
        return match($this->status) {
            'draft' => 'مسودة',
            'submitted' => 'مُقدم',
            'approved' => 'موافق عليه',
            'paid' => 'مدفوع',
            default => $this->status,
        };
    }
}

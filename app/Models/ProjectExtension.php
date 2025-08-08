<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectExtension extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'old_end_date',
        'new_end_date',
        'extension_reason',
        'extended_by'
    ];

    protected $casts = [
        'old_end_date' => 'date',
        'new_end_date' => 'date',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function extendedBy()
    {
        return $this->belongsTo(User::class, 'extended_by');
    }
}

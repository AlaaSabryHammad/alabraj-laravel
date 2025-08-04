<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'name',
        'file_path',
        'description',
        'file_size',
        'file_type'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}

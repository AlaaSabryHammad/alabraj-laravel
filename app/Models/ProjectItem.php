<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectItem extends Model
{
    protected $fillable = [
        'project_id',
        'name',
        'quantity',
        'unit',
        'unit_price',
        'total_price',
        'total_with_tax'
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'total_with_tax' => 'decimal:2'
    ];

    // Relationship with Project
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}

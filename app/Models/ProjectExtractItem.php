<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectExtractItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_extract_id',
        'item_description',
        'project_item_index',
        'quantity',
        'unit',
        'unit_price',
        'total_price',
        'total_value',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'total_value' => 'decimal:2',
    ];

    public function projectExtract()
    {
        return $this->belongsTo(ProjectExtract::class);
    }
}

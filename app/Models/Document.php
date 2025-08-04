<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'type',
        'file_path',
        'file_size',
        'uploaded_by',
        'description',
        'tags',
        'expiry_date'
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'tags' => 'array'
    ];
}

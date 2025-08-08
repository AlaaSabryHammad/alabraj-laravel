<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SparePartType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description', 
        'category',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    /**
     * Get all spare parts of this type
     */
    public function spareParts()
    {
        return $this->hasMany(SparePart::class, 'spare_part_type_id');
    }

    /**
     * Get category label in Arabic
     */
    public function getCategoryLabelAttribute()
    {
        $categories = [
            'engine' => 'محرك',
            'transmission' => 'ناقل الحركة', 
            'brakes' => 'المكابح',
            'electrical' => 'كهربائي',
            'hydraulic' => 'هيدروليك',
            'cooling' => 'تبريد',
            'filters' => 'فلاتر',
            'tires' => 'إطارات',
            'body' => 'هيكل',
            'other' => 'أخرى'
        ];

        return $categories[$this->category] ?? 'غير محدد';
    }
}

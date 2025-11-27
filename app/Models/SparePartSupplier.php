<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SparePartSupplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'company_name',
        'email',
        'phone',
        'phone_2',
        'address',
        'city',
        'country',
        'tax_number',
        'cr_number',
        'contact_person',
        'contact_person_phone',
        'notes',
        'status',
        'credit_limit',
        'payment_terms',
    ];

    protected $casts = [
        'credit_limit' => 'decimal:2',
    ];

    /**
     * Get all spare parts supplied by this supplier
     */
    public function spareParts()
    {
        return $this->hasMany(SparePart::class, 'spare_part_supplier_id');
    }

    /**
     * Scope to get active suppliers
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'نشط');
    }

    /**
     * Scope to search suppliers
     */
    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
                ->orWhere('company_name', 'like', "%{$term}%")
                ->orWhere('email', 'like', "%{$term}%")
                ->orWhere('phone', 'like', "%{$term}%");
        });
    }
}

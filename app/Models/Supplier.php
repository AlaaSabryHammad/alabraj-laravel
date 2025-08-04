<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
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
        'category',
        'payment_terms',
        'credit_limit',
        'notes',
        'status',
        'contact_person',
        'contact_person_phone'
    ];

    protected $casts = [
        'credit_limit' => 'decimal:2'
    ];

    /**
     * Get the external trucks for this supplier
     */
    public function externalTrucks()
    {
        return $this->hasMany(ExternalTruck::class);
    }
}

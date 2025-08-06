<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeBalance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'type',
        'amount',
        'notes',
        'transaction_date',
        'created_by'
    ];

    protected $casts = [
        'transaction_date' => 'datetime',
        'amount' => 'decimal:2'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

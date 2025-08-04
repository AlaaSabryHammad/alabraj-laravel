<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManagerAssignment extends Model
{
    protected $fillable = [
        'employee_id',
        'manager_id',
        'assigned_by',
        'assigned_at',
        'notes',
        'assignment_type'
    ];

    protected $casts = [
        'assigned_at' => 'datetime'
    ];

    // العلاقات
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function manager()
    {
        return $this->belongsTo(Employee::class, 'manager_id');
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}

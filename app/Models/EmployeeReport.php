<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'reporter_id',
        'report_content',
        'report_type'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // العلاقة مع الموظف
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    // العلاقة مع المستخدم الذي كتب التقرير
    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }
}

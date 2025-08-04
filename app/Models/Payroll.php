<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payroll extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'payroll_date',
        'notes',
        'status',
        'total_amount',
        'created_by',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'payroll_date' => 'date',
        'approved_at' => 'datetime',
        'total_amount' => 'decimal:2',
    ];

    // Relations
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function employees()
    {
        return $this->hasMany(PayrollEmployee::class);
    }

    public function eligibleEmployees()
    {
        return $this->hasMany(PayrollEmployee::class)->where('is_eligible', true);
    }

    // Methods
    public function calculateTotalAmount()
    {
        $total = $this->employees()
            ->where('is_eligible', true)
            ->sum('net_salary');

        $this->update(['total_amount' => $total]);

        return $total;
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'draft' => 'bg-gray-100 text-gray-800',
            'pending' => 'bg-yellow-100 text-yellow-800',
            'approved' => 'bg-green-100 text-green-800',
            'rejected' => 'bg-red-100 text-red-800',
        ];

        return $badges[$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    public function getStatusTextAttribute()
    {
        $statuses = [
            'draft' => 'مسودة',
            'pending' => 'في الانتظار',
            'approved' => 'معتمد',
            'rejected' => 'مرفوض',
        ];

        return $statuses[$this->status] ?? 'غير محدد';
    }

    public function isEditable()
    {
        return in_array($this->status, ['draft', 'pending']);
    }

    public function canBeApproved()
    {
        return $this->status === 'pending';
    }
}

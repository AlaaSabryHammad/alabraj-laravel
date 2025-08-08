<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectLoan extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'loan_amount',
        'loan_source',
        'lender_name',
        'loan_date',
        'due_date',
        'interest_rate',
        'interest_type',
        'loan_purpose',
        'notes',
        'status',
        'recorded_by'
    ];

    protected $casts = [
        'loan_amount' => 'decimal:2',
        'loan_date' => 'date',
        'due_date' => 'date',
        'interest_rate' => 'decimal:2',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function getLoanSourceNameAttribute()
    {
        $sources = [
            'bank' => 'بنك',
            'company' => 'شركة',
            'individual' => 'فرد',
            'government' => 'جهة حكومية',
            'other' => 'أخرى'
        ];

        return $sources[$this->loan_source] ?? $this->loan_source;
    }

    public function getLoanPurposeNameAttribute()
    {
        $purposes = [
            'equipment' => 'شراء معدات',
            'materials' => 'شراء مواد',
            'wages' => 'دفع أجور',
            'operations' => 'تكاليف تشغيلية',
            'expansion' => 'توسعة المشروع',
            'other' => 'أخرى'
        ];

        return $purposes[$this->loan_purpose] ?? $this->loan_purpose;
    }

    public function getFormattedLoanAmountAttribute()
    {
        return number_format((float)$this->loan_amount, 2) . ' ر.س';
    }

    public function getStatusNameAttribute()
    {
        $statuses = [
            'active' => 'نشط',
            'paid' => 'مسدد',
            'overdue' => 'متأخر'
        ];

        return $statuses[$this->status] ?? $this->status;
    }
}

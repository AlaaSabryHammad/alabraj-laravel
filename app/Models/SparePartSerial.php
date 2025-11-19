<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SparePartSerial extends Model
{
    use HasFactory;

    protected $fillable = [
        'spare_part_id',
        'serial_number',
        'barcode',
        'location_id',
        'status',
        'assigned_to_employee_id',
        'assigned_date',
        'returned_date',
        'exported_to_employee_id',
        'exported_date',
        'exported_at',
        'exported_by',
        'export_notes',
        'notes',
    ];

    protected $casts = [
        'assigned_date' => 'date',
        'returned_date' => 'date',
        'exported_date' => 'date',
    ];

    // العلاقات
    public function sparePart()
    {
        return $this->belongsTo(SparePart::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function assignedToEmployee()
    {
        return $this->belongsTo(Employee::class, 'assigned_to_employee_id');
    }

    public function exportedToEmployee()
    {
        return $this->belongsTo(Employee::class, 'exported_to_employee_id');
    }

    // دوال مساعدة
    public function isAvailable()
    {
        return $this->status === 'available';
    }

    public function isAssigned()
    {
        return $this->status === 'assigned';
    }

    public function isDamaged()
    {
        return $this->status === 'damaged';
    }

    // تحديث حالة الرقم التسلسلي
    public function assignTo(Employee $employee, $notes = null)
    {
        $this->update([
            'status' => 'assigned',
            'assigned_to_employee_id' => $employee->id,
            'assigned_date' => now(),
            'notes' => $notes,
        ]);
    }

    public function markAsReturned($notes = null)
    {
        $this->update([
            'status' => 'returned',
            'returned_date' => now(),
            'notes' => $notes,
        ]);
    }

    public function markAsDamaged($notes = null)
    {
        $this->update([
            'status' => 'damaged',
            'notes' => $notes,
        ]);
    }
}

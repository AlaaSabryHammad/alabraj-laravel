<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'category',
        'material_unit_id',
        'unit_price',
        'minimum_stock',
        'maximum_stock',
        'current_stock',
        'supplier_name',
        'supplier_contact',
        'brand',
        'model',
        'specifications',
        'status',
        'storage_location',
        'last_purchase_date',
        'last_purchase_price',
        'notes'
    ];

    protected $casts = [
        'specifications' => 'array',
        'unit_price' => 'decimal:2',
        'last_purchase_price' => 'decimal:2',
        'last_purchase_date' => 'date'
    ];

    /**
     * Get the status in Arabic
     */
    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            'active' => 'نشط',
            'inactive' => 'غير نشط',
            'out_of_stock' => 'نفذ المخزون',
            'discontinued' => 'متوقف',
            default => $this->status
        };
    }

    /**
     * Get the category name in Arabic
     */
    public function getCategoryNameAttribute(): string
    {
        return match($this->category) {
            'cement' => 'أسمنت',
            'steel' => 'حديد',
            'aggregate' => 'خرسانة',
            'tools' => 'أدوات',
            'electrical' => 'كهربائية',
            'plumbing' => 'سباكة',
            'other' => 'أخرى',
            default => $this->category
        };
    }

    /**
     * Check if material is low on stock
     */
    public function isLowStock(): bool
    {
        return $this->current_stock <= $this->minimum_stock && $this->current_stock > 0;
    }

    /**
     * Check if material is out of stock
     */
    public function isOutOfStock(): bool
    {
        return $this->current_stock == 0;
    }

    /**
     * Get stock status
     */
    public function getStockStatusAttribute(): string
    {
        if ($this->current_stock == 0) {
            return 'نفذ المخزون';
        } elseif ($this->isLowStock()) {
            return 'مخزون منخفض';
        } else {
            return 'متوفر';
        }
    }

    /**
     * Get the status in Arabic (method format)
     */
    public function getStatusInArabic(): string
    {
        return match($this->status) {
            'active' => 'نشط',
            'inactive' => 'غير نشط',
            'out_of_stock' => 'نفذ المخزون',
            'discontinued' => 'متوقف',
            default => $this->status
        };
    }

    /**
     * Get the category name in Arabic (method format)
     */
    public function getCategoryInArabic(): string
    {
        return match($this->category) {
            'cement' => 'أسمنت',
            'steel' => 'حديد',
            'aggregate' => 'خرسانة',
            'tools' => 'أدوات',
            'electrical' => 'كهربائية',
            'plumbing' => 'سباكة',
            'other' => 'أخرى',
            default => $this->category
        };
    }

    /**
     * Relationship with MaterialUnit
     */
    public function materialUnit()
    {
        return $this->belongsTo(MaterialUnit::class);
    }

    /**
     * Get the unit (returns unit from material_unit relationship)
     */
    public function getUnitAttribute(): ?string
    {
        return $this->materialUnit?->name;
    }

    /**
     * Get the effective unit for display (ensures we always have a unit)
     */
    public function getEffectiveUnitAttribute(): ?string
    {
        return $this->materialUnit?->name ?? 'غير محدد';
    }

    /**
     * Get unit_of_measure for backwards compatibility
     */
    public function getUnitOfMeasureAttribute(): ?string
    {
        return $this->materialUnit?->name;
    }
}

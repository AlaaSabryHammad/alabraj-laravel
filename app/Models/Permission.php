<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = [
        'name',
        'display_name',
        'category',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    /**
     * الحصول على جميع الأدوار التي تملك هذه الصلاحية
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permissions');
    }

    /**
     * الحصول على الصلاحيات مجمعة حسب الفئة
     */
    public static function getByCategory()
    {
        return self::where('is_active', true)
            ->orderBy('category')
            ->orderBy('name')
            ->get()
            ->groupBy('category');
    }

    /**
     * التحقق من وجود الصلاحية
     */
    public static function exists($permission)
    {
        return self::where('name', $permission)->where('is_active', true)->exists();
    }
}

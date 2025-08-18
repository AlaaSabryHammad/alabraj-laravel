<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Permission;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    /**
     * المستخدمون التابعون لهذا الدور
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_roles');
    }

    /**
     * الصلاحيات التابعة لهذا الدور
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions');
    }

    /**
     * التحقق من وجود صلاحية معينة
     */
    public function hasPermission($permission)
    {
        if (!$this->is_active) {
            return false;
        }

        return $this->permissions()->where('name', $permission)->exists();
    }

    /**
     * إضافة صلاحية للدور
     */
    public function givePermission($permission)
    {
        $permModel = Permission::where('name', $permission)->first();
        if ($permModel && !$this->permissions()->where('permissions.id', $permModel->id)->exists()) {
            $this->permissions()->attach($permModel->id);
        }
    }

    /**
     * إزالة صلاحية من الدور
     */
    public function revokePermission($permission)
    {
        $permModel = Permission::where('name', $permission)->first();
        if ($permModel) {
            $this->permissions()->detach($permModel->id);
        }
    }
}

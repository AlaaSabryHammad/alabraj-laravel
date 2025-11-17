<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role',
        'department',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Attributes to append to model output
     *
     * @var array<int, string>
     */
    protected $appends = [
        'employee_id',
    ];

    // Employee relationship
    public function employee()
    {
        return $this->hasOne(Employee::class);
    }

    /**
     * Get employee ID for notifications and other features
     * Loads the employee relationship if needed
     */
    public function getEmployeeIdAttribute()
    {
        if (!$this->relationLoaded('employee')) {
            $this->load('employee');
        }
        return $this->employee?->id;
    }

    // Single role relationship (for role_id column)
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * العلاقة مع الأدوار
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    /**
     * التحقق من وجود دور
     */
    public function hasRole($role)
    {
        if (is_string($role)) {
            return $this->roles()->where('name', $role)->exists();
        }

        return $this->roles()->where('id', $role)->exists();
    }

    /**
     * التحقق من وجود صلاحية
     */
    public function hasPermission($permission)
    {
        foreach ($this->roles as $role) {
            if ($role->hasPermission($permission)) {
                return true;
            }
        }
        return false;
    }

    /**
     * التحقق من أن المستخدم مدير عام
     */
    public function isGeneralManager()
    {
        return $this->hasRole('general_manager') || $this->role === 'general_manager';
    }

    /**
     * إعطاء دور للمستخدم
     */
    public function assignRole($role)
    {
        if (is_string($role)) {
            $role = Role::where('name', $role)->first();
        }

        if ($role && !$this->hasRole($role->id)) {
            $this->roles()->attach($role->id);
        }
    }

    /**
     * إزالة دور من المستخدم
     */
    public function removeRole($role)
    {
        if (is_string($role)) {
            $role = Role::where('name', $role)->first();
        }

        if ($role) {
            $this->roles()->detach($role->id);
        }
    }

    /**
     * الحصول على جميع الصلاحيات للمستخدم
     */
    public function getAllPermissions()
    {
        $permissions = [];
        foreach ($this->roles as $role) {
            $permissions = array_merge($permissions, $role->permissions ?? []);
        }
        return array_unique($permissions);
    }

    /**
     * الحصول على رابط الصورة الشخصية
     */
    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            return asset($this->avatar);
        }

        return $this->generateDefaultAvatar();
    }

    /**
     * توليد صورة شخصية افتراضية
     */
    private function generateDefaultAvatar()
    {
        $initials = $this->getInitials($this->name);
        $colors = [
            '#FF6B6B',
            '#4ECDC4',
            '#45B7D1',
            '#96CEB4',
            '#FFEAA7',
            '#DDA0DD',
            '#98D8C8',
            '#F7DC6F',
            '#BB8FCE',
            '#85C1E9'
        ];

        $colorIndex = abs(crc32($this->name)) % count($colors);
        $backgroundColor = $colors[$colorIndex];

        $svg = "<svg width='200' height='200' xmlns='http://www.w3.org/2000/svg'>";
        $svg .= "<rect width='200' height='200' fill='{$backgroundColor}'/>";
        $svg .= "<text x='50%' y='130' font-family='Arial, sans-serif' font-size='80' ";
        $svg .= "fill='white' text-anchor='middle' dominant-baseline='middle'>{$initials}</text>";
        $svg .= "</svg>";

        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }

    /**
     * Get user's current role display name
     */
    public function getCurrentRoleDisplayName()
    {
        // أولاً جرب من بيانات الموظف
        if ($this->employee && $this->employee->role) {
            $employeeRole = $this->employee->role;

            // تحويل أدوار الموظف إلى أسماء عرض
            return match ($employeeRole) {
                'general_manager' => 'مدير عام',
                'manager' => 'مدير',
                'project_manager' => 'مدير مشاريع',
                'engineer' => 'مهندس',
                'site_manager' => 'مشرف موقع',
                'supervisor' => 'مشرف',
                'worker' => 'عامل',
                'driver' => 'سائق',
                'مدير عام' => 'مدير عام',
                'مدير' => 'مدير',
                'مدير مشاريع' => 'مدير مشاريع',
                'مهندس' => 'مهندس',
                'مشرف موقع' => 'مشرف موقع',
                default => $employeeRole
            };
        }

        // ثم جرب من علاقة role_id
        if ($this->role_id) {
            $role = Role::find($this->role_id);
            if ($role) {
                return $role->display_name;
            }
        }

        // ثم جرب من علاقة many-to-many
        if ($this->roles && $this->roles->isNotEmpty()) {
            return $this->roles->first()->display_name;
        }

        // أخيراً استخدم الدور القديم
        return match ($this->role) {
            'admin' => 'مدير النظام',
            'manager' => 'مدير',
            'finance' => 'مدير المالية',
            'employee' => 'موظف',
            default => 'موظف'
        };
    }

    /**
     * استخراج الأحرف الأولى من الاسم
     */
    private function getInitials($name)
    {
        $nameParts = explode(' ', trim($name));
        $initials = '';
        $count = 0;

        foreach ($nameParts as $part) {
            if ($count >= 2) break;
            $part = trim($part);
            if (!empty($part)) {
                $initials .= mb_substr($part, 0, 1, 'UTF-8');
                $count++;
            }
        }

        return $initials ?: mb_substr($name, 0, 2, 'UTF-8');
    }
}

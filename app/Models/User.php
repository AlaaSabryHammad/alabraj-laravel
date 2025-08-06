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

    // Employee relationship
    public function employee()
    {
        return $this->hasOne(Employee::class);
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
            '#FF6B6B', '#4ECDC4', '#45B7D1', '#96CEB4', '#FFEAA7',
            '#DDA0DD', '#98D8C8', '#F7DC6F', '#BB8FCE', '#85C1E9'
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

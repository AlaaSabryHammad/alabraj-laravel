<?php

namespace App\Services;

use Illuminate\Support\Str;

class AvatarGenerator
{
    private $size;
    private $colors;
    
    public function __construct($size = 200)
    {
        $this->size = $size;
        
        // ألوان متنوعة للخلفيات
        $this->colors = [
            '#FF6B6B', '#4ECDC4', '#45B7D1', '#96CEB4', '#FFEAA7',
            '#DDA0DD', '#98D8C8', '#F7DC6F', '#BB8FCE', '#85C1E9',
            '#F8C471', '#82E0AA', '#F1948A', '#85C1E9', '#D2B4DE',
            '#AED6F1', '#A3E4D7', '#F9E79F', '#FAD7A0', '#D5A6BD'
        ];
    }
    
    /**
     * توليد صورة شخصية SVG بناءً على الاسم
     */
    public function generateSimpleAvatar($name, $email = null)
    {
        // استخراج الأحرف الأولى من الاسم
        $initials = $this->getInitials($name);
        
        // اختيار لون خلفية بناءً على الاسم (ليكون ثابت لنفس الشخص)
        $colorIndex = abs(crc32($name)) % count($this->colors);
        $backgroundColor = $this->colors[$colorIndex];
        
        // إنشاء محتوى SVG
        $svg = $this->createSVGAvatar($initials, $backgroundColor);
        
        // حفظ الصورة
        $filename = 'avatar_' . Str::slug($name) . '_' . time() . '.svg';
        $filepath = public_path('avatars/' . $filename);
        
        file_put_contents($filepath, $svg);
        
        return 'avatars/' . $filename;
    }
    
    /**
     * إنشاء صورة SVG
     */
    private function createSVGAvatar($initials, $backgroundColor)
    {
        $size = $this->size;
        $fontSize = $size * 0.4;
        $textY = $size * 0.65; // موضع النص عمودياً
        
        $svg = <<<SVG
<svg width="{$size}" height="{$size}" xmlns="http://www.w3.org/2000/svg">
    <rect width="{$size}" height="{$size}" fill="{$backgroundColor}"/>
    <text x="50%" y="{$textY}" font-family="Arial, sans-serif" font-size="{$fontSize}" 
          fill="white" text-anchor="middle" dominant-baseline="middle">{$initials}</text>
</svg>
SVG;
        
        return $svg;
    }
    
    /**
     * استخراج الأحرف الأولى من الاسم العربي
     */
    private function getInitials($name)
    {
        // تنظيف الاسم وتقسيمه
        $nameParts = explode(' ', trim($name));
        
        $initials = '';
        $count = 0;
        
        foreach ($nameParts as $part) {
            if ($count >= 2) break; // أول حرفين فقط
            
            $part = trim($part);
            if (!empty($part)) {
                // أخذ أول حرف من كل جزء
                $initials .= mb_substr($part, 0, 1, 'UTF-8');
                $count++;
            }
        }
        
        return $initials ?: mb_substr($name, 0, 2, 'UTF-8');
    }
    
    /**
     * توليد avatar بصيغة Data URL لـ SVG
     */
    public function generateDataUrlAvatar($name)
    {
        $initials = $this->getInitials($name);
        $colorIndex = abs(crc32($name)) % count($this->colors);
        $backgroundColor = $this->colors[$colorIndex];
        
        $svg = $this->createSVGAvatar($initials, $backgroundColor);
        $base64 = base64_encode($svg);
        
        return 'data:image/svg+xml;base64,' . $base64;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CorrespondenceReply extends Model
{
    use HasFactory;

    protected $fillable = [
        'correspondence_id',
        'user_id',
        'reply_content',
        'reply_type',
        'status',
        'file_path',
        'file_name',
        'file_size',
        'file_type'
    ];

    protected $casts = [
        'file_size' => 'integer',
    ];

    // Relations
    public function correspondence()
    {
        return $this->belongsTo(Correspondence::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessors
    public function getFileSizeDisplayAttribute()
    {
        if (!$this->file_size) {
            return null;
        }

        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $i < count($units) && $bytes >= 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getStatusDisplayAttribute()
    {
        return match ($this->status) {
            'draft' => 'مسودة',
            'sent' => 'تم الإرسال',
            default => 'غير محدد'
        };
    }

    public function getReplyTypeDisplayAttribute()
    {
        return match ($this->reply_type ?? 'reply') {
            'reply' => 'رد',
            'forward' => 'إعادة توجيه',
            'note' => 'ملاحظة',
            default => 'رد'
        };
    }
}

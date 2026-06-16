<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    public function markAsRead(): void
    {
        if (!$this->is_read) {
            $this->update(['is_read' => true, 'read_at' => now()]);
        }
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Get Arabic label for subject value.
     */
    public static function subjectLabel(string $subject): string
    {
        return match ($subject) {
            'course' => 'استفسار عن دورة تدريبية',
            'project' => 'طلب مشروع برمجي',
            'private' => 'تدريب خاص',
            'collab' => 'تعاون وشراكة',
            'other' => 'أخرى',
            default => $subject,
        };
    }
}

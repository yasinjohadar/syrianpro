<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsultationRequest extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'consultation_type',
        'preferred_date',
        'preferred_time',
        'topic',
        'notes',
    ];

    protected $casts = [
        'preferred_date' => 'date',
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
     * Get Arabic label for consultation type.
     */
    public static function consultationTypeLabel(string $type): string
    {
        return match ($type) {
            'quick' => 'استشارة سريعة (30 دقيقة)',
            'deep' => 'استشارة معمقة (60 دقيقة)',
            'code_review' => 'مراجعة مشروع / كود',
            'learning_path' => 'تخطيط مسار تعلم',
            'other' => 'أخرى',
            default => $type,
        };
    }

    /**
     * Get Arabic label for preferred time.
     */
    public static function preferredTimeLabel(?string $time): string
    {
        if (!$time) {
            return '—';
        }
        return match ($time) {
            'morning' => 'صباحاً (9 ص - 12 م)',
            'afternoon' => 'بعد الظهر (12 - 4 م)',
            'evening' => 'مساءً (4 - 8 م)',
            'flexible' => 'مرن حسب توفرك',
            default => $time,
        };
    }
}

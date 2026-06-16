<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class NewsletterSubscriber extends Model
{
    protected $fillable = [
        'email',
        'is_active',
        'source',
        'unsubscribe_token',
        'subscribed_at',
        'unsubscribed_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'subscribed_at' => 'datetime',
        'unsubscribed_at' => 'datetime',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public static function sourceLabel(string $source): string
    {
        return match ($source) {
            'home' => 'الصفحة الرئيسية',
            'blog' => 'المدونة',
            'blog-detail' => 'صفحة المقال',
            'footer' => 'الفوتر',
            default => $source ?: 'عام',
        };
    }

    public function unsubscribe(): void
    {
        $this->update([
            'is_active' => false,
            'unsubscribed_at' => now(),
        ]);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->unsubscribe_token)) {
                $model->unsubscribe_token = Str::random(64);
            }
            if (empty($model->subscribed_at)) {
                $model->subscribed_at = now();
            }
        });
    }
}

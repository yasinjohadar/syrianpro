<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobApplication extends Model
{
    public const STATUS_PENDING = 'pending';

    public const STATUS_REVIEWING = 'reviewing';

    public const STATUS_SHORTLISTED = 'shortlisted';

    public const STATUS_REJECTED = 'rejected';

    public const STATUS_ACCEPTED = 'accepted';

    protected $fillable = [
        'user_id',
        'job_listing_id',
        'status',
        'admin_notes',
    ];

    public static function statusLabels(): array
    {
        return [
            self::STATUS_PENDING => 'قيد المراجعة',
            self::STATUS_REVIEWING => 'قيد الدراسة',
            self::STATUS_SHORTLISTED => 'قائمة مختصرة',
            self::STATUS_REJECTED => 'مرفوض',
            self::STATUS_ACCEPTED => 'مقبول',
        ];
    }

    public function statusLabel(): string
    {
        return self::statusLabels()[$this->status] ?? $this->status;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class, 'job_listing_id');
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeForJob(Builder $query, int $jobId): Builder
    {
        return $query->where('job_listing_id', $jobId);
    }

    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }
}

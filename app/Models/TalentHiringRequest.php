<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TalentHiringRequest extends Model
{
    public const STATUS_DRAFT = 'draft';

    public const STATUS_ACTIVE = 'active';

    public const STATUS_PAUSED = 'paused';

    public const STATUS_CLOSED = 'closed';

    public const STATUS_HIRED = 'hired';

    public const TYPE_FULL_TIME = 'full_time';

    public const TYPE_PART_TIME = 'part_time';

    public const TYPE_FREELANCE = 'freelance';

    public const TYPE_CONTRACT = 'contract';

    protected $fillable = [
        'user_id',
        'talent_id',
        'company_id',
        'headline',
        'cover_message',
        'employment_type',
        'is_remote',
        'rate_min',
        'rate_max',
        'status',
        'published_at',
        'expires_at',
    ];

    protected $casts = [
        'is_remote' => 'boolean',
        'rate_min' => 'integer',
        'rate_max' => 'integer',
        'published_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public static function statusLabels(): array
    {
        return [
            self::STATUS_DRAFT => 'مسودة',
            self::STATUS_ACTIVE => 'نشط',
            self::STATUS_PAUSED => 'متوقف',
            self::STATUS_CLOSED => 'مغلق',
            self::STATUS_HIRED => 'تم التوظيف',
        ];
    }

    public static function employmentTypeLabels(): array
    {
        return [
            self::TYPE_FULL_TIME => 'دوام كامل',
            self::TYPE_PART_TIME => 'دوام جزئي',
            self::TYPE_FREELANCE => 'عمل حر',
            self::TYPE_CONTRACT => 'عقد',
        ];
    }

    public function statusLabel(): string
    {
        return self::statusLabels()[$this->status] ?? $this->status;
    }

    public function employmentTypeLabel(): string
    {
        return self::employmentTypeLabels()[$this->employment_type] ?? $this->employment_type;
    }

    public function isPublic(): bool
    {
        return $this->company_id === null;
    }

    public function isPitch(): bool
    {
        return $this->company_id !== null;
    }

    public function isVisible(): bool
    {
        if ($this->status !== self::STATUS_ACTIVE) {
            return false;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        return true;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function talent(): BelongsTo
    {
        return $this->belongsTo(Talent::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function responses(): HasMany
    {
        return $this->hasMany(TalentHiringRequestResponse::class, 'hiring_request_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ACTIVE)
            ->where(function (Builder $q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            });
    }

    public function scopePublic(Builder $query): Builder
    {
        return $query->whereNull('company_id');
    }

    public function scopePitches(Builder $query): Builder
    {
        return $query->whereNotNull('company_id');
    }

    public function scopePublicActive(Builder $query): Builder
    {
        return $query->public()->active();
    }

    public function scopePitchesForCompany(Builder $query, int $companyId): Builder
    {
        return $query->where('company_id', $companyId)->active();
    }

    public function scopeForTalent(Builder $query, int $talentId): Builder
    {
        return $query->where('talent_id', $talentId);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TalentRecommendation extends Model
{
    public const SCOPE_HOMEPAGE = 'homepage';

    public const SCOPE_TALENTS_PAGE = 'talents_page';

    public const SCOPE_SPECIALTY = 'specialty';

    public const SCOPE_JOB = 'job';

    protected $fillable = [
        'talent_id',
        'recommended_by',
        'reason',
        'scope',
        'scope_id',
        'priority',
        'starts_at',
        'expires_at',
        'is_active',
    ];

    protected $casts = [
        'priority' => 'integer',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public static function scopeLabels(): array
    {
        return [
            self::SCOPE_HOMEPAGE => 'الصفحة الرئيسية',
            self::SCOPE_TALENTS_PAGE => 'صفحة المواهب',
            self::SCOPE_SPECIALTY => 'تخصص تقني',
            self::SCOPE_JOB => 'وظيفة محددة',
        ];
    }

    public function scopeLabel(): string
    {
        return self::scopeLabels()[$this->scope] ?? $this->scope;
    }

    public function talent(): BelongsTo
    {
        return $this->belongsTo(Talent::class);
    }

    public function recommender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recommended_by');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)
            ->where(function (Builder $q) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function (Builder $q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            });
    }

    public function scopeForScope(Builder $query, string $scope, ?int $scopeId = null): Builder
    {
        $query->where('scope', $scope);

        if ($scopeId !== null) {
            $query->where('scope_id', $scopeId);
        }

        return $query;
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderByDesc('priority')->orderByDesc('created_at');
    }
}

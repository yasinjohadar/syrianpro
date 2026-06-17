<?php

namespace App\Models;

use App\Models\Concerns\HasContactChannels;
use App\Models\Concerns\ResolvesSlugOrIdRouteBinding;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Talent extends Model
{
    use HasContactChannels;
    use ResolvesSlugOrIdRouteBinding;
    use SoftDeletes;

    protected $table = 'talents';

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'title',
        'city',
        'avatar',
        'avatar_image',
        'bio',
        'skills',
        'is_remote',
        'is_open_to_work',
        'availability',
        'rate_min',
        'rate_max',
        'rate_currency',
        'experience',
        'projects',
        'links',
        'contact_emails',
        'contact_websites',
        'social_links',
        'tech_specialty_id',
        'is_verified',
        'is_featured',
        'is_active',
        'order',
    ];

    protected $casts = [
        'skills' => 'array',
        'is_remote' => 'boolean',
        'is_open_to_work' => 'boolean',
        'rate_min' => 'integer',
        'rate_max' => 'integer',
        'experience' => 'array',
        'projects' => 'array',
        'links' => 'array',
        'contact_emails' => 'array',
        'contact_websites' => 'array',
        'social_links' => 'array',
        'is_verified' => 'boolean',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function (Talent $talent) {
            if (empty($talent->slug)) {
                $talent->slug = static::generateUniqueSlug($talent->name);
            }
        });
    }

    public static function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $slug = Str::slug($name);
        $original = $slug;
        $counter = 1;

        while (static::query()
            ->when($ignoreId, fn (Builder $q) => $q->where('id', '!=', $ignoreId))
            ->where('slug', $slug)
            ->exists()) {
            $slug = $original.'-'.$counter++;
        }

        return $slug ?: Str::random(8);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function techSpecialty(): BelongsTo
    {
        return $this->belongsTo(TechSpecialty::class);
    }

    public function hiringRequests(): HasMany
    {
        return $this->hasMany(TalentHiringRequest::class);
    }

    public function hires(): HasMany
    {
        return $this->hasMany(Hire::class);
    }

    public function activePublicHiringRequest(): HasOne
    {
        return $this->hasOne(TalentHiringRequest::class)
            ->publicActive()
            ->latest('published_at');
    }

    public function avatarUrl(): ?string
    {
        if (! $this->avatar_image) {
            return null;
        }

        return Storage::disk('public')->url($this->avatar_image);
    }

    public function getRateDisplayAttribute(): string
    {
        if ($this->rate_min && $this->rate_max) {
            return '$'.$this->rate_min.' – $'.$this->rate_max.' /ساعة';
        }

        if ($this->rate_min) {
            return '$'.$this->rate_min.'+ /ساعة';
        }

        return '—';
    }

    public function getRateUsdAttribute(): string
    {
        if ($this->rate_min && $this->rate_max) {
            return $this->rate_min.'–'.$this->rate_max.'/ساعة';
        }

        return '';
    }

    public function getAvatarInitialAttribute(): string
    {
        if ($this->avatar) {
            return $this->avatar;
        }

        return mb_substr($this->name, 0, 1);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeForHome(Builder $query): Builder
    {
        return $query->active()
            ->featured()
            ->orderBy('order')
            ->orderBy('name');
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('order')->orderBy('name');
    }

    public function resolvedContactEmails(): array
    {
        return $this->contact_emails ?? [];
    }

    public function resolvedContactWebsites(): array
    {
        $websites = $this->contact_websites ?? [];

        if ($websites !== []) {
            return $websites;
        }

        $portfolio = $this->links['portfolio'] ?? null;

        if ($portfolio) {
            return [['label' => 'Portfolio', 'url' => $portfolio]];
        }

        return [];
    }

    public function resolvedSocialLinks(): array
    {
        $social = $this->social_links ?? [];

        if ($social !== []) {
            return $social;
        }

        $links = $this->links ?? [];
        $result = [];

        foreach (['github', 'linkedin'] as $platform) {
            if (! empty($links[$platform])) {
                $result[] = [
                    'platform' => $platform,
                    'url' => $links[$platform],
                ];
            }
        }

        return $result;
    }

    public function toFrontendArray(): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->name,
            'title' => $this->title,
            'city' => $this->city,
            'avatar' => $this->avatar_initial,
            'avatarImage' => $this->avatarUrl(),
            'bio' => $this->bio ?? '',
            'skills' => $this->skills ?? [],
            'remote' => $this->is_remote,
            'openToWork' => $this->is_open_to_work,
            'hiringHeadline' => $this->relationLoaded('activePublicHiringRequest')
                ? $this->activePublicHiringRequest?->headline
                : null,
            'specialtyName' => $this->relationLoaded('techSpecialty')
                ? $this->techSpecialty?->name
                : null,
            'availability' => $this->availability ?? '',
            'rateUSD' => $this->rate_usd,
            'rateMin' => $this->rate_min,
            'rateMax' => $this->rate_max,
            'experience' => $this->experience ?? [],
            'projects' => $this->projects ?? [],
            'links' => $this->links ?? [],
            'contactEmails' => $this->resolvedContactEmails(),
            'contactWebsites' => $this->resolvedContactWebsites(),
            'socialLinks' => $this->resolvedSocialLinks(),
            'verified' => $this->is_verified,
            'featured' => $this->is_featured,
        ];
    }
}

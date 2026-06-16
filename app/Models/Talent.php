<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Talent extends Model
{
    use SoftDeletes;

    protected $table = 'talents';

    protected $fillable = [
        'name',
        'slug',
        'title',
        'city',
        'avatar',
        'avatar_image',
        'bio',
        'skills',
        'is_remote',
        'availability',
        'rate_min',
        'rate_max',
        'rate_currency',
        'experience',
        'projects',
        'links',
        'tech_specialty_id',
        'is_verified',
        'is_featured',
        'is_active',
        'order',
    ];

    protected $casts = [
        'skills' => 'array',
        'is_remote' => 'boolean',
        'rate_min' => 'integer',
        'rate_max' => 'integer',
        'experience' => 'array',
        'projects' => 'array',
        'links' => 'array',
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

    public function techSpecialty(): BelongsTo
    {
        return $this->belongsTo(TechSpecialty::class);
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

    public function toFrontendArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'title' => $this->title,
            'city' => $this->city,
            'avatar' => $this->avatar_initial,
            'bio' => $this->bio ?? '',
            'skills' => $this->skills ?? [],
            'remote' => $this->is_remote,
            'availability' => $this->availability ?? '',
            'rateUSD' => $this->rate_usd,
            'experience' => $this->experience ?? [],
            'projects' => $this->projects ?? [],
            'links' => $this->links ?? [],
            'verified' => $this->is_verified,
            'featured' => $this->is_featured,
        ];
    }
}

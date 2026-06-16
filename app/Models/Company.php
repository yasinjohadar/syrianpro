<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Company extends Model
{
    use SoftDeletes;

    protected $table = 'companies';

    protected $fillable = [
        'name',
        'slug',
        'sector',
        'category',
        'logo',
        'logo_image',
        'jobs_count',
        'rating',
        'is_verified',
        'is_remote_friendly',
        'is_syria_friendly',
        'location',
        'founded',
        'team_size',
        'website',
        'timezone',
        'payment_methods',
        'about',
        'mission',
        'values',
        'perks',
        'culture',
        'tech_stack',
        'is_featured',
        'is_active',
        'order',
    ];

    protected $casts = [
        'jobs_count' => 'integer',
        'rating' => 'decimal:1',
        'is_verified' => 'boolean',
        'is_remote_friendly' => 'boolean',
        'is_syria_friendly' => 'boolean',
        'payment_methods' => 'array',
        'values' => 'array',
        'perks' => 'array',
        'culture' => 'array',
        'tech_stack' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function (Company $company) {
            if (empty($company->slug)) {
                $company->slug = static::generateUniqueSlug($company->name);
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

    public function jobs(): HasMany
    {
        return $this->hasMany(Job::class, 'company_name', 'name');
    }

    public function logoUrl(): ?string
    {
        if (! $this->logo_image) {
            return null;
        }

        return Storage::disk('public')->url($this->logo_image);
    }

    public function getRatingDisplayAttribute(): string
    {
        return number_format((float) $this->rating, 1);
    }

    public function getJobsCountLabelAttribute(): string
    {
        $count = $this->jobs_count;

        if ($count === 1) {
            return '1 وظيفة';
        }

        if ($count === 2) {
            return '2 وظيفتان';
        }

        return $count.' وظيفة';
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
            ->where('is_remote_friendly', true)
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
            'sector' => $this->sector,
            'category' => $this->category,
            'logo' => $this->logo ?? '🏢',
            'jobs' => $this->jobs_count,
            'verified' => $this->is_verified,
            'rating' => $this->rating_display,
            'remoteFriendly' => $this->is_remote_friendly,
            'syriaFriendly' => $this->is_syria_friendly,
            'location' => $this->location,
            'founded' => $this->founded,
            'teamSize' => $this->team_size,
            'website' => $this->website,
            'timezone' => $this->timezone,
            'paymentMethods' => $this->payment_methods ?? [],
            'about' => $this->about ?? '',
            'mission' => $this->mission ?? '',
            'values' => $this->values ?? [],
            'perks' => $this->perks ?? [],
            'culture' => $this->culture ?? [],
            'techStack' => $this->tech_stack ?? [],
        ];
    }
}

<?php

namespace App\Models;

use App\Models\Concerns\ResolvesSlugOrIdRouteBinding;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Job extends Model
{
    use ResolvesSlugOrIdRouteBinding;
    use SoftDeletes;

    protected $table = 'job_listings';

    protected $fillable = [
        'title',
        'slug',
        'company_id',
        'company_name',
        'logo',
        'logo_image',
        'location',
        'employment_type',
        'salary_min',
        'salary_max',
        'currency',
        'remote_type',
        'timezone',
        'is_syria_friendly',
        'payment_methods',
        'skills',
        'tags',
        'tag_labels',
        'description',
        'responsibilities',
        'requirements',
        'benefits',
        'tech_specialty_id',
        'is_featured',
        'is_new',
        'is_active',
        'order',
        'published_at',
    ];

    protected $casts = [
        'salary_min' => 'integer',
        'salary_max' => 'integer',
        'is_syria_friendly' => 'boolean',
        'payment_methods' => 'array',
        'skills' => 'array',
        'tags' => 'array',
        'tag_labels' => 'array',
        'responsibilities' => 'array',
        'requirements' => 'array',
        'benefits' => 'array',
        'is_featured' => 'boolean',
        'is_new' => 'boolean',
        'is_active' => 'boolean',
        'order' => 'integer',
        'published_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Job $job) {
            if (empty($job->slug)) {
                $job->slug = static::generateUniqueSlug($job->title);
            }
        });
    }

    public static function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $slug = Str::slug($title);
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

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function techSpecialty(): BelongsTo
    {
        return $this->belongsTo(TechSpecialty::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(JobApplication::class, 'job_listing_id');
    }

    public function applicationsCount(): int
    {
        return $this->applications()->count();
    }

    public function logoUrl(): ?string
    {
        if (! $this->logo_image) {
            return null;
        }

        return Storage::disk('public')->url($this->logo_image);
    }

    public function getSalaryDisplayAttribute(): string
    {
        if ($this->salary_min && $this->salary_max) {
            return number_format($this->salary_min).' – '.number_format($this->salary_max);
        }

        if ($this->salary_min) {
            return number_format($this->salary_min).'+';
        }

        return '—';
    }

    public function getRelativeDateAttribute(): string
    {
        $date = $this->published_at ?? $this->created_at;

        if (! $date) {
            return '';
        }

        $days = (int) $date->diffInDays(now());

        if ($days === 0) {
            return 'اليوم';
        }

        if ($days === 1) {
            return 'منذ يوم';
        }

        if ($days === 2) {
            return 'منذ يومين';
        }

        if ($days <= 10) {
            return "منذ {$days} أيام";
        }

        $weeks = (int) ceil($days / 7);

        if ($days <= 30) {
            return $weeks === 1 ? 'منذ أسبوع' : "منذ {$weeks} أسابيع";
        }

        return $date->translatedFormat('j M Y');
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
            ->where('remote_type', 'full-remote')
            ->orderBy('order')
            ->orderByDesc('published_at');
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('order')->orderByDesc('published_at');
    }

    public function toFrontendArray(): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->title,
            'company' => $this->company_name,
            'logo' => $this->logo ?? '💼',
            'location' => $this->location,
            'type' => $this->employment_type,
            'salary' => $this->salary_display,
            'salaryUSD' => ($this->salary_min ?? 0).'-'.($this->salary_max ?? 0),
            'currency' => $this->currency,
            'remoteType' => $this->remote_type,
            'timezone' => $this->timezone ?? '—',
            'syriaFriendly' => $this->is_syria_friendly,
            'paymentMethods' => $this->payment_methods ?? [],
            'skills' => $this->skills ?? [],
            'tags' => $this->tags ?? [],
            'tagLabels' => $this->tag_labels ?? [],
            'date' => $this->relative_date,
            'isNew' => $this->is_new,
            'description' => $this->description ?? '',
            'responsibilities' => $this->responsibilities ?? [],
            'requirements' => $this->requirements ?? [],
            'benefits' => $this->benefits ?? [],
        ];
    }
}

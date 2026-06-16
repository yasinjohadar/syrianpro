<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TechSpecialty extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'icon',
        'image',
        'jobs_count',
        'order',
        'is_active',
        'show_on_home',
    ];

    protected $casts = [
        'jobs_count' => 'integer',
        'order' => 'integer',
        'is_active' => 'boolean',
        'show_on_home' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (TechSpecialty $specialty) {
            if (empty($specialty->slug)) {
                $specialty->slug = static::generateUniqueSlug($specialty->name);
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

    public function getJobsCountLabelAttribute(): string
    {
        $count = $this->jobs_count;

        if ($count === 1) {
            return '1 وظيفة';
        }

        if ($count === 2) {
            return '2 وظيفتان';
        }

        return $count.'+ وظيفة';
    }

    public function iconUrl(): ?string
    {
        if (! $this->image) {
            return null;
        }

        return Storage::disk('public')->url($this->image);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeForHome(Builder $query): Builder
    {
        return $query->active()
            ->where('show_on_home', true)
            ->orderBy('order')
            ->orderBy('name');
    }
}

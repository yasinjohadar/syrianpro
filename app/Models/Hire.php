<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Hire extends Model
{
    public const SOURCE_APPLICATION = 'application';

    public const SOURCE_PUBLIC_REQUEST = 'public_request';

    public const SOURCE_PITCH = 'pitch';

    protected $fillable = [
        'talent_id',
        'company_id',
        'job_listing_id',
        'source',
        'source_id',
        'hired_at',
        'notes',
    ];

    protected $casts = [
        'hired_at' => 'datetime',
    ];

    public static function sourceLabels(): array
    {
        return [
            self::SOURCE_APPLICATION => 'تقديم على وظيفة',
            self::SOURCE_PUBLIC_REQUEST => 'طلب توظيف عام',
            self::SOURCE_PITCH => 'عرض موجّه',
        ];
    }

    public function sourceLabel(): string
    {
        return self::sourceLabels()[$this->source] ?? $this->source;
    }

    public function talent(): BelongsTo
    {
        return $this->belongsTo(Talent::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class, 'job_listing_id');
    }
}

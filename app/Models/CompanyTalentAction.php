<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyTalentAction extends Model
{
    public const TYPE_INVITE = 'invite';

    public const TYPE_SHORTLIST = 'shortlist';

    public const TYPE_NOTE = 'note';

    public const STATUS_PENDING = 'pending';

    public const STATUS_VIEWED = 'viewed';

    public const STATUS_APPLIED = 'applied';

    public const STATUS_DECLINED = 'declined';

    public const STATUS_ACTIVE = 'active';

    public const STATUS_REMOVED = 'removed';

    protected $fillable = [
        'company_id',
        'talent_id',
        'job_listing_id',
        'user_id',
        'type',
        'message',
        'fit_rating',
        'status',
        'viewed_at',
        'responded_at',
    ];

    protected $casts = [
        'fit_rating' => 'integer',
        'viewed_at' => 'datetime',
        'responded_at' => 'datetime',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function talent(): BelongsTo
    {
        return $this->belongsTo(Talent::class);
    }

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class, 'job_listing_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

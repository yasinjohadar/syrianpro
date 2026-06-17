<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TalentHiringRequestResponse extends Model
{
    public const STATUS_INTERESTED = 'interested';

    public const STATUS_CONTACTED = 'contacted';

    public const STATUS_DECLINED = 'declined';

    protected $fillable = [
        'hiring_request_id',
        'company_id',
        'user_id',
        'status',
        'message',
    ];

    public static function statusLabels(): array
    {
        return [
            self::STATUS_INTERESTED => 'مهتم',
            self::STATUS_CONTACTED => 'تم التواصل',
            self::STATUS_DECLINED => 'غير مناسب',
        ];
    }

    public function statusLabel(): string
    {
        return self::statusLabels()[$this->status] ?? $this->status;
    }

    public function hiringRequest(): BelongsTo
    {
        return $this->belongsTo(TalentHiringRequest::class, 'hiring_request_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

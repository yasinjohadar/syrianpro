<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    protected $table = 'system_settings';

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
    ];

    /**
     * Scope by key.
     */
    public function scopeByKey($query, string $key)
    {
        return $query->where('key', $key);
    }

    /**
     * Scope by group.
     */
    public function scopeOfGroup($query, string $group)
    {
        return $query->where('group', $group);
    }

    /**
     * Set a setting value (update or create).
     */
    public static function set(string $key, $value, string $type = 'string', string $group = 'general'): self
    {
        return static::updateOrCreate(
            [
                'key' => $key,
                'group' => $group,
            ],
            [
                'value' => is_bool($value) ? ($value ? 'true' : 'false') : (string) $value,
                'type' => $type,
            ]
        );
    }
}

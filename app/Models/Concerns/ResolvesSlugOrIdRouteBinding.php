<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Model;

trait ResolvesSlugOrIdRouteBinding
{
    public function resolveRouteBinding($value, $field = null): ?Model
    {
        $field ??= $this->getRouteKeyName();

        return static::query()
            ->where(function ($query) use ($value, $field) {
                $query->where($field, $value);

                if (is_numeric($value)) {
                    $query->orWhere($this->getKeyName(), (int) $value);
                }
            })
            ->first();
    }
}

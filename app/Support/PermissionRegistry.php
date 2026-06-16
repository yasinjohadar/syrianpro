<?php

namespace App\Support;

use Spatie\Permission\Models\Permission;

class PermissionRegistry
{
    public static function groups(): array
    {
        return config('permissions.groups', []);
    }

    /**
     * @return list<string>
     */
    public static function allNames(): array
    {
        $names = [];

        foreach (self::groups() as $group) {
            foreach (array_keys($group['permissions'] ?? []) as $name) {
                $names[] = $name;
            }
        }

        return array_values(array_unique($names));
    }

    public static function label(string $name): string
    {
        foreach (self::groups() as $group) {
            if (isset($group['permissions'][$name])) {
                return $group['permissions'][$name];
            }
        }

        return $name;
    }

    /**
     * مجموعات للعرض في نموذج الأدوار، مع إضافة صلاحيات موجودة في DB وغير معرّفة في الإعدادات.
     */
    public static function groupsForForm(): array
    {
        $groups = self::groups();
        $defined = collect(self::allNames());
        $orphans = Permission::query()
            ->pluck('name')
            ->diff($defined)
            ->sort()
            ->values();

        if ($orphans->isNotEmpty()) {
            $groups['uncategorized'] = [
                'label' => 'صلاحيات غير مصنفة',
                'permissions' => $orphans->mapWithKeys(
                    fn (string $name) => [$name => $name]
                )->all(),
            ];
        }

        return $groups;
    }

    public static function syncToDatabase(): void
    {
        foreach (self::allNames() as $name) {
            Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        }
    }

    public static function namesFromRequest(?array $input): array
    {
        if (empty($input)) {
            return [];
        }

        return array_values(array_filter(array_keys($input)));
    }
}

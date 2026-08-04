<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Support\Collection;

class PortalCapabilities
{
    /**
     * @return array<string, array<string, mixed>>
     */
    public static function all(): array
    {
        return config('capabilities.modules', []);
    }

    public static function enabled(string $key): bool
    {
        return (bool) data_get(static::all(), "{$key}.enabled", false);
    }

    public static function public(string $key): bool
    {
        return static::enabled($key) && (bool) data_get(static::all(), "{$key}.public", false);
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    public static function publicModules(): Collection
    {
        return collect(static::all())
            ->map(fn (array $module, string $key): array => ['key' => $key] + $module)
            ->filter(fn (array $module): bool => (bool) ($module['enabled'] ?? false) && (bool) ($module['public'] ?? false))
            ->values();
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    public static function futureModules(): Collection
    {
        return collect(static::all())
            ->map(fn (array $module, string $key): array => ['key' => $key] + $module)
            ->reject(fn (array $module): bool => (bool) ($module['enabled'] ?? false))
            ->values();
    }
}

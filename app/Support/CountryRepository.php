<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Support\Collection;

class CountryRepository
{
    /**
     * @return array<int, string>
     */
    public static function countries(): array
    {
        return static::records()
            ->pluck('name')
            ->filter()
            ->unique()
            ->sort()
            ->values()
            ->all();
    }

    /**
     * @return array<int, string>
     */
    public static function nationalities(): array
    {
        return static::records()
            ->pluck('nationality')
            ->filter()
            ->unique()
            ->sort()
            ->values()
            ->all();
    }

    public static function timezoneForCountry(?string $country): string
    {
        $record = static::records()
            ->first(fn (array $record): bool => strcasecmp((string) $record['name'], (string) $country) === 0);

        return (string) ($record['timezone'] ?? config('countries.default_timezone', config('app.timezone')));
    }

    /**
     * @return Collection<int, array{name: string, nationality: string, timezone: string}>
     */
    private static function records(): Collection
    {
        return collect(config('countries.records', []))
            ->filter(fn (mixed $record): bool => is_array($record) && filled($record['name'] ?? null));
    }
}

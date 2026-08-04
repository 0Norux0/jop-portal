<?php

declare(strict_types=1);

namespace App\Support;

use App\Domain\Shared\Models\Setting;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Schema;
use Throwable;

class PortalData
{
    public const SETTING_KEY = 'portal_data';

    /**
     * @var array<string, mixed> | null
     */
    private static ?array $loaded = null;

    /**
     * @return array<string, mixed>
     */
    public static function load(): array
    {
        if (static::$loaded !== null) {
            return static::$loaded;
        }

        $defaults = config('portal', []);

        try {
            if (! Schema::hasTable('settings')) {
                return static::$loaded = $defaults;
            }

            $stored = Setting::query()
                ->where('key', static::SETTING_KEY)
                ->value('value');
        } catch (Throwable) {
            return static::$loaded = $defaults;
        }

        if (! is_string($stored) || trim($stored) === '') {
            return static::$loaded = $defaults;
        }

        $decoded = json_decode($stored, true);

        if (! is_array($decoded)) {
            return static::$loaded = $defaults;
        }

        return static::$loaded = static::sanitize(array_replace($defaults, $decoded)) + $defaults;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function save(array $data): void
    {
        $data = static::sanitize($data);

        Setting::query()->updateOrCreate(
            ['key' => static::SETTING_KEY],
            [
                'group' => 'content',
                'type' => 'json',
                'value' => json_encode($data, JSON_PRETTY_PRINT),
                'is_sensitive' => false,
            ],
        );

        static::$loaded = $data;
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public static function sanitize(array $data): array
    {
        return [
            'stats' => static::sanitizeStats(Arr::get($data, 'stats', [])),
            'countries' => static::sanitizeStringList(Arr::get($data, 'countries', [])),
            'currencies' => static::sanitizeStringList(Arr::get($data, 'currencies', [])),
            'languages' => static::sanitizeStringList(Arr::get($data, 'languages', [])),
            'salary_types' => static::sanitizeStringList(Arr::get($data, 'salary_types', [])),
            'categories' => static::sanitizeStringList(Arr::get($data, 'categories', [])),
            'candidate_types' => static::sanitizeStringList(Arr::get($data, 'candidate_types', [])),
            'employer_types' => static::sanitizeStringList(Arr::get($data, 'employer_types', [])),
            'badges' => static::sanitizeStringList(Arr::get($data, 'badges', [])),
            'jobs' => static::sanitizeJobs(Arr::get($data, 'jobs', [])),
            'candidates' => static::sanitizeCandidates(Arr::get($data, 'candidates', [])),
            'packages' => static::sanitizePackages(Arr::get($data, 'packages', [])),
            'roadmap' => static::sanitizeRoadmap(Arr::get($data, 'roadmap', [])),
            'ecosystem_modules' => static::sanitizeStringList(Arr::get($data, 'ecosystem_modules', [])),
            'policies' => static::sanitizePolicies(Arr::get($data, 'policies', [])),
            'seo_pages' => static::sanitizeSeoPages(Arr::get($data, 'seo_pages', [])),
            'blog_categories' => static::sanitizeStringList(Arr::get($data, 'blog_categories', [])),
            'blog_topics' => static::sanitizeStringList(Arr::get($data, 'blog_topics', [])),
        ];
    }

    /**
     * @param  array<string, mixed>  $portal
     * @return array<string, mixed>
     */
    public static function toFormState(array $portal): array
    {
        $portal['policies'] = collect($portal['policies'] ?? [])
            ->map(fn (string $title, string $slug): array => ['slug' => $slug, 'title' => $title])
            ->values()
            ->all();

        $portal['seo_pages'] = collect($portal['seo_pages'] ?? [])
            ->map(fn (array $page, string $slug): array => [
                'slug' => $slug,
                'title' => (string) ($page['title'] ?? ''),
                'focus' => (string) ($page['focus'] ?? ''),
            ])
            ->values()
            ->all();

        return $portal;
    }

    /**
     * @param  array<string, mixed>  $state
     * @return array<string, mixed>
     */
    public static function fromFormState(array $state): array
    {
        $state['policies'] = collect($state['policies'] ?? [])
            ->filter(fn (array $policy): bool => filled($policy['slug'] ?? '') && filled($policy['title'] ?? ''))
            ->mapWithKeys(fn (array $policy): array => [static::slug($policy['slug']) => static::text($policy['title'])])
            ->all();

        $state['seo_pages'] = collect($state['seo_pages'] ?? [])
            ->filter(fn (array $page): bool => filled($page['slug'] ?? '') && filled($page['title'] ?? ''))
            ->mapWithKeys(fn (array $page): array => [
                static::slug($page['slug']) => [
                    'title' => static::text($page['title'] ?? ''),
                    'focus' => static::text($page['focus'] ?? ''),
                ],
            ])
            ->all();

        return $state;
    }

    /**
     * @param  mixed  $items
     * @return array<int, string>
     */
    private static function sanitizeStringList(mixed $items): array
    {
        if (! is_array($items)) {
            return [];
        }

        return collect($items)
            ->map(fn (mixed $item): string => is_array($item) ? static::text($item['value'] ?? '') : static::text($item))
            ->filter()
            ->values()
            ->all();
    }

    /**
     * @param  mixed  $items
     * @return array<int, array<string, string>>
     */
    private static function sanitizeStats(mixed $items): array
    {
        if (! is_array($items)) {
            return [];
        }

        return collect($items)
            ->map(fn (array $item): array => [
                'label' => static::text($item['label'] ?? ''),
                'value' => static::text($item['value'] ?? ''),
            ])
            ->filter(fn (array $item): bool => $item['label'] !== '' || $item['value'] !== '')
            ->values()
            ->all();
    }

    /**
     * @param  mixed  $items
     * @return array<int, array<string, mixed>>
     */
    private static function sanitizeJobs(mixed $items): array
    {
        if (! is_array($items)) {
            return [];
        }

        return collect($items)
            ->map(fn (array $item): array => [
                'slug' => static::slug($item['slug'] ?? $item['title'] ?? ''),
                'title' => static::text($item['title'] ?? ''),
                'company' => static::text($item['company'] ?? ''),
                'city' => static::text($item['city'] ?? ''),
                'country' => static::text($item['country'] ?? ''),
                'mode' => static::text($item['mode'] ?? ''),
                'salary' => static::text($item['salary'] ?? ''),
                'type' => static::text($item['type'] ?? ''),
                'category' => static::text($item['category'] ?? ''),
                'badges' => static::sanitizeStringList($item['badges'] ?? []),
                'urgent' => (bool) ($item['urgent'] ?? false),
                'deadline' => static::text($item['deadline'] ?? ''),
                'vacancies' => max(0, (int) ($item['vacancies'] ?? 0)),
                'description' => static::text($item['description'] ?? ''),
                'responsibilities' => static::sanitizeStringList($item['responsibilities'] ?? []),
                'skills' => static::sanitizeStringList($item['skills'] ?? []),
                'requirements' => static::sanitizeStringList($item['requirements'] ?? []),
                'benefits' => static::sanitizeStringList($item['benefits'] ?? []),
            ])
            ->filter(fn (array $item): bool => $item['slug'] !== '' && $item['title'] !== '')
            ->values()
            ->all();
    }

    /**
     * @param  mixed  $items
     * @return array<int, array<string, mixed>>
     */
    private static function sanitizeCandidates(mixed $items): array
    {
        if (! is_array($items)) {
            return [];
        }

        return collect($items)
            ->map(fn (array $item): array => [
                'name' => static::text($item['name'] ?? ''),
                'headline' => static::text($item['headline'] ?? ''),
                'country' => static::text($item['country'] ?? ''),
                'badges' => static::sanitizeStringList($item['badges'] ?? []),
                'skills' => static::sanitizeStringList($item['skills'] ?? []),
            ])
            ->filter(fn (array $item): bool => $item['name'] !== '')
            ->values()
            ->all();
    }

    /**
     * @param  mixed  $items
     * @return array<int, array<string, mixed>>
     */
    private static function sanitizePackages(mixed $items): array
    {
        if (! is_array($items)) {
            return [];
        }

        return collect($items)
            ->map(fn (array $item): array => [
                'name' => static::text($item['name'] ?? ''),
                'price' => static::text($item['price'] ?? ''),
                'features' => static::sanitizeStringList($item['features'] ?? []),
            ])
            ->filter(fn (array $item): bool => $item['name'] !== '')
            ->values()
            ->all();
    }

    /**
     * @param  mixed  $items
     * @return array<int, array<string, mixed>>
     */
    private static function sanitizeRoadmap(mixed $items): array
    {
        if (! is_array($items)) {
            return [];
        }

        return collect($items)
            ->map(fn (array $item): array => [
                'phase' => static::text($item['phase'] ?? ''),
                'title' => static::text($item['title'] ?? ''),
                'items' => static::sanitizeStringList($item['items'] ?? []),
            ])
            ->filter(fn (array $item): bool => $item['phase'] !== '' || $item['title'] !== '')
            ->values()
            ->all();
    }

    /**
     * @param  mixed  $items
     * @return array<string, string>
     */
    private static function sanitizePolicies(mixed $items): array
    {
        if (! is_array($items)) {
            return [];
        }

        return collect($items)
            ->filter(fn (mixed $title, mixed $slug): bool => is_string($slug) && filled($slug) && filled($title))
            ->mapWithKeys(fn (mixed $title, string $slug): array => [static::slug($slug) => static::text($title)])
            ->all();
    }

    /**
     * @param  mixed  $items
     * @return array<string, array<string, string>>
     */
    private static function sanitizeSeoPages(mixed $items): array
    {
        if (! is_array($items)) {
            return [];
        }

        return collect($items)
            ->filter(fn (mixed $page, mixed $slug): bool => is_string($slug) && is_array($page) && filled($slug))
            ->mapWithKeys(fn (array $page, string $slug): array => [
                static::slug($slug) => [
                    'title' => static::text($page['title'] ?? ''),
                    'focus' => static::text($page['focus'] ?? ''),
                ],
            ])
            ->all();
    }

    private static function text(mixed $value): string
    {
        return trim(strip_tags((string) $value));
    }

    private static function slug(mixed $value): string
    {
        return str((string) $value)->trim()->lower()->slug('-')->toString();
    }
}

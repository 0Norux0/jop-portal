<?php

declare(strict_types=1);

namespace App\Support;

use App\Domain\Shared\Models\Setting;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Throwable;

class SiteContent
{
    public const SETTING_KEY = 'site_content';

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

        $defaults = static::defaults();

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

        return static::$loaded = static::sanitize(static::mergeWithDefaults($decoded));
    }

    /**
     * @param  array<string, mixed>  $content
     */
    public static function save(array $content): void
    {
        $content = static::sanitize(static::mergeWithDefaults($content));

        Setting::query()->updateOrCreate(
            ['key' => static::SETTING_KEY],
            [
                'group' => 'site',
                'type' => 'json',
                'value' => json_encode($content, JSON_PRETTY_PRINT),
                'is_sensitive' => false,
            ],
        );

        static::$loaded = $content;
    }

    /**
     * @param  array<string, mixed>  $content
     * @return array<string, mixed>
     */
    public static function sanitize(array $content): array
    {
        return [
            'brand' => [
                'name' => static::string(Arr::get($content, 'brand.name')),
                'tagline' => static::string(Arr::get($content, 'brand.tagline')),
                'powered_by' => static::string(Arr::get($content, 'brand.powered_by')),
                'description' => static::text(Arr::get($content, 'brand.description')),
                'logo_path' => static::string(Arr::get($content, 'brand.logo_path')),
                'favicon_path' => static::string(Arr::get($content, 'brand.favicon_path')),
                'primary_color' => static::color(Arr::get($content, 'brand.primary_color'), '#2a7190'),
                'secondary_color' => static::color(Arr::get($content, 'brand.secondary_color'), '#f7f7f7'),
                'button_label' => static::string(Arr::get($content, 'brand.button_label')),
            ],
            'navigation' => [
                'home_label' => static::string(Arr::get($content, 'navigation.home_label')),
                'about_label' => static::string(Arr::get($content, 'navigation.about_label')),
                'jobs_label' => static::string(Arr::get($content, 'navigation.jobs_label')),
                'contact_label' => static::string(Arr::get($content, 'navigation.contact_label')),
                'sign_in_label' => static::string(Arr::get($content, 'navigation.sign_in_label')),
                'register_label' => static::string(Arr::get($content, 'navigation.register_label')),
                'links' => static::links(Arr::get($content, 'navigation.links', [])),
            ],
            'home' => [
                'eyebrow' => static::string(Arr::get($content, 'home.eyebrow')),
                'headline' => static::string(Arr::get($content, 'home.headline')),
                'description' => static::text(Arr::get($content, 'home.description')),
                'keyword_placeholder' => static::string(Arr::get($content, 'home.keyword_placeholder')),
                'location_placeholder' => static::string(Arr::get($content, 'home.location_placeholder')),
                'search_button_label' => static::string(Arr::get($content, 'home.search_button_label')),
                'hero_image_path' => static::string(Arr::get($content, 'home.hero_image_path')),
                'featured_jobs_heading' => static::string(Arr::get($content, 'home.featured_jobs_heading')),
                'featured_jobs_subheading' => static::string(Arr::get($content, 'home.featured_jobs_subheading')),
                'employers_heading' => static::string(Arr::get($content, 'home.employers_heading')),
                'employers_description' => static::text(Arr::get($content, 'home.employers_description')),
                'employer_tools_heading' => static::string(Arr::get($content, 'home.employer_tools_heading')),
                'employer_tools_description' => static::text(Arr::get($content, 'home.employer_tools_description')),
                'verified_candidates_subheading' => static::string(Arr::get($content, 'home.verified_candidates_subheading')),
                'verified_candidates_heading' => static::string(Arr::get($content, 'home.verified_candidates_heading')),
                'categories_subheading' => static::string(Arr::get($content, 'home.categories_subheading')),
                'categories_heading' => static::string(Arr::get($content, 'home.categories_heading')),
                'stories_subheading' => static::string(Arr::get($content, 'home.stories_subheading')),
                'stories_heading' => static::string(Arr::get($content, 'home.stories_heading')),
                'stories_description' => static::text(Arr::get($content, 'home.stories_description')),
                'candidates_heading' => static::string(Arr::get($content, 'home.candidates_heading')),
                'candidates_description' => static::text(Arr::get($content, 'home.candidates_description')),
            ],
            'home_sections' => static::sections(Arr::get($content, 'home_sections', [])),
            'footer' => [
                'description' => static::text(Arr::get($content, 'footer.description')),
                'powered_by' => static::string(Arr::get($content, 'footer.powered_by')),
                'platform_heading' => static::string(Arr::get($content, 'footer.platform_heading')),
                'policies_heading' => static::string(Arr::get($content, 'footer.policies_heading')),
                'copyright' => static::string(Arr::get($content, 'footer.copyright')),
                'platform_links' => static::links(Arr::get($content, 'footer.platform_links', [])),
                'policy_links' => static::links(Arr::get($content, 'footer.policy_links', [])),
            ],
            'contact' => [
                'email' => static::string(Arr::get($content, 'contact.email')),
                'phone' => static::string(Arr::get($content, 'contact.phone')),
                'whatsapp_url' => static::url(Arr::get($content, 'contact.whatsapp_url')),
                'facebook_url' => static::url(Arr::get($content, 'contact.facebook_url')),
                'linkedin_url' => static::url(Arr::get($content, 'contact.linkedin_url')),
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function defaults(): array
    {
        return [
            'brand' => [
                'name' => config('portal.brand.name', config('jobportal.brand_name', 'JobSearch')),
                'tagline' => config('portal.brand.tagline', 'Connecting global employers with skilled, verified, and job-ready talent.'),
                'powered_by' => config('portal.brand.powered_by', 'Powered by ICSA Group / NAS International College of London'),
                'description' => config('portal.brand.description', ''),
                'logo_path' => '',
                'favicon_path' => '',
                'primary_color' => '#2a7190',
                'secondary_color' => '#f7f7f7',
                'button_label' => 'Create Account',
            ],
            'navigation' => [
                'home_label' => 'Home',
                'about_label' => 'About Us',
                'jobs_label' => 'Job',
                'contact_label' => 'Contact',
                'sign_in_label' => 'Sign In',
                'register_label' => 'Create Account',
                'links' => [
                    ['label' => 'Home', 'url' => '/', 'enabled' => true],
                    ['label' => 'About Us', 'url' => '/about-us', 'enabled' => true],
                    ['label' => 'Job', 'url' => '/jobs', 'enabled' => true],
                    ['label' => 'Contact', 'url' => '/contact', 'enabled' => true],
                ],
            ],
            'home' => [
                'eyebrow' => config('portal.brand.powered_by', ''),
                'headline' => 'Search, Find, and Apply!',
                'description' => 'A global hiring platform connecting employers with skilled, verified, and job-ready candidates worldwide. Build a profile, upload your CV, explore international jobs, and apply with confidence.',
                'keyword_placeholder' => 'Job title or Keyword',
                'location_placeholder' => 'Location',
                'search_button_label' => 'Find Now!',
                'hero_image_path' => 'images/global-career-hero.webp',
                'featured_jobs_heading' => 'Latest international jobs',
                'featured_jobs_subheading' => 'Featured openings',
                'employers_heading' => 'Trusted hiring teams across regions',
                'employers_description' => 'Employers can verify documents, publish clear job benefits, and build trust before candidates apply.',
                'employer_tools_heading' => 'Post jobs and review stronger applications',
                'employer_tools_description' => 'Compare CVs, portfolios, video profiles, verification badges, visa readiness, relocation preference, and future trust scores.',
                'candidates_heading' => 'Create a profile that employers can trust',
                'candidates_description' => 'Upload CVs, add video introductions, showcase portfolios, list certificates, and earn optional verification badges.',
                'verified_candidates_subheading' => 'Verified candidates',
                'verified_candidates_heading' => 'Profiles built for faster shortlisting',
                'categories_subheading' => 'Top job categories',
                'categories_heading' => 'Search by skill, industry, or work style',
                'stories_subheading' => 'Career success stories',
                'stories_heading' => 'Designed for real outcomes',
                'stories_description' => 'Success-story slots are ready for candidates, employers, and verified graduate achievements as the platform grows.',
            ],
            'home_sections' => [
                'stats' => ['label' => 'Stats', 'enabled' => true],
                'jobs' => ['label' => 'Featured jobs', 'enabled' => true],
                'employers' => ['label' => 'Featured employers', 'enabled' => true],
                'candidate_pitch' => ['label' => 'Candidate and employer pitch', 'enabled' => true],
                'verified_candidates' => ['label' => 'Verified candidates', 'enabled' => true],
                'categories' => ['label' => 'Categories', 'enabled' => true],
                'stories' => ['label' => 'Success stories', 'enabled' => true],
                'how_it_works' => ['label' => 'How it works', 'enabled' => true],
            ],
            'footer' => [
                'description' => config('portal.brand.description', ''),
                'powered_by' => config('portal.brand.powered_by', ''),
                'platform_heading' => 'Platform',
                'policies_heading' => 'Policies',
                'copyright' => 'All rights reserved.',
                'platform_links' => [
                    ['label' => 'Find jobs', 'url' => '/jobs', 'enabled' => true],
                    ['label' => 'Build profile', 'url' => '/job-seekers', 'enabled' => true],
                    ['label' => 'CV builder', 'url' => '/cv-builder', 'enabled' => true],
                    ['label' => 'Portfolio', 'url' => '/portfolio', 'enabled' => true],
                    ['label' => 'Hire talent', 'url' => '/employers', 'enabled' => true],
                    ['label' => 'International support', 'url' => '/international-support', 'enabled' => true],
                ],
                'policy_links' => [
                    ['label' => 'Anti-Scam Policy', 'url' => '/policies/anti-scam', 'enabled' => true],
                    ['label' => 'Candidate verification', 'url' => '/candidate-verification', 'enabled' => true],
                    ['label' => 'Employer verification', 'url' => '/employer-verification', 'enabled' => true],
                    ['label' => 'Job Posting Rules', 'url' => '/policies/job-posting-rules', 'enabled' => true],
                    ['label' => 'Privacy Policy', 'url' => '/policies/privacy', 'enabled' => true],
                    ['label' => 'Terms', 'url' => '/policies/terms', 'enabled' => true],
                ],
            ],
            'contact' => [
                'email' => '',
                'phone' => '',
                'whatsapp_url' => '',
                'facebook_url' => '',
                'linkedin_url' => '',
            ],
        ];
    }

    public static function assetUrl(?string $path, ?string $fallback = null): string
    {
        $path = trim((string) $path);

        if ($path === '') {
            return asset($fallback ?: 'images/global-career-hero.webp');
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        if (Storage::disk('public')->exists($path)) {
            return Storage::url($path);
        }

        return asset($path);
    }

    /**
     * @param  array<string, mixed>  $content
     * @return array<string, mixed>
     */
    private static function mergeWithDefaults(array $content): array
    {
        $merged = array_replace_recursive(static::defaults(), $content);

        foreach (['navigation.links', 'footer.platform_links', 'footer.policy_links'] as $path) {
            if (Arr::has($content, $path)) {
                Arr::set($merged, $path, Arr::get($content, $path));
            }
        }

        return $merged;
    }

    private static function string(mixed $value): string
    {
        return trim(strip_tags((string) $value));
    }

    private static function text(mixed $value): string
    {
        return trim(strip_tags((string) $value));
    }

    private static function url(mixed $value): string
    {
        $value = static::string($value);

        return filter_var($value, FILTER_VALIDATE_URL) ? $value : '';
    }

    /**
     * @return array<int, array{label: string, url: string, enabled: bool}>
     */
    private static function links(mixed $links): array
    {
        if (! is_array($links)) {
            return [];
        }

        return collect($links)
            ->map(fn (array $link): array => [
                'label' => static::string($link['label'] ?? ''),
                'url' => static::string($link['url'] ?? ''),
                'enabled' => (bool) ($link['enabled'] ?? true),
            ])
            ->filter(fn (array $link): bool => $link['label'] !== '' && $link['url'] !== '')
            ->values()
            ->all();
    }

    /**
     * @return array<string, array{label: string, enabled: bool}>
     */
    private static function sections(mixed $sections): array
    {
        $defaults = static::defaults()['home_sections'];

        if (! is_array($sections)) {
            return $defaults;
        }

        foreach ($defaults as $key => $default) {
            $defaults[$key] = [
                'label' => static::string(Arr::get($sections, "{$key}.label", $default['label'])),
                'enabled' => (bool) Arr::get($sections, "{$key}.enabled", $default['enabled']),
            ];
        }

        return $defaults;
    }

    private static function color(mixed $value, string $fallback): string
    {
        $value = static::string($value);

        return preg_match('/^#[0-9a-fA-F]{6}$/', $value) === 1 ? $value : $fallback;
    }
}

<?php

declare(strict_types=1);

namespace App\Support;

use App\Domain\Shared\Models\Setting;
use Illuminate\Support\Facades\Schema;
use Throwable;

class PageContent
{
    public const SETTING_KEY = 'page_content';

    /**
     * @return array<string, array{title: string, eyebrow: string, description: string}>
     */
    public static function all(): array
    {
        $defaults = static::defaults();

        try {
            if (! Schema::hasTable('settings')) {
                return $defaults;
            }

            $stored = Setting::query()->where('key', static::SETTING_KEY)->value('value');
        } catch (Throwable) {
            return $defaults;
        }

        if (! is_string($stored) || trim($stored) === '') {
            return $defaults;
        }

        $decoded = json_decode($stored, true);

        if (! is_array($decoded)) {
            return $defaults;
        }

        return static::sanitize(array_replace($defaults, $decoded));
    }

    /**
     * @return array{title: string, eyebrow: string, description: string}
     */
    public static function get(string $key): array
    {
        return static::all()[$key] ?? [
            'title' => str($key)->headline()->toString(),
            'eyebrow' => '',
            'description' => '',
        ];
    }

    /**
     * @param  array<string, array<string, mixed>>  $pages
     */
    public static function save(array $pages): void
    {
        Setting::query()->updateOrCreate(
            ['key' => static::SETTING_KEY],
            [
                'group' => 'content',
                'type' => 'json',
                'value' => json_encode(static::sanitize($pages), JSON_PRETTY_PRINT),
                'is_sensitive' => false,
            ],
        );
    }

    /**
     * @return array<int, array{key: string, title: string, eyebrow: string, description: string}>
     */
    public static function toFormState(): array
    {
        return collect(static::all())
            ->map(fn (array $page, string $key): array => ['key' => $key] + $page)
            ->values()
            ->all();
    }

    /**
     * @param  array<int, array<string, mixed>>  $rows
     * @return array<string, array{title: string, eyebrow: string, description: string}>
     */
    public static function fromFormState(array $rows): array
    {
        return collect($rows)
            ->filter(fn (array $row): bool => filled($row['key'] ?? '') && filled($row['title'] ?? ''))
            ->mapWithKeys(fn (array $row): array => [
                static::key($row['key']) => [
                    'title' => static::text($row['title'] ?? ''),
                    'eyebrow' => static::text($row['eyebrow'] ?? ''),
                    'description' => static::text($row['description'] ?? ''),
                ],
            ])
            ->all();
    }

    /**
     * @param  array<string, mixed>  $pages
     * @return array<string, array{title: string, eyebrow: string, description: string}>
     */
    private static function sanitize(array $pages): array
    {
        return collect($pages)
            ->filter(fn (mixed $page, mixed $key): bool => is_string($key) && is_array($page))
            ->mapWithKeys(fn (array $page, string $key): array => [
                static::key($key) => [
                    'title' => static::text($page['title'] ?? ''),
                    'eyebrow' => static::text($page['eyebrow'] ?? ''),
                    'description' => static::text($page['description'] ?? ''),
                ],
            ])
            ->all();
    }

    /**
     * @return array<string, array{title: string, eyebrow: string, description: string}>
     */
    private static function defaults(): array
    {
        return [
            'job-seekers' => ['title' => 'Build a global candidate profile', 'eyebrow' => 'Job seekers', 'description' => 'Create a mini professional website with CV, video introduction, portfolio, certificates, preferences, and optional verified ICSA/NAS badges.'],
            'jobs' => ['title' => 'Global job search', 'eyebrow' => 'International opportunities', 'description' => 'Search international, remote, urgent, verified-employer, and visa-sponsorship friendly jobs.'],
            'candidate-search' => ['title' => 'Search candidates globally', 'eyebrow' => 'Employer candidate database', 'description' => 'Basic search previews are free. Advanced search and CV/contact reveal use premium packages or credits.'],
            'candidate-verification' => ['title' => 'Candidate verification', 'eyebrow' => 'Candidate trust checks', 'description' => 'Verification badges help employers understand which parts of a candidate profile have been reviewed.'],
            'career-coach' => ['title' => 'Rule-based career coach', 'eyebrow' => 'Career guidance', 'description' => 'Get a practical starter plan, job-fit estimate, skill gaps, portfolio suggestions, and interview questions without requiring an AI provider.'],
            'cv-builder' => ['title' => 'CV and resume builder', 'eyebrow' => 'Candidate tools', 'description' => 'Create an ATS-friendly CV, upload an existing resume, build country-specific versions, prepare cover letters, and track missing information before applying.'],
            'ecosystem' => ['title' => 'From job portal to career ecosystem', 'eyebrow' => 'Career ecosystem', 'description' => 'The first release enables the job portal. The architecture stays ready for courses, internships, coaching, credentials, freelance work, events, and AI career guidance.'],
            'employer-dashboard' => ['title' => 'Employer dashboard', 'eyebrow' => 'Employer workspace', 'description' => 'Manage jobs, applicants, candidate search, packages, interviews, and hiring analytics.'],
            'employer-register' => ['title' => 'Employer registration', 'eyebrow' => 'Create employer account', 'description' => 'Create a verified employer account and prepare documents for trusted hiring workflows.'],
            'employer-verification' => ['title' => 'Employer verification', 'eyebrow' => 'Employer trust levels', 'description' => 'Employer verification protects job seekers from fake jobs, unclear recruiters, and unsafe overseas offers.'],
            'employers' => ['title' => 'Hire verified global talent', 'eyebrow' => 'Employers and agencies', 'description' => 'Post jobs, verify your company, review applicants, search candidates, watch video profiles, view portfolios, and build a trusted hiring pipeline.'],
            'international-support' => ['title' => 'International data, currency, and language support', 'eyebrow' => 'Global readiness', 'description' => 'Jobs and candidate profiles are structured for country, city, region, time zone, currency, authorization, visa support, and relocation preferences.'],
            'institution-dashboards' => ['title' => 'Institution placement dashboards', 'eyebrow' => 'Verified graduate outcomes', 'description' => 'Optional ICSA, NAS, and ICS dashboards show placement rates, salary averages, employer partners, top industries, and career-coach usage statistics.'],
            'packages' => ['title' => 'Employer packages', 'eyebrow' => 'Employer monetization', 'description' => 'Revenue comes from employer tools: job posts, featured listings, urgent hiring boosts, CV database credits, and recruitment agency packages.'],
            'platform-admin' => ['title' => 'Platform governance overview', 'eyebrow' => 'Platform operations', 'description' => 'A high-level overview of governance areas for users, candidates, employers, jobs, verifications, payments, content, reports, reference data, and communications.'],
            'portfolio' => ['title' => 'Portfolio and project section', 'eyebrow' => 'Candidate showcase', 'description' => 'Show practical work through images, PDFs, videos, links, certificates, case studies, assignments, and project descriptions.'],
            'trust-safety' => ['title' => 'Trust and safety', 'eyebrow' => 'Platform governance', 'description' => 'International hiring needs strong scam prevention, employer verification, candidate consent, audit logs, and clear no-illegal-fees rules.'],
            'video-profile' => ['title' => 'Video profile', 'eyebrow' => 'Candidate trust feature', 'description' => 'Candidates can introduce themselves professionally with short approved videos while controlling privacy and employer visibility.'],
            'blog' => ['title' => 'Career resources and SEO content', 'eyebrow' => 'Career resources', 'description' => 'Content hubs for global traffic: job search tips, CV writing, interviews, work abroad guides, visa sponsorship, remote work, employer hiring tips, and success stories.'],
        ];
    }

    private static function key(mixed $key): string
    {
        return str((string) $key)->trim()->lower()->slug('-')->toString();
    }

    private static function text(mixed $value): string
    {
        return trim(strip_tags((string) $value));
    }
}

<?php

declare(strict_types=1);

namespace App\Support;

use App\Domain\Portal\Models\Job;
use Illuminate\Support\Collection;

class PortalJobPresenter
{
    /**
     * @return array<int, array<string, mixed>>
     */
    public static function publishedJobs(): array
    {
        $jobs = Job::query()
            ->with(['employer', 'category'])
            ->where('status', 'published')
            ->orderByDesc('is_featured')
            ->latest('updated_at')
            ->get()
            ->map(fn (Job $job): array => static::job($job))
            ->values()
            ->all();

        return $jobs ?: PortalData::load()['jobs'];
    }

    /**
     * @return array<string, mixed>|null
     */
    public static function find(string $slug): ?array
    {
        $job = Job::query()
            ->with(['employer', 'category'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->first();

        if ($job instanceof Job) {
            return static::job($job);
        }

        return collect(PortalData::load()['jobs'])->firstWhere('slug', $slug);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function similar(string $currentSlug, ?string $category = null): array
    {
        return collect(static::publishedJobs())
            ->reject(fn (array $job): bool => $job['slug'] === $currentSlug)
            ->when($category, fn (Collection $jobs): Collection => $jobs->sortByDesc(fn (array $job): bool => $job['category'] === $category))
            ->take(3)
            ->values()
            ->all();
    }

    /**
     * @return array<string, mixed>
     */
    private static function job(Job $job): array
    {
        $employer = $job->employer;
        $badges = collect([
            $job->visa_sponsorship ? 'Visa sponsorship' : null,
            $job->relocation_support ? 'Relocation support' : null,
            $job->is_featured ? 'Featured employer' : null,
            $job->is_urgent ? 'Urgent' : null,
            $employer?->verification_status === 'verified' ? 'Verified employer' : null,
        ])->filter()->values()->all();

        return [
            'id' => $job->id,
            'slug' => $job->slug,
            'title' => $job->title,
            'company' => $employer?->name ?? 'Confidential employer',
            'company_slug' => $employer?->slug,
            'company_logo' => $employer?->logo_path,
            'city' => $job->city ?? '',
            'country' => $job->country ?? '',
            'mode' => str($job->work_mode)->replace('_', '-')->title()->toString(),
            'salary' => static::salary($job),
            'type' => str($job->employment_type)->replace('_', '-')->title()->toString(),
            'category' => $job->category?->name ?? '',
            'badges' => $badges,
            'urgent' => $job->is_urgent,
            'deadline' => $job->application_deadline?->toFormattedDateString() ?? 'Open until filled',
            'vacancies' => $job->vacancies,
            'description' => $job->description ?: 'The employer will provide full role details during application review.',
            'responsibilities' => $job->responsibilities ?: ['Review role details', 'Complete employer screening', 'Coordinate onboarding'],
            'skills' => $job->skills ?: ['Communication', 'Role-specific experience'],
            'requirements' => $job->requirements ?: ['Relevant experience', 'Complete candidate profile'],
            'benefits' => $job->benefits ?: ['Transparent hiring process', 'Employer-managed application review'],
        ];
    }

    private static function salary(Job $job): string
    {
        if ($job->salary_min === null && $job->salary_max === null) {
            return 'Salary not disclosed';
        }

        $currency = $job->currency ?: 'USD';

        if ($job->salary_min !== null && $job->salary_max !== null) {
            return sprintf('%s %s - %s monthly', $currency, number_format($job->salary_min), number_format($job->salary_max));
        }

        return sprintf('%s %s monthly', $currency, number_format((int) ($job->salary_min ?? $job->salary_max)));
    }
}

<?php

declare(strict_types=1);

namespace App\Support;

use App\Domain\Portal\Models\Employer;

class EmployerEntitlements
{
    /**
     * @return array<string, array<string, int|string>>
     */
    public static function plans(): array
    {
        return [
            'free' => [
                'label' => 'Free Employer Account',
                'job_posts' => 2,
                'featured_jobs' => 0,
                'candidate_searches' => 10,
                'cv_credits' => 1,
                'contact_credits' => 1,
                'matching_requests' => 0,
                'ai_requests' => 0,
            ],
            'growth' => [
                'label' => 'Growth',
                'job_posts' => 10,
                'featured_jobs' => 2,
                'candidate_searches' => 100,
                'cv_credits' => 15,
                'contact_credits' => 10,
                'matching_requests' => 2,
                'ai_requests' => 2,
            ],
            'premium' => [
                'label' => 'Premium Employer Package',
                'job_posts' => 25,
                'featured_jobs' => 6,
                'candidate_searches' => 500,
                'cv_credits' => 60,
                'contact_credits' => 40,
                'matching_requests' => 8,
                'ai_requests' => 8,
            ],
            'enterprise' => [
                'label' => 'Enterprise',
                'job_posts' => 100,
                'featured_jobs' => 20,
                'candidate_searches' => 2000,
                'cv_credits' => 250,
                'contact_credits' => 200,
                'matching_requests' => 25,
                'ai_requests' => 25,
            ],
        ];
    }

    /**
     * @return array<string, int|string>
     */
    public static function forEmployer(Employer $employer): array
    {
        $plan = $employer->billing_plan ?: 'free';

        return static::plans()[$plan] ?? static::plans()['free'];
    }

    /**
     * @return array<string, string>
     */
    public static function planOptions(): array
    {
        return collect(static::plans())
            ->mapWithKeys(fn (array $plan, string $key): array => [$key => (string) $plan['label']])
            ->all();
    }

    public static function resetCreditsForPlan(Employer $employer): void
    {
        $entitlements = static::forEmployer($employer);

        $employer->forceFill([
            'job_post_limit' => (int) $entitlements['job_posts'],
            'featured_job_credits' => (int) $entitlements['featured_jobs'],
            'candidate_search_credits' => (int) $entitlements['candidate_searches'],
            'cv_access_credits' => (int) $entitlements['cv_credits'],
            'candidate_contact_credits' => (int) $entitlements['contact_credits'],
            'matching_request_credits' => (int) $entitlements['matching_requests'],
            'ai_recruitment_credits' => (int) $entitlements['ai_requests'],
        ])->save();
    }
}

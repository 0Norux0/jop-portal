<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Support\Str;

class CareerCoach
{
    /**
     * @param  array<string, mixed>  $input
     * @return array<string, mixed>
     */
    public static function advise(array $input): array
    {
        $target = trim((string) ($input['target_role'] ?? ''));
        $skills = collect(explode(',', (string) ($input['skills'] ?? '')))
            ->map(fn (string $skill): string => trim($skill))
            ->filter()
            ->values();
        $hasCv = (bool) ($input['has_cv'] ?? false);
        $hasPortfolio = (bool) ($input['has_portfolio'] ?? false);
        $hasVideo = (bool) ($input['has_video'] ?? false);

        $lowerTarget = Str::lower($target);
        $recommendedSkills = match (true) {
            str_contains($lowerTarget, 'care') => ['Safeguarding', 'First aid', 'Elder-care documentation', 'English communication'],
            str_contains($lowerTarget, 'developer'), str_contains($lowerTarget, 'it') => ['Git', 'Portfolio projects', 'API fundamentals', 'Database basics'],
            str_contains($lowerTarget, 'cyber') => ['Networking', 'Linux', 'Security fundamentals', 'Incident notes'],
            str_contains($lowerTarget, 'admin') => ['MS Office', 'Email writing', 'Documentation', 'Customer service'],
            str_contains($lowerTarget, 'account') => ['Excel', 'Bookkeeping', 'Reconciliation', 'Reporting'],
            default => ['Communication', 'Role-specific portfolio sample', 'Interview practice', 'Clear CV headline'],
        };

        $missingSkills = collect($recommendedSkills)
            ->reject(fn (string $skill): bool => $skills->contains(fn (string $existing): bool => Str::contains(Str::lower($existing), Str::lower($skill))))
            ->values()
            ->all();

        $score = 35
            + min(25, $skills->count() * 5)
            + ($hasCv ? 15 : 0)
            + ($hasPortfolio ? 15 : 0)
            + ($hasVideo ? 10 : 0);

        return [
            'fit_score' => min(100, $score),
            'next_steps' => array_values(array_filter([
                $hasCv ? null : 'Upload or build an ATS-friendly CV.',
                $hasPortfolio ? null : 'Add at least one portfolio/project or work sample.',
                $hasVideo ? null : 'Record a short professional video introduction.',
                'Apply to roles where your skills match at least 60 percent of the requirements.',
            ])),
            'skill_gaps' => $missingSkills,
            'interview_questions' => [
                'Tell us about your experience related to '.$target.'.',
                'What proof can you show for your strongest skill?',
                'Describe a difficult work situation and how you handled it.',
                'Why are you interested in this country, role, or work setup?',
            ],
        ];
    }
}

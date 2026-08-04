<?php

declare(strict_types=1);

namespace App\Support;

class InstitutionMetrics
{
    /**
     * @return array<int, array<string, mixed>>
     */
    public static function dashboards(): array
    {
        return [
            [
                'slug' => 'icsa-kuwait',
                'name' => 'ICSA Kuwait',
                'location' => 'Kuwait',
                'graduate_employment_rate' => '78%',
                'average_salary' => 'KWD 420 monthly',
                'placement_percentage' => '64%',
                'employer_partners' => 38,
                'top_industries' => ['Office Administration', 'Accounting', 'Cybersecurity', 'Hospitality'],
                'ai_career_statistics' => ['Job-fit plans generated' => 124, 'Skill gaps flagged' => 312, 'Interview prompts generated' => 486],
            ],
            [
                'slug' => 'icsa-uae',
                'name' => 'ICSA UAE',
                'location' => 'UAE',
                'graduate_employment_rate' => '74%',
                'average_salary' => 'AED 4,100 monthly',
                'placement_percentage' => '61%',
                'employer_partners' => 44,
                'top_industries' => ['Hospitality', 'Office Administration', 'Healthcare Support', 'IT Support'],
                'ai_career_statistics' => ['Job-fit plans generated' => 98, 'Skill gaps flagged' => 241, 'Interview prompts generated' => 352],
            ],
            [
                'slug' => 'nas-london',
                'name' => 'NAS London',
                'location' => 'United Kingdom',
                'graduate_employment_rate' => '81%',
                'average_salary' => 'GBP 2,350 monthly',
                'placement_percentage' => '69%',
                'employer_partners' => 52,
                'top_industries' => ['Caregiving', 'Healthcare Support', 'IT', 'Business Administration'],
                'ai_career_statistics' => ['Job-fit plans generated' => 143, 'Skill gaps flagged' => 287, 'Interview prompts generated' => 501],
            ],
            [
                'slug' => 'ics-london',
                'name' => 'ICS London',
                'location' => 'United Kingdom',
                'graduate_employment_rate' => '76%',
                'average_salary' => 'GBP 2,180 monthly',
                'placement_percentage' => '62%',
                'employer_partners' => 35,
                'top_industries' => ['Digital Skills', 'Design', 'Office Administration', 'Remote Work'],
                'ai_career_statistics' => ['Job-fit plans generated' => 86, 'Skill gaps flagged' => 199, 'Interview prompts generated' => 274],
            ],
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    public static function find(string $slug): ?array
    {
        return collect(self::dashboards())->firstWhere('slug', $slug);
    }
}

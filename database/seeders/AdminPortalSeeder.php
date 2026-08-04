<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\Portal\Models\Candidate;
use App\Domain\Portal\Models\Employer;
use App\Domain\Portal\Models\HomepageSection;
use App\Domain\Portal\Models\Job;
use App\Domain\Portal\Models\JobApplication;
use App\Domain\Portal\Models\JobCategory;
use App\Domain\Portal\Models\MediaAsset;
use App\Domain\Portal\Models\TrustReport;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AdminPortalSeeder extends Seeder
{
    public function run(): void
    {
        $categories = collect([
            ['name' => 'Caregiving', 'group' => 'Healthcare'],
            ['name' => 'Information Technology', 'group' => 'Technology'],
            ['name' => 'Hospitality', 'group' => 'Services'],
            ['name' => 'Cybersecurity', 'group' => 'Technology'],
            ['name' => 'Accounting', 'group' => 'Finance'],
            ['name' => 'Design & Creative', 'group' => 'Creative'],
        ])->mapWithKeys(function (array $category): array {
            $record = JobCategory::query()->updateOrCreate(
                ['slug' => Str::slug($category['name'])],
                [
                    'name' => $category['name'],
                    'group' => $category['group'],
                    'is_active' => true,
                    'sort_order' => 10,
                ],
            );

            return [$category['name'] => $record];
        });

        $employers = collect([
            ['name' => 'Northbridge Care Group', 'industry' => 'Healthcare', 'country' => 'United Kingdom', 'city' => 'Manchester'],
            ['name' => 'MapleCloud Labs', 'industry' => 'Technology', 'country' => 'Canada', 'city' => 'Toronto'],
            ['name' => 'Pearl Vista Hotels', 'industry' => 'Hospitality', 'country' => 'UAE', 'city' => 'Dubai'],
        ])->mapWithKeys(function (array $employer): array {
            $record = Employer::query()->updateOrCreate(
                ['slug' => Str::slug($employer['name'])],
                [
                    ...$employer,
                    'contact_name' => 'Hiring Manager',
                    'contact_email' => 'hr@'.Str::slug($employer['name'], '').'.test',
                    'verification_status' => 'verified',
                    'status' => 'active',
                    'description' => $employer['name'].' is available for admin-managed job posting and verification workflows.',
                ],
            );

            return [$employer['name'] => $record];
        });

        $jobs = [
            [
                'title' => 'Senior Caregiver',
                'employer' => 'Northbridge Care Group',
                'category' => 'Caregiving',
                'country' => 'United Kingdom',
                'city' => 'Manchester',
                'work_mode' => 'on_site',
                'employment_type' => 'full_time',
                'currency' => 'GBP',
                'salary_min' => 2100,
                'salary_max' => 2650,
                'is_featured' => true,
                'is_urgent' => true,
                'visa_sponsorship' => true,
            ],
            [
                'title' => 'Remote Laravel Developer',
                'employer' => 'MapleCloud Labs',
                'category' => 'Information Technology',
                'country' => 'Canada',
                'city' => 'Toronto',
                'work_mode' => 'remote',
                'employment_type' => 'contract',
                'currency' => 'USD',
                'salary_min' => 2800,
                'salary_max' => 4200,
                'is_featured' => true,
            ],
            [
                'title' => 'Front Office Executive',
                'employer' => 'Pearl Vista Hotels',
                'category' => 'Hospitality',
                'country' => 'UAE',
                'city' => 'Dubai',
                'work_mode' => 'on_site',
                'employment_type' => 'full_time',
                'currency' => 'AED',
                'salary_min' => 3500,
                'salary_max' => 4500,
                'is_urgent' => true,
            ],
        ];

        foreach ($jobs as $job) {
            Job::query()->updateOrCreate(
                ['slug' => Str::slug($job['title'].' '.$job['city'])],
                [
                    'employer_id' => $employers[$job['employer']]->id,
                    'job_category_id' => $categories[$job['category']]->id,
                    'title' => $job['title'],
                    'country' => $job['country'],
                    'city' => $job['city'],
                    'work_mode' => $job['work_mode'],
                    'employment_type' => $job['employment_type'],
                    'currency' => $job['currency'],
                    'salary_min' => $job['salary_min'],
                    'salary_max' => $job['salary_max'],
                    'vacancies' => 3,
                    'application_deadline' => now()->addMonth()->toDateString(),
                    'status' => 'published',
                    'is_featured' => $job['is_featured'] ?? false,
                    'is_urgent' => $job['is_urgent'] ?? false,
                    'visa_sponsorship' => $job['visa_sponsorship'] ?? false,
                    'description' => 'Admin-managed job record ready for real employer content.',
                    'responsibilities' => ['Review applications', 'Interview shortlisted candidates', 'Coordinate onboarding'],
                    'skills' => ['Communication', 'Role-specific experience'],
                    'requirements' => ['Relevant experience', 'Complete candidate profile'],
                    'benefits' => ['Transparent hiring process', 'Employer-managed application review'],
                ],
            );
        }

        $candidate = Candidate::query()->updateOrCreate(
            ['email' => 'candidate@jobportal.test'],
            [
                'full_name' => 'Ayesha Khan',
                'headline' => 'Certified Caregiver open to UK roles',
                'phone' => '+965 5000 1000',
                'country' => 'Pakistan',
                'city' => 'Lahore',
                'current_job_title' => 'Care Assistant',
                'preferred_job_category' => 'Caregiving',
                'linkedin_url' => 'https://www.linkedin.com/in/demo-candidate',
                'portfolio_url' => 'https://portfolio.example.com/demo-candidate',
                'verification_status' => 'verified',
                'availability_status' => 'open_to_work',
                'trust_score' => 82,
                'skills' => ['Elder care', 'First aid', 'English'],
                'bio' => 'Demo candidate profile for application testing.',
            ],
        );

        JobApplication::query()->updateOrCreate(
            ['candidate_email' => $candidate->email, 'job_id' => Job::query()->where('slug', 'senior-caregiver-manchester')->value('id')],
            [
                'candidate_id' => $candidate->id,
                'candidate_name' => $candidate->full_name,
                'method' => 'linkedin_profile',
                'status' => 'submitted',
                'linkedin_url' => $candidate->linkedin_url,
                'cover_letter' => 'Demo application submitted with a LinkedIn profile.',
            ],
        );

        TrustReport::query()->updateOrCreate(
            ['subject_type' => 'job', 'subject_reference' => 'demo-report'],
            [
                'reason' => 'Suspicious or unclear job details',
                'status' => 'open',
                'priority' => 'normal',
                'description' => 'Demo trust report for admin moderation workflow.',
            ],
        );

        MediaAsset::query()->updateOrCreate(
            ['path' => 'site/nas-logo-cropped.png'],
            [
                'name' => 'NAS Logo Cropped',
                'collection' => 'branding',
                'disk' => 'public',
                'mime_type' => 'image/png',
                'is_public' => true,
                'alt_text' => 'NAS logo',
            ],
        );

        foreach (['hero', 'jobs', 'employers', 'candidates', 'categories', 'stories'] as $index => $key) {
            HomepageSection::query()->updateOrCreate(
                ['key' => $key],
                [
                    'title' => Str::headline($key),
                    'eyebrow' => 'Homepage',
                    'description' => 'Admin-managed homepage section.',
                    'is_enabled' => true,
                    'sort_order' => ($index + 1) * 10,
                    'content' => [],
                ],
            );
        }
    }
}

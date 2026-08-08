<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\Identity\Enums\AccountStatus;
use App\Domain\Identity\Enums\RegistrationPurpose;
use App\Domain\Identity\Enums\RegistrationSource;
use App\Domain\Identity\Enums\Role as RoleEnum;
use App\Domain\Identity\Models\User;
use App\Support\CountryRepository;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DemoAccountsSeeder extends Seeder
{
    public function run(): void
    {
        $password = (string) env('DEMO_ACCOUNT_PASSWORD', Str::password(18));

        $this->createUser(
            role: RoleEnum::JobSeeker,
            name: 'Ayesha Khan',
            email: 'candidate@jobportal.test',
            purpose: RegistrationPurpose::FindJob,
            password: $password,
            extra: [
                'phone' => '+965 5000 1001',
                'country' => 'Pakistan',
                'city' => 'Lahore',
                'nationality' => 'Pakistani',
                'current_job_title' => 'Certified Caregiver',
                'preferred_job_category' => 'Caregiving',
                'visa_work_permit_status' => 'Needs employer sponsorship',
                'preferred_work_countries' => ['United Kingdom', 'Kuwait', 'UAE'],
                'willing_to_relocate' => true,
                'available_for_remote_work' => false,
            ],
        );

        $this->createUser(
            role: RoleEnum::Employer,
            name: 'Sarah Mitchell',
            email: 'employer@jobportal.test',
            purpose: RegistrationPurpose::Hire,
            password: $password,
            extra: [
                'phone' => '+965 5000 2002',
                'country' => 'United Kingdom',
                'city' => 'Manchester',
                'current_job_title' => 'Hiring Manager, Northbridge Care Group',
            ],
        );

        $this->createUser(
            role: RoleEnum::RecruitmentAgency,
            name: 'Omar Rahman',
            email: 'agency@jobportal.test',
            purpose: RegistrationPurpose::RecruitmentAgency,
            password: $password,
            extra: [
                'phone' => '+965 5000 3003',
                'country' => 'Kuwait',
                'city' => 'Kuwait City',
                'current_job_title' => 'Recruitment Agency Consultant',
            ],
        );

        $this->createUser(
            role: RoleEnum::Administrator,
            name: 'Demo Administrator',
            email: 'admin@jobportal.test',
            purpose: RegistrationPurpose::General,
            password: $password,
            extra: [
                'phone' => '+965 5000 4004',
                'country' => 'Kuwait',
                'city' => 'Kuwait City',
                'current_job_title' => 'Portal Administrator',
            ],
        );
    }

    /**
     * @param  array<string, mixed>  $extra
     */
    private function createUser(RoleEnum $role, string $name, string $email, RegistrationPurpose $purpose, string $password, array $extra = []): void
    {
        $user = User::query()->updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => Hash::make($password),
                'status' => AccountStatus::Active,
                'email_verified_at' => now(),
                'terms_accepted_at' => now(),
                'privacy_accepted_at' => now(),
                'registration_purpose' => $purpose,
                'registration_source' => RegistrationSource::AdminCreated,
                'preferred_timezone' => CountryRepository::timezoneForCountry((string) ($extra['country'] ?? null)),
            ] + $extra,
        );

        if (! $user->hasRole($role->value)) {
            $user->assignRole($role->value);
        }
    }
}

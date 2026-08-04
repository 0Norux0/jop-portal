<?php

declare(strict_types=1);

namespace App\Actions\Fortify;

use App\Domain\Identity\Enums\AccountStatus;
use App\Domain\Identity\Enums\RegistrationPurpose;
use App\Domain\Identity\Enums\RegistrationSource;
use App\Domain\Identity\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    /**
     * @param  array<string, mixed>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique(User::class)],
            'password' => ['required', 'string', 'min:12', 'confirmed'],
            'purpose' => ['required', Rule::enum(RegistrationPurpose::class)],
            'phone' => ['nullable', 'string', 'max:32'],
            'country' => ['required', 'string', 'max:120'],
            'city' => ['required', 'string', 'max:120'],
            'nationality' => ['required', 'string', 'max:120'],
            'current_job_title' => ['required', 'string', 'max:160'],
            'preferred_job_category' => ['required', 'string', 'max:160'],
            'linkedin_url' => ['nullable', 'url', 'max:255'],
            'portfolio_url' => ['nullable', 'url', 'max:255'],
            'personal_website_url' => ['nullable', 'url', 'max:255'],
            'github_url' => ['nullable', 'url', 'max:255'],
            'behance_url' => ['nullable', 'url', 'max:255'],
            'youtube_url' => ['nullable', 'url', 'max:255'],
            'tiktok_url' => ['nullable', 'url', 'max:255'],
            'visa_work_permit_status' => ['nullable', 'string', 'max:160'],
            'preferred_work_countries' => ['nullable', 'array'],
            'preferred_work_countries.*' => ['string', 'max:120'],
            'willing_to_relocate' => ['nullable', 'boolean'],
            'available_for_remote_work' => ['nullable', 'boolean'],
            'terms' => ['accepted'],
        ])->validate();

        $purpose = RegistrationPurpose::from($input['purpose']);

        return DB::transaction(function () use ($input, $purpose): User {
            $user = User::create([
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => Hash::make($input['password']),
                'phone' => $input['phone'] ?? null,
                'country' => $input['country'],
                'city' => $input['city'],
                'nationality' => $input['nationality'],
                'current_job_title' => $input['current_job_title'],
                'preferred_job_category' => $input['preferred_job_category'],
                'linkedin_url' => $input['linkedin_url'] ?? null,
                'portfolio_url' => $input['portfolio_url'] ?? null,
                'personal_website_url' => $input['personal_website_url'] ?? null,
                'github_url' => $input['github_url'] ?? null,
                'behance_url' => $input['behance_url'] ?? null,
                'youtube_url' => $input['youtube_url'] ?? null,
                'tiktok_url' => $input['tiktok_url'] ?? null,
                'visa_work_permit_status' => $input['visa_work_permit_status'] ?? null,
                'preferred_work_countries' => $input['preferred_work_countries'] ?? [],
                'willing_to_relocate' => (bool) ($input['willing_to_relocate'] ?? false),
                'available_for_remote_work' => (bool) ($input['available_for_remote_work'] ?? false),
                'status' => AccountStatus::PendingEmailVerification,
                'registration_purpose' => $purpose,
                'registration_source' => RegistrationSource::Web,
                'terms_accepted_at' => now(),
                'privacy_accepted_at' => now(),
                'marketing_consent' => (bool) ($input['marketing_consent'] ?? false),
            ]);

            if (($role = $purpose->initialRole()) !== null) {
                $user->assignRole($role->value);
            }

            return $user;
        });
    }
}

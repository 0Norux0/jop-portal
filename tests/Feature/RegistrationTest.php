<?php

declare(strict_types=1);

use App\Domain\Identity\Enums\AccountStatus;
use App\Domain\Identity\Enums\Role as RoleEnum;
use App\Domain\Identity\Models\User;
use Database\Seeders\RolePermissionSeeder;

beforeEach(function (): void {
    $this->seed(RolePermissionSeeder::class);
});

it('registers a job seeker and assigns the job seeker role', function (): void {
    $response = $this->post('/register', [
        'name' => 'Test Seeker',
        'email' => 'seeker@example.com',
        'password' => 'super-secret-1234',
        'password_confirmation' => 'super-secret-1234',
        'purpose' => 'find_job',
        'country' => 'Kuwait',
        'city' => 'Kuwait City',
        'nationality' => 'Kuwaiti',
        'current_job_title' => 'Working full-time',
        'preferred_job_category' => 'Information Technology',
        'terms' => 'on',
    ]);

    $user = User::where('email', 'seeker@example.com')->first();

    expect($user)->not->toBeNull()
        ->and($user->status)->toBe(AccountStatus::PendingEmailVerification)
        ->and($user->hasRole(RoleEnum::JobSeeker->value))->toBeTrue()
        ->and($user->terms_accepted_at)->not->toBeNull();
});

it('rejects registration without accepting terms', function (): void {
    $this->post('/register', [
        'name' => 'No Terms',
        'email' => 'noterms@example.com',
        'password' => 'super-secret-1234',
        'password_confirmation' => 'super-secret-1234',
        'purpose' => 'find_job',
    ]);

    expect(User::where('email', 'noterms@example.com')->exists())->toBeFalse();
});

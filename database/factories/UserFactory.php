<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Domain\Identity\Enums\AccountStatus;
use App\Domain\Identity\Enums\RegistrationSource;
use App\Domain\Identity\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password-1234567890'),
            'status' => AccountStatus::Active,
            'registration_source' => RegistrationSource::Web,
            'preferred_locale' => 'en',
            'preferred_timezone' => 'UTC',
            'terms_accepted_at' => now(),
            'privacy_accepted_at' => now(),
            'remember_token' => Str::random(10),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn () => [
            'email_verified_at' => null,
            'status' => AccountStatus::PendingEmailVerification,
        ]);
    }

    public function suspended(): static
    {
        return $this->state(fn () => ['status' => AccountStatus::Suspended]);
    }
}

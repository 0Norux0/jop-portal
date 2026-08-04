<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Domain\Identity\Models\LoginActivity;
use App\Domain\Identity\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LoginActivity>
 */
class LoginActivityFactory extends Factory
{
    protected $model = LoginActivity::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'successful' => true,
            'ip_address' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
            'failure_reason' => null,
            'logged_in_at' => now(),
        ];
    }

    public function failed(): static
    {
        return $this->state(fn () => [
            'successful' => false,
            'failure_reason' => 'invalid_credentials',
            'logged_in_at' => null,
        ]);
    }
}

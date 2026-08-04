<?php

declare(strict_types=1);

use App\Domain\Identity\Models\User;

it('throttles repeated failed logins', function (): void {
    $user = User::factory()->create(['password' => bcrypt('correct-horse-battery')]);

    $max = (int) config('jobportal.security.login_max_attempts', 5);

    for ($i = 0; $i < $max; $i++) {
        $this->post('/login', ['email' => $user->email, 'password' => 'nope']);
    }

    $response = $this->post('/login', ['email' => $user->email, 'password' => 'nope']);

    // Fortify returns a validation error containing a throttle message.
    $response->assertSessionHasErrors('email');
    $this->assertGuest();
})->group('throttle');

<?php

declare(strict_types=1);

use App\Domain\Identity\Models\User;

it('does not authenticate with a wrong password', function (): void {
    $user = User::factory()->create(['password' => bcrypt('correct-horse-battery')]);

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
});

it('records a failed login activity with a coarse reason only', function (): void {
    $user = User::factory()->create(['password' => bcrypt('correct-horse-battery')]);

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $activity = $user->loginActivities()->where('successful', false)->first();

    expect($activity)->not->toBeNull()
        ->and($activity->failure_reason)->toBe('invalid_credentials');
});

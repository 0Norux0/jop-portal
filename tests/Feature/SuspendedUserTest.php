<?php

declare(strict_types=1);

use App\Domain\Identity\Models\User;

it('blocks a suspended user from authenticating', function (): void {
    $user = User::factory()->suspended()->create(['password' => bcrypt('correct-horse-battery')]);

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'correct-horse-battery',
    ]);

    $this->assertGuest();
});

it('records account_blocked when a suspended user attempts login', function (): void {
    $user = User::factory()->suspended()->create(['password' => bcrypt('correct-horse-battery')]);

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'correct-horse-battery',
    ]);

    expect($user->loginActivities()->where('failure_reason', 'account_blocked')->exists())->toBeTrue();
});

<?php

declare(strict_types=1);

use App\Domain\Identity\Models\User;

it('logs in an active user with correct credentials', function (): void {
    $user = User::factory()->create(['password' => bcrypt('correct-horse-battery')]);

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'correct-horse-battery',
    ]);

    $this->assertAuthenticatedAs($user);
});

it('records a successful login activity row', function (): void {
    $user = User::factory()->create(['password' => bcrypt('correct-horse-battery')]);

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'correct-horse-battery',
    ]);

    expect($user->loginActivities()->where('successful', true)->exists())->toBeTrue();
});

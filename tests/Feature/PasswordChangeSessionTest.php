<?php

declare(strict_types=1);

use App\Domain\Identity\Models\User;

it('updates the password through the password update endpoint', function (): void {
    $user = User::factory()->create(['password' => bcrypt('old-password-1234')]);

    $this->actingAs($user)->put('/user/password', [
        'current_password' => 'old-password-1234',
        'password' => 'new-password-567890',
        'password_confirmation' => 'new-password-567890',
    ]);

    expect(Hash::check('new-password-567890', $user->fresh()->password))->toBeTrue();
});

it('rejects a password update when current password is wrong', function (): void {
    $user = User::factory()->create(['password' => bcrypt('old-password-1234')]);

    $response = $this->actingAs($user)->from('/profile')->put('/user/password', [
        'current_password' => 'WRONG',
        'password' => 'new-password-567890',
        'password_confirmation' => 'new-password-567890',
    ]);

    $response->assertSessionHasErrors('current_password', null, 'updatePassword');
    expect(Hash::check('old-password-1234', $user->fresh()->password))->toBeTrue();
});

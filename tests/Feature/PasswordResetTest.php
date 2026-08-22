<?php

declare(strict_types=1);

use App\Domain\Identity\Models\User;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;

it('sends a reset link to a known email', function (): void {
    Notification::fake();
    $user = User::factory()->create();

    $this->post('/forgot-password', ['email' => $user->email]);

    Notification::assertSentTo($user, ResetPasswordNotification::class);
});

it('resets the password with a valid token', function (): void {
    $user = User::factory()->create();
    $token = Password::createToken($user);

    $this->post('/reset-password', [
        'token' => $token,
        'email' => $user->email,
        'password' => 'brand-new-password-99',
        'password_confirmation' => 'brand-new-password-99',
    ]);

    expect(Hash::check('brand-new-password-99', $user->fresh()->password))->toBeTrue();
});

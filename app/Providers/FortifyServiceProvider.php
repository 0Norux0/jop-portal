<?php

declare(strict_types=1);

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Domain\Identity\Actions\RecordLoginActivity;
use App\Domain\Identity\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);

        // Custom login that rejects users whose status forbids authentication.
        Fortify::authenticateUsing(function (Request $request) {
            $recorder = app(RecordLoginActivity::class);

            /** @var User|null $user */
            $user = User::query()->where('email', $request->string('email'))->first();

            if ($user === null || ! Hash::check((string) $request->input('password'), $user->password)) {
                $recorder->record($user, false, $request->ip(), (string) $request->userAgent(), 'invalid_credentials');

                return null;
            }

            if (! $user->canAuthenticate()) {
                $recorder->record($user, false, $request->ip(), (string) $request->userAgent(), 'account_blocked');

                return null;
            }

            $user->forceFill(['last_login_at' => now()])->save();
            $recorder->record($user, true, $request->ip(), (string) $request->userAgent());

            return $user;
        });

        // Login throttling keyed on email + IP.
        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(
                Str::lower((string) $request->input('email')).'|'.$request->ip()
            );

            return Limit::perMinute((int) config('jobportal.security.login_max_attempts', 5))
                ->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by((string) $request->session()->get('login.id'));
        });

        // Views are served by Blade (resources/views/auth/*).
        Fortify::loginView(fn () => view('auth.login'));
        Fortify::registerView(fn () => view('auth.register'));
        Fortify::requestPasswordResetLinkView(fn () => view('auth.forgot-password'));
        Fortify::resetPasswordView(fn (Request $request) => view('auth.reset-password', ['request' => $request]));
        Fortify::verifyEmailView(fn () => view('auth.verify-email'));
        Fortify::confirmPasswordView(fn () => view('auth.confirm-password'));
        Fortify::twoFactorChallengeView(fn () => view('auth.two-factor-challenge'));
    }
}

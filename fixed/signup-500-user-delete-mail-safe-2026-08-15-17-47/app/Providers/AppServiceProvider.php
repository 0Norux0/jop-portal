<?php

declare(strict_types=1);

namespace App\Providers;

use App\Domain\Identity\Enums\Role as RoleEnum;
use App\Domain\Identity\Models\User;
use App\Notifications\WelcomeNotification;
use App\Policies\UserPolicy;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Throwable;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::policy(User::class, UserPolicy::class);

        // Super administrator implicitly passes every gate check.
        // Laravel's recommended Gate::before mechanism.
        Gate::before(function (User $user, string $ability): ?bool {
            return $user->hasRole(RoleEnum::SuperAdministrator->value) ? true : null;
        });

        Event::listen(Verified::class, function (Verified $event): void {
            if ($event->user instanceof User) {
                try {
                    $event->user->notify(new WelcomeNotification());
                } catch (Throwable $exception) {
                    report($exception);
                }
            }
        });
    }
}

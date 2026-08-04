<?php

declare(strict_types=1);

namespace App\Providers;

use App\Domain\Identity\Enums\Role as RoleEnum;
use App\Domain\Identity\Models\User;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

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
    }
}

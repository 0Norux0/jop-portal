<?php

declare(strict_types=1);

namespace App\Policies;

use App\Domain\Identity\Enums\Permission;
use App\Domain\Identity\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(Permission::ViewUsers->value);
    }

    public function view(User $user, User $target): bool
    {
        return $user->hasPermissionTo(Permission::ViewUsers->value);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo(Permission::CreateUsers->value);
    }

    public function update(User $user, User $target): bool
    {
        return $user->hasPermissionTo(Permission::EditUsers->value);
    }

    public function suspend(User $user, User $target): bool
    {
        return $user->id !== $target->id
            && $user->hasPermissionTo(Permission::SuspendUsers->value);
    }

    public function assignRoles(User $user): bool
    {
        return $user->hasPermissionTo(Permission::AssignRoles->value);
    }

    public function viewLoginActivity(User $user): bool
    {
        return $user->hasPermissionTo(Permission::ViewLoginActivity->value);
    }
}

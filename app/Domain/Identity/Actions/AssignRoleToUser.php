<?php

declare(strict_types=1);

namespace App\Domain\Identity\Actions;

use App\Domain\Identity\Enums\Role as RoleEnum;
use App\Domain\Identity\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;

class AssignRoleToUser
{
    /**
     * Assign a role to a target user on behalf of an actor.
     *
     * The super-administrator role can only be granted by an existing
     * super administrator. This prevents privilege escalation through the
     * ordinary administration interface.
     *
     * @throws AuthorizationException
     */
    public function assign(User $actor, User $target, RoleEnum $role): void
    {
        if ($role === RoleEnum::SuperAdministrator && ! $actor->isSuperAdministrator()) {
            throw new AuthorizationException(
                'Only a super administrator may grant super-administrator status.'
            );
        }

        DB::transaction(function () use ($target, $role): void {
            $target->assignRole($role->value);
        });
    }

    /**
     * Remove a role, with the same escalation guard for super administrator.
     *
     * @throws AuthorizationException
     */
    public function remove(User $actor, User $target, RoleEnum $role): void
    {
        if ($role === RoleEnum::SuperAdministrator && ! $actor->isSuperAdministrator()) {
            throw new AuthorizationException(
                'Only a super administrator may remove super-administrator status.'
            );
        }

        DB::transaction(function () use ($target, $role): void {
            $target->removeRole($role->value);
        });
    }
}

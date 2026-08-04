<?php

declare(strict_types=1);

use App\Domain\Identity\Actions\AssignRoleToUser;
use App\Domain\Identity\Enums\Role as RoleEnum;
use App\Domain\Identity\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Auth\Access\AuthorizationException;

beforeEach(fn () => $this->seed(RolePermissionSeeder::class));

it('prevents an ordinary administrator from granting super-administrator', function (): void {
    $admin = User::factory()->create();
    $admin->assignRole(RoleEnum::Administrator->value);
    $target = User::factory()->create();

    expect(fn () => app(AssignRoleToUser::class)
        ->assign($admin, $target, RoleEnum::SuperAdministrator))
        ->toThrow(AuthorizationException::class);

    expect($target->fresh()->hasRole(RoleEnum::SuperAdministrator->value))->toBeFalse();
});

it('allows a super administrator to grant super-administrator', function (): void {
    $super = User::factory()->create();
    $super->assignRole(RoleEnum::SuperAdministrator->value);
    $target = User::factory()->create();

    app(AssignRoleToUser::class)->assign($super, $target, RoleEnum::SuperAdministrator);

    expect($target->fresh()->hasRole(RoleEnum::SuperAdministrator->value))->toBeTrue();
});

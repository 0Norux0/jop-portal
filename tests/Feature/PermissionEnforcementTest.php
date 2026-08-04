<?php

declare(strict_types=1);

use App\Domain\Identity\Enums\Permission as Perm;
use App\Domain\Identity\Enums\Role as RoleEnum;
use App\Domain\Identity\Models\User;
use Database\Seeders\RolePermissionSeeder;

beforeEach(fn () => $this->seed(RolePermissionSeeder::class));

it('grants administrators the view-users permission', function (): void {
    $admin = User::factory()->create();
    $admin->assignRole(RoleEnum::Administrator->value);

    expect($admin->can(Perm::ViewUsers->value))->toBeTrue();
});

it('denies administrators super-admin-only permissions', function (): void {
    $admin = User::factory()->create();
    $admin->assignRole(RoleEnum::Administrator->value);

    expect($admin->can(Perm::ManageApiCredentials->value))->toBeFalse()
        ->and($admin->can(Perm::EditSensitiveSettings->value))->toBeFalse();
});

it('grants super administrators every ability via Gate::before', function (): void {
    $super = User::factory()->create();
    $super->assignRole(RoleEnum::SuperAdministrator->value);

    expect($super->can(Perm::ManageApiCredentials->value))->toBeTrue()
        ->and($super->can('any.undefined.ability'))->toBeTrue();
});

it('denies a job seeker access to the admin panel permission', function (): void {
    $seeker = User::factory()->create();
    $seeker->assignRole(RoleEnum::JobSeeker->value);

    expect($seeker->can(Perm::AccessAdminPanel->value))->toBeFalse();
});

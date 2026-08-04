<?php

declare(strict_types=1);

use App\Domain\Identity\Enums\Role as RoleEnum;
use App\Domain\Identity\Models\User;
use Database\Seeders\RolePermissionSeeder;

beforeEach(fn () => $this->seed(RolePermissionSeeder::class));

it('allows a single user to hold multiple roles', function (): void {
    $user = User::factory()->create();

    $user->assignRole(RoleEnum::JobSeeker->value);
    $user->assignRole(RoleEnum::Employer->value);

    expect($user->hasRole(RoleEnum::JobSeeker->value))->toBeTrue()
        ->and($user->hasRole(RoleEnum::Employer->value))->toBeTrue()
        ->and($user->roles()->count())->toBe(2);
});

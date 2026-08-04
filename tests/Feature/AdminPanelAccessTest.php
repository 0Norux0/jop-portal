<?php

declare(strict_types=1);

use App\Domain\Identity\Enums\Role as RoleEnum;
use App\Domain\Identity\Models\User;
use App\Filament\Resources\UserResource;
use Database\Seeders\RolePermissionSeeder;
use Filament\Facades\Filament;

beforeEach(function (): void {
    $this->seed(RolePermissionSeeder::class);
    Filament::setCurrentPanel(Filament::getPanel('admin'));
});

it('permits an administrator to access the admin panel', function (): void {
    $admin = User::factory()->create();
    $admin->assignRole(RoleEnum::Administrator->value);

    expect($admin->canAccessPanel(Filament::getPanel('admin')))->toBeTrue();
});

it('denies a job seeker access to the admin panel', function (): void {
    $seeker = User::factory()->create();
    $seeker->assignRole(RoleEnum::JobSeeker->value);

    expect($seeker->canAccessPanel(Filament::getPanel('admin')))->toBeFalse();
});

it('denies a suspended administrator access to the admin panel', function (): void {
    $admin = User::factory()->suspended()->create();
    $admin->assignRole(RoleEnum::Administrator->value);

    expect($admin->canAccessPanel(Filament::getPanel('admin')))->toBeFalse();
});

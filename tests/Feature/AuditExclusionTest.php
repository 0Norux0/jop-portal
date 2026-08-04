<?php

declare(strict_types=1);

use App\Domain\Identity\Enums\AccountStatus;
use App\Domain\Identity\Models\User;
use Spatie\Activitylog\Models\Activity;

it('writes an activity log entry on user status change', function (): void {
    $user = User::factory()->create();
    $user->status = AccountStatus::Suspended;
    $user->save();

    expect(Activity::query()->where('log_name', 'user')->exists())->toBeTrue();
});

it('never stores the password or remember token in activity log properties', function (): void {
    $user = User::factory()->create();
    $user->update(['name' => 'Changed Name']);

    $activity = Activity::query()->where('log_name', 'user')->latest('id')->first();
    $properties = $activity?->properties->toArray() ?? [];

    $flat = json_encode($properties);

    expect($flat)->not->toContain('password')
        ->and($flat)->not->toContain('remember_token')
        ->and($flat)->not->toContain('two_factor_secret');
});

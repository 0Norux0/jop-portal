<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Domain\Identity\Enums\AccountStatus;
use App\Domain\Identity\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UserStatsWidget extends StatsOverviewWidget
{
    /** @return array<int, Stat> */
    protected function getStats(): array
    {
        return [
            Stat::make('Total users', (string) User::query()->count()),
            Stat::make('Active', (string) User::query()
                ->where('status', AccountStatus::Active->value)->count()),
            Stat::make('Pending verification', (string) User::query()
                ->where('status', AccountStatus::PendingEmailVerification->value)->count()),
            Stat::make('Suspended', (string) User::query()
                ->where('status', AccountStatus::Suspended->value)->count()),
        ];
    }
}

<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Domain\Identity\Enums\AccountStatus;
use App\Domain\Identity\Models\User;
use App\Domain\Portal\Models\Candidate;
use App\Domain\Portal\Models\Employer;
use App\Domain\Portal\Models\Job;
use App\Domain\Portal\Models\JobApplication;
use App\Domain\Portal\Models\TrustReport;
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
            Stat::make('Employers', (string) Employer::query()->count())
                ->description(Employer::query()->where('verification_status', 'verified')->count().' verified'),
            Stat::make('Candidates', (string) Candidate::query()->count())
                ->description(Candidate::query()->where('availability_status', 'open_to_work')->count().' open to work'),
            Stat::make('Published jobs', (string) Job::query()->where('status', 'published')->count())
                ->description(Job::query()->where('status', 'draft')->count().' drafts'),
            Stat::make('Applications', (string) JobApplication::query()->count())
                ->description(JobApplication::query()->where('status', 'shortlisted')->count().' shortlisted'),
            Stat::make('Trust reports', (string) TrustReport::query()->where('status', 'open')->count())
                ->description('Open reports needing review'),
        ];
    }
}

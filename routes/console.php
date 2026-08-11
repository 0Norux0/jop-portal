<?php

declare(strict_types=1);

use App\Domain\Identity\Models\LoginActivity;
use App\Domain\Portal\Models\JobAlert;
use App\Notifications\JobAlertDigestNotification;
use App\Support\PortalJobPresenter;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

// Prune old login-activity rows according to the configured retention period.
Schedule::call(function (): void {
    $days = (int) config('jobportal.security.login_activity_retention_days', 90);
    LoginActivity::query()
        ->where('created_at', '<', now()->subDays($days))
        ->delete();
})->daily()->name('prune-login-activity')->withoutOverlapping();

Artisan::command('job-alerts:send', function (): int {
    $jobs = collect(PortalJobPresenter::publishedJobs());
    $sent = 0;

    JobAlert::query()
        ->with('user')
        ->where('is_active', true)
        ->chunkById(100, function ($alerts) use ($jobs, &$sent): void {
            foreach ($alerts as $alert) {
                $matches = $jobs
                    ->when($alert->keyword, fn ($items) => $items->filter(fn (array $job): bool => str_contains(strtolower($job['title'].' '.$job['company'].' '.$job['description']), strtolower((string) $alert->keyword))))
                    ->when($alert->country, fn ($items) => $items->filter(fn (array $job): bool => strtolower((string) $job['country']) === strtolower((string) $alert->country)))
                    ->when($alert->category, fn ($items) => $items->filter(fn (array $job): bool => strtolower((string) $job['category']) === strtolower((string) $alert->category)))
                    ->values();

                if ($matches->isEmpty() || $alert->user === null) {
                    continue;
                }

                $alert->user->notify(new JobAlertDigestNotification($alert, $matches));
                $alert->update(['last_sent_at' => now()]);
                $sent++;
            }
        });

    $this->info("Sent {$sent} job alert digests.");

    return 0;
})->purpose('Send matching job alert digests to job seekers.');

Schedule::command('job-alerts:send')->dailyAt('08:00')->name('send-job-alerts')->withoutOverlapping();

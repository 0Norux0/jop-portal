<?php

declare(strict_types=1);

use App\Domain\Identity\Models\LoginActivity;
use Illuminate\Support\Facades\Schedule;

// Prune old login-activity rows according to the configured retention period.
Schedule::call(function (): void {
    $days = (int) config('jobportal.security.login_activity_retention_days', 90);
    LoginActivity::query()
        ->where('created_at', '<', now()->subDays($days))
        ->delete();
})->daily()->name('prune-login-activity')->withoutOverlapping();

<?php

declare(strict_types=1);

namespace App\Domain\Identity\Actions;

use App\Domain\Identity\Models\LoginActivity;
use App\Domain\Identity\Models\User;

class RecordLoginActivity
{
    /**
     * Record a login attempt. Only security metadata is stored — never the
     * submitted password or any sensitive request body.
     *
     * @param  string|null  $failureReason  A coarse category, e.g. "invalid_credentials"
     *                                       or "account_blocked". Never free-form user input.
     */
    public function record(
        ?User $user,
        bool $successful,
        ?string $ipAddress,
        ?string $userAgent,
        ?string $failureReason = null,
    ): LoginActivity {
        return LoginActivity::create([
            'user_id' => $user?->id,
            'successful' => $successful,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent !== null ? mb_substr($userAgent, 0, 512) : null,
            'failure_reason' => $failureReason,
            'logged_in_at' => $successful ? now() : null,
        ]);
    }
}

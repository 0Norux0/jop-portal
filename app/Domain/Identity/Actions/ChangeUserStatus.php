<?php

declare(strict_types=1);

namespace App\Domain\Identity\Actions;

use App\Domain\Identity\Enums\AccountStatus;
use App\Domain\Identity\Models\User;
use App\Notifications\AccountReactivatedNotification;
use App\Notifications\AccountSuspendedNotification;
use Illuminate\Support\Facades\DB;

class ChangeUserStatus
{
    /**
     * Change a user's account status inside a transaction and fire the
     * appropriate notification. The status change is captured by the
     * model's activity log automatically.
     */
    public function change(User $user, AccountStatus $newStatus): User
    {
        return DB::transaction(function () use ($user, $newStatus): User {
            $previous = $user->status;

            $user->status = $newStatus;
            $user->save();

            if ($newStatus === AccountStatus::Suspended && $previous !== AccountStatus::Suspended) {
                $user->notify(new AccountSuspendedNotification());
            }

            if ($newStatus === AccountStatus::Active
                && in_array($previous, [AccountStatus::Suspended, AccountStatus::Deactivated], true)) {
                $user->notify(new AccountReactivatedNotification());
            }

            return $user;
        });
    }
}

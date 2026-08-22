<?php

declare(strict_types=1);

namespace App\Domain\Identity\Actions;

use App\Domain\Identity\Enums\AccountStatus;
use App\Domain\Identity\Models\User;
use App\Notifications\AccountReactivatedNotification;
use App\Notifications\AccountSuspendedNotification;
use Illuminate\Support\Facades\DB;
use Throwable;

class ChangeUserStatus
{
    /**
     * Change a user's account status inside a transaction and fire the
     * appropriate notification. The status change is captured by the
     * model's activity log automatically.
     */
    public function change(User $user, AccountStatus $newStatus): User
    {
        [$updatedUser, $notification] = DB::transaction(function () use ($user, $newStatus): array {
            $previous = $user->status;

            $user->status = $newStatus;
            $user->save();

            $notification = null;

            if ($newStatus === AccountStatus::Suspended && $previous !== AccountStatus::Suspended) {
                $notification = new AccountSuspendedNotification();
            }

            if ($newStatus === AccountStatus::Active
                && in_array($previous, [AccountStatus::Suspended, AccountStatus::Deactivated], true)) {
                $notification = new AccountReactivatedNotification();
            }

            return [$user, $notification];
        });

        if ($notification !== null) {
            try {
                $updatedUser->notify($notification);
            } catch (Throwable $exception) {
                report($exception);
            }
        }

        return $updatedUser;
    }
}

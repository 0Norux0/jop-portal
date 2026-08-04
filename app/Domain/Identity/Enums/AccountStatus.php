<?php

declare(strict_types=1);

namespace App\Domain\Identity\Enums;

enum AccountStatus: string
{
    case PendingEmailVerification = 'pending_email_verification';
    case Active = 'active';
    case Suspended = 'suspended';
    case Restricted = 'restricted';
    case Deactivated = 'deactivated';
    case ScheduledForDeletion = 'scheduled_for_deletion';

    /**
     * Whether a user in this status is permitted to authenticate.
     */
    public function canAuthenticate(): bool
    {
        return match ($this) {
            self::Active, self::Restricted, self::PendingEmailVerification => true,
            self::Suspended, self::Deactivated, self::ScheduledForDeletion => false,
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::PendingEmailVerification => 'Pending email verification',
            self::Active => 'Active',
            self::Suspended => 'Suspended',
            self::Restricted => 'Restricted',
            self::Deactivated => 'Deactivated',
            self::ScheduledForDeletion => 'Scheduled for deletion',
        };
    }

    /**
     * Human-readable reason returned to a blocked user at login.
     */
    public function blockedReason(): ?string
    {
        return match ($this) {
            self::Suspended => 'This account has been suspended. Please contact support.',
            self::Deactivated => 'This account has been deactivated.',
            self::ScheduledForDeletion => 'This account is scheduled for deletion.',
            default => null,
        };
    }
}

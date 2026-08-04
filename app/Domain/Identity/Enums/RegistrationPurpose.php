<?php

declare(strict_types=1);

namespace App\Domain\Identity\Enums;

enum RegistrationPurpose: string
{
    case FindJob = 'find_job';
    case Hire = 'hire';
    case RecruitmentAgency = 'recruitment_agency';
    case General = 'general';

    /**
     * The initial role assigned at registration for this purpose.
     * Returns null for a general account (no role granted up front).
     * This is an INITIAL assignment only — it never prevents the user
     * from receiving additional roles later.
     */
    public function initialRole(): ?Role
    {
        return match ($this) {
            self::FindJob => Role::JobSeeker,
            self::Hire => Role::Employer,
            self::RecruitmentAgency => Role::RecruitmentAgency,
            self::General => null,
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::FindJob => 'Find a job',
            self::Hire => 'Hire candidates',
            self::RecruitmentAgency => 'Recruitment agency',
            self::General => 'General account',
        };
    }
}

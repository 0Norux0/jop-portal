<?php

declare(strict_types=1);

namespace App\Domain\Identity\Enums;

enum Role: string
{
    case JobSeeker = 'job_seeker';
    case Employer = 'employer';
    case RecruitmentAgency = 'recruitment_agency';
    case PlacementOfficer = 'placement_officer';
    case Moderator = 'moderator';
    case Administrator = 'administrator';
    case SuperAdministrator = 'super_administrator';

    public function label(): string
    {
        return match ($this) {
            self::JobSeeker => 'Job seeker',
            self::Employer => 'Employer',
            self::RecruitmentAgency => 'Recruitment agency',
            self::PlacementOfficer => 'Placement officer',
            self::Moderator => 'Moderator',
            self::Administrator => 'Administrator',
            self::SuperAdministrator => 'Super administrator',
        };
    }

    /**
     * Roles that may access the administration panel.
     *
     * @return array<int, self>
     */
    public static function panelRoles(): array
    {
        return [self::Moderator, self::Administrator, self::SuperAdministrator];
    }

    /**
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_map(static fn (self $r): string => $r->value, self::cases());
    }
}

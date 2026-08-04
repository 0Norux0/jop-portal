<?php

declare(strict_types=1);

namespace App\Domain\Identity\Enums;

/**
 * Granular permissions. Authorisation checks should reference these rather
 * than role names wherever practical.
 */
enum Permission: string
{
    // Identity
    case ViewUsers = 'identity.view_users';
    case CreateUsers = 'identity.create_users';
    case EditUsers = 'identity.edit_users';
    case SuspendUsers = 'identity.suspend_users';
    case RestoreUsers = 'identity.restore_users';
    case AssignRoles = 'identity.assign_roles';
    case RemoveRoles = 'identity.remove_roles';
    case ViewLoginActivity = 'identity.view_login_activity';

    // Settings
    case ViewSystemSettings = 'settings.view';
    case EditNonSensitiveSettings = 'settings.edit_non_sensitive';
    case EditSensitiveSettings = 'settings.edit_sensitive';
    case ManageFeatureFlags = 'settings.manage_feature_flags';
    case ManageSecuritySettings = 'settings.manage_security';

    // Trust & moderation
    case ViewAuditLogs = 'trust.view_audit_logs';
    case ViewReports = 'trust.view_reports';
    case ModerateContent = 'trust.moderate_content';

    // Platform
    case AccessAdminPanel = 'platform.access_admin_panel';
    case AccessSuperAdmin = 'platform.access_super_admin';
    case EnterMaintenanceMode = 'platform.enter_maintenance_mode';
    case ViewSystemHealth = 'platform.view_system_health';
    case ManageApiCredentials = 'platform.manage_api_credentials';

    /**
     * Permissions that may only ever belong to the super administrator and
     * must never be assignable through the ordinary admin interface.
     *
     * @return array<int, self>
     */
    public static function superAdminOnly(): array
    {
        return [
            self::AccessSuperAdmin,
            self::EditSensitiveSettings,
            self::ManageSecuritySettings,
            self::EnterMaintenanceMode,
            self::ManageApiCredentials,
        ];
    }

    /**
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_map(static fn (self $p): string => $p->value, self::cases());
    }

    /**
     * @return array<int, string>
     */
    public static function superAdminOnlyValues(): array
    {
        return array_map(static fn (self $p): string => $p->value, self::superAdminOnly());
    }
}

<?php

declare(strict_types=1);

namespace App\Domain\Identity\Models;

use App\Domain\Identity\Enums\AccountStatus;
use App\Domain\Identity\Enums\Permission;
use App\Domain\Identity\Enums\RegistrationPurpose;
use App\Domain\Identity\Enums\RegistrationSource;
use App\Domain\Identity\Enums\Role as RoleEnum;
use App\Domain\Portal\Models\Employer;
use App\Domain\Shared\Concerns\HasPublicId;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser, MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory;
    use HasPublicId;
    use HasRoles;
    use LogsActivity;
    use Notifiable;
    use SoftDeletes;
    use TwoFactorAuthenticatable;

    protected $guarded = ['id'];

    /**
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'phone_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'terms_accepted_at' => 'datetime',
            'privacy_accepted_at' => 'datetime',
            'marketing_consent' => 'boolean',
            'preferred_work_countries' => 'array',
            'willing_to_relocate' => 'boolean',
            'available_for_remote_work' => 'boolean',
            'password' => 'hashed',
            'status' => AccountStatus::class,
            'registration_purpose' => RegistrationPurpose::class,
            'registration_source' => RegistrationSource::class,
        ];
    }

    // ----------------------------------------------------------------------
    // Relationships
    // ----------------------------------------------------------------------

    /**
     * @return HasMany<LoginActivity, $this>
     */
    public function loginActivities(): HasMany
    {
        return $this->hasMany(LoginActivity::class);
    }

    /**
     * @return HasMany<Employer, $this>
     */
    public function employers(): HasMany
    {
        return $this->hasMany(Employer::class);
    }

    // ----------------------------------------------------------------------
    // Status helpers
    // ----------------------------------------------------------------------

    public function canAuthenticate(): bool
    {
        return $this->status instanceof AccountStatus
            && $this->status->canAuthenticate();
    }

    public function isSuperAdministrator(): bool
    {
        return $this->hasRole(RoleEnum::SuperAdministrator->value);
    }

    // ----------------------------------------------------------------------
    // Filament panel access
    // ----------------------------------------------------------------------

    public function canAccessPanel(Panel $panel): bool
    {
        // Must be allowed to authenticate AND hold the admin-panel permission.
        return $this->canAuthenticate()
            && $this->hasPermissionTo(Permission::AccessAdminPanel->value);
    }

    // ----------------------------------------------------------------------
    // Audit logging (activitylog) — metadata only, never secrets
    // ----------------------------------------------------------------------

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email', 'status'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('user');
    }

    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }
}

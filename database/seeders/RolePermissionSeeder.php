<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\Identity\Enums\Permission as Perm;
use App\Domain\Identity\Enums\Role as RoleEnum;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        DB::transaction(function (): void {
            foreach (Perm::values() as $permission) {
                Permission::findOrCreate($permission, 'web');
            }

            foreach (RoleEnum::values() as $role) {
                Role::findOrCreate($role, 'web');
            }

            $this->assignPermissions();
        });

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    private function assignPermissions(): void
    {
        // Moderator: read + moderation only.
        $this->syncRole(RoleEnum::Moderator, [
            Perm::AccessAdminPanel,
            Perm::ViewUsers,
            Perm::ViewLoginActivity,
            Perm::ViewReports,
            Perm::ModerateContent,
            Perm::ViewAuditLogs,
        ]);

        // Administrator: everything EXCEPT super-admin-only permissions.
        $adminPermissions = array_filter(
            Perm::cases(),
            fn (Perm $p) => ! in_array($p, Perm::superAdminOnly(), true),
        );
        $this->syncRole(RoleEnum::Administrator, $adminPermissions);

        // Super administrator: holds the super-admin marker permission.
        // (Gate::before grants all abilities regardless, but we attach the
        //  explicit markers so the role is self-describing.)
        Role::findByName(RoleEnum::SuperAdministrator->value, 'web')
            ->syncPermissions(Perm::values());
    }

    /**
     * @param  array<int, Perm>  $permissions
     */
    private function syncRole(RoleEnum $role, array $permissions): void
    {
        Role::findByName($role->value, 'web')->syncPermissions(
            array_map(fn (Perm $p) => $p->value, $permissions)
        );
    }
}

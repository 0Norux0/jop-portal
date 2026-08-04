<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\Identity\Enums\AccountStatus;
use App\Domain\Identity\Enums\Role as RoleEnum;
use App\Domain\Identity\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('SUPER_ADMIN_EMAIL');

        if (blank($email)) {
            $this->command?->warn('SUPER_ADMIN_EMAIL not set — skipping super-admin creation.');

            return;
        }

        if (User::query()->where('email', $email)->exists()) {
            $this->command?->info("Super admin {$email} already exists — skipping.");

            return;
        }

        // Use the env password if provided; otherwise generate a one-time secure
        // password and print it ONCE. Never hard-code a password in source.
        $providedPassword = env('SUPER_ADMIN_PASSWORD');
        $generated = blank($providedPassword);
        $password = $generated ? Str::password(20) : $providedPassword;

        $user = User::create([
            'name' => env('SUPER_ADMIN_NAME', 'Platform Owner'),
            'email' => $email,
            'password' => Hash::make($password),
            'status' => AccountStatus::Active,
            'email_verified_at' => now(),
            'terms_accepted_at' => now(),
            'privacy_accepted_at' => now(),
            'registration_source' => 'admin_created',
        ]);

        $user->assignRole(RoleEnum::SuperAdministrator->value);

        if ($generated) {
            $this->command?->warn('============================================================');
            $this->command?->warn(' Generated one-time super-admin password (store it securely):');
            $this->command?->warn(" {$password}");
            $this->command?->warn('============================================================');
        } else {
            $this->command?->info("Super admin {$email} created with provided password.");
        }
    }
}

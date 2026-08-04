<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            ['group' => 'general', 'key' => 'platform_name', 'type' => 'string', 'value' => config('jobportal.brand_name'), 'is_sensitive' => false],
            ['group' => 'general', 'key' => 'default_locale', 'type' => 'string', 'value' => 'en', 'is_sensitive' => false],
            ['group' => 'registration', 'key' => 'email_verification_required', 'type' => 'boolean', 'value' => '1', 'is_sensitive' => false],
            ['group' => 'security', 'key' => 'admin_2fa_required', 'type' => 'boolean', 'value' => '1', 'is_sensitive' => false],
            ['group' => 'ai', 'key' => 'ai_enabled', 'type' => 'boolean', 'value' => '0', 'is_sensitive' => false],
            ['group' => 'ai', 'key' => 'log_sensitive_content', 'type' => 'boolean', 'value' => '0', 'is_sensitive' => false],
        ];

        foreach ($defaults as $row) {
            DB::table('settings')->updateOrInsert(
                ['key' => $row['key']],
                array_merge($row, ['created_at' => now(), 'updated_at' => now()]),
            );
        }
    }
}

<?php

declare(strict_types=1);

namespace App\Support;

use App\Domain\Shared\Models\Setting;
use Illuminate\Support\Facades\Schema;
use Throwable;

class MaintenanceMode
{
    public const KEY = 'maintenance_mode';

    /**
     * @return array{enabled: bool, message: string, allowed_paths: array<int, string>}
     */
    public static function settings(): array
    {
        $defaults = [
            'enabled' => false,
            'message' => 'The site is temporarily down for maintenance. Please check back shortly.',
            'allowed_paths' => ['admin', 'admin/*', 'livewire/*', 'filament/*', 'login', 'logout', 'maintenance'],
        ];

        try {
            if (! Schema::hasTable('settings')) {
                return $defaults;
            }

            $value = Setting::query()->where('key', self::KEY)->value('value');
        } catch (Throwable) {
            return $defaults;
        }

        if (! is_string($value) || trim($value) === '') {
            return $defaults;
        }

        $decoded = json_decode($value, true);

        if (! is_array($decoded)) {
            return $defaults;
        }

        return [
            'enabled' => (bool) ($decoded['enabled'] ?? false),
            'message' => trim((string) ($decoded['message'] ?? $defaults['message'])) ?: $defaults['message'],
            'allowed_paths' => array_values(array_filter(array_map('strval', $decoded['allowed_paths'] ?? $defaults['allowed_paths']))),
        ];
    }

    /**
     * @param  array{enabled?: bool, message?: string, allowed_paths?: array<int, string>}  $settings
     */
    public static function save(array $settings): void
    {
        Setting::query()->updateOrCreate(
            ['key' => self::KEY],
            [
                'group' => 'platform',
                'type' => 'json',
                'value' => json_encode([
                    'enabled' => (bool) ($settings['enabled'] ?? false),
                    'message' => trim((string) ($settings['message'] ?? '')),
                    'allowed_paths' => array_values($settings['allowed_paths'] ?? []),
                ], JSON_PRETTY_PRINT),
                'is_sensitive' => false,
            ],
        );
    }
}

<?php

declare(strict_types=1);

namespace App\Support;

use App\Domain\Shared\Models\Setting;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Schema;
use Throwable;

class EmailContent
{
    public const SETTING_KEY = 'email_content';

    /**
     * @var array<string, mixed> | null
     */
    private static ?array $loaded = null;

    /**
     * @return array<string, mixed>
     */
    public static function load(): array
    {
        if (static::$loaded !== null) {
            return static::$loaded;
        }

        $defaults = static::defaults();

        try {
            if (! Schema::hasTable('settings')) {
                return static::$loaded = $defaults;
            }

            $stored = Setting::query()
                ->where('key', static::SETTING_KEY)
                ->value('value');
        } catch (Throwable) {
            return static::$loaded = $defaults;
        }

        if (! is_string($stored) || trim($stored) === '') {
            return static::$loaded = $defaults;
        }

        $decoded = json_decode($stored, true);

        if (! is_array($decoded)) {
            return static::$loaded = $defaults;
        }

        return static::$loaded = static::sanitize(static::mergeWithDefaults($decoded));
    }

    /**
     * @param  array<string, mixed>  $content
     */
    public static function save(array $content): void
    {
        $content = static::sanitize(static::mergeWithDefaults($content));

        Setting::query()->updateOrCreate(
            ['key' => static::SETTING_KEY],
            [
                'group' => 'email',
                'type' => 'json',
                'value' => json_encode($content, JSON_PRETTY_PRINT),
                'is_sensitive' => false,
            ],
        );

        static::$loaded = $content;
    }

    /**
     * @return array<string, string>
     */
    public static function branding(): array
    {
        $site = SiteContent::load();
        $brand = static::brandName($site);
        $logoPath = (string) Arr::get($site, 'brand.logo_path');
        $logoUrl = $logoPath !== ''
            ? static::absoluteUrl(SiteContent::assetUrl($logoPath))
            : static::absoluteUrl(asset('images/nas-logo-cropped.webp'));

        return [
            'brand' => $brand,
            'logoUrl' => $logoUrl,
            'homeUrl' => url('/'),
            'primaryColor' => (string) Arr::get($site, 'brand.primary_color', '#2a7190'),
            'footerBackground' => '#eef4f6',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function defaults(): array
    {
        $brand = (string) config('jobportal.brand_name', config('app.name'));

        return [
            'reset_password' => [
                'subject' => "Reset your {$brand} password",
                'eyebrow' => 'Password reset',
                'heading' => "Reset your {$brand} password",
                'intro' => "We received a request to reset the password for your {$brand} account.",
                'button_label' => 'Reset password',
                'note' => 'This reset link will expire soon. If you did not request a password reset, you can safely ignore this email.',
                'footer' => "This email was sent by {$brand}. Please do not share password reset links with anyone.",
            ],
            'verify_email' => [
                'subject' => "Confirm your {$brand} email address",
                'eyebrow' => 'Confirm your account',
                'heading' => "Welcome to {$brand}, {name}!",
                'intro' => 'Please confirm your email address so we can protect your account and keep your job activity secure.',
                'button_label' => 'Confirm email address',
                'note' => 'If you did not create this account, you can ignore this email.',
                'footer' => "You are receiving this because an account was created using this email address on {$brand}.",
            ],
            'welcome' => [
                'subject' => "Welcome to {$brand}",
                'eyebrow' => 'Account ready',
                'heading' => "Your {$brand} account is ready, {name}!",
                'intro' => 'Thanks for confirming your email address.',
                'body' => "Manage your profile, save jobs, apply for openings, and receive account updates from {$brand}.",
                'button_label' => 'Open your dashboard',
                'footer' => "Welcome to {$brand}. We are glad to have you here.",
            ],
        ];
    }

    /**
     * @param  array<string, mixed>  $content
     * @return array<string, mixed>
     */
    public static function sanitize(array $content): array
    {
        $defaults = static::defaults();

        foreach ($defaults as $email => $fields) {
            foreach ($fields as $field => $fallback) {
                $content[$email][$field] = static::text(Arr::get($content, "{$email}.{$field}", $fallback));
            }
        }

        return $content;
    }

    /**
     * @param  array<string, mixed>  $content
     * @return array<string, mixed>
     */
    private static function mergeWithDefaults(array $content): array
    {
        return array_replace_recursive(static::defaults(), $content);
    }

    /**
     * @param  array<string, mixed>  $site
     */
    private static function brandName(array $site): string
    {
        return (string) Arr::get($site, 'brand.name', config('jobportal.brand_name', config('app.name')));
    }

    private static function absoluteUrl(string $url): string
    {
        if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) {
            return $url;
        }

        return url($url);
    }

    private static function text(mixed $value): string
    {
        return trim(strip_tags((string) $value));
    }
}

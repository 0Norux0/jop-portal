<?php

declare(strict_types=1);

namespace App\Support;

use App\Domain\Shared\Models\Setting;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Schema;
use Throwable;

class EmployerPortalContent
{
    public const SETTING_KEY = 'employer_portal_content';

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

        return static::$loaded = static::sanitize(array_replace_recursive($defaults, $decoded));
    }

    /**
     * @param  array<string, mixed>  $content
     */
    public static function save(array $content): void
    {
        $content = static::sanitize(array_replace_recursive(static::defaults(), $content));

        Setting::query()->updateOrCreate(
            ['key' => static::SETTING_KEY],
            [
                'group' => 'employer_portal',
                'type' => 'json',
                'value' => json_encode($content, JSON_PRETTY_PRINT),
                'is_sensitive' => false,
            ],
        );

        static::$loaded = $content;
    }

    /**
     * @return array<int, array{key: string, label: string, route: string}>
     */
    public static function navigationItems(): array
    {
        $navigation = static::load()['navigation'];

        return collect(static::routes())
            ->map(function (string $route, string $key) use ($navigation): ?array {
                $item = $navigation[$key] ?? [];

                if (! (bool) ($item['enabled'] ?? true)) {
                    return null;
                }

                return [
                    'key' => $key,
                    'label' => (string) ($item['label'] ?? str($key)->headline()),
                    'route' => $route,
                ];
            })
            ->filter()
            ->values()
            ->all();
    }

    /**
     * @param  array<string, mixed>  $content
     * @return array<string, mixed>
     */
    public static function sanitize(array $content): array
    {
        $defaults = static::defaults();

        $navigation = [];

        foreach ($defaults['navigation'] as $key => $default) {
            $navigation[$key] = [
                'label' => static::employerSafeText(static::string(Arr::get($content, "navigation.{$key}.label", $default['label']))),
                'enabled' => (bool) Arr::get($content, "navigation.{$key}.enabled", $default['enabled']),
            ];
        }

        return [
            'navigation' => $navigation,
            'billing' => [
                'eyebrow' => static::employerSafeText(static::string(Arr::get($content, 'billing.eyebrow', $defaults['billing']['eyebrow']))),
                'title' => static::employerSafeText(static::string(Arr::get($content, 'billing.title', $defaults['billing']['title']))),
                'owner_label' => static::employerSafeText(static::string(Arr::get($content, 'billing.owner_label', $defaults['billing']['owner_label']))),
                'email_label' => static::string(Arr::get($content, 'billing.email_label', $defaults['billing']['email_label'])),
                'plan_label' => static::string(Arr::get($content, 'billing.plan_label', $defaults['billing']['plan_label'])),
                'status_label' => static::string(Arr::get($content, 'billing.status_label', $defaults['billing']['status_label'])),
                'save_label' => static::string(Arr::get($content, 'billing.save_label', $defaults['billing']['save_label'])),
                'invoices_title' => static::string(Arr::get($content, 'billing.invoices_title', $defaults['billing']['invoices_title'])),
                'invoices_copy' => static::text(Arr::get($content, 'billing.invoices_copy', $defaults['billing']['invoices_copy'])),
                'payment_title' => static::string(Arr::get($content, 'billing.payment_title', $defaults['billing']['payment_title'])),
                'payment_copy' => static::text(Arr::get($content, 'billing.payment_copy', $defaults['billing']['payment_copy'])),
                'team_title' => static::employerSafeText(static::string(Arr::get($content, 'billing.team_title', $defaults['billing']['team_title']))),
                'team_copy' => static::employerSafeText(static::text(Arr::get($content, 'billing.team_copy', $defaults['billing']['team_copy']))),
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function defaults(): array
    {
        return [
            'navigation' => [
                'overview' => ['label' => 'Overview', 'enabled' => true],
                'company' => ['label' => 'Company Page', 'enabled' => true],
                'jobs' => ['label' => 'Jobs', 'enabled' => true],
                'applicants' => ['label' => 'Applicants', 'enabled' => true],
                'candidates' => ['label' => 'Find Candidates', 'enabled' => true],
                'billing' => ['label' => 'Billing & Account', 'enabled' => true],
                'promotion' => ['label' => 'Advertise', 'enabled' => true],
                'services' => ['label' => 'Paid Services', 'enabled' => true],
            ],
            'billing' => [
                'eyebrow' => 'Billing & Account',
                'title' => 'Billing and account details',
                'owner_label' => 'Company contact',
                'email_label' => 'Billing email',
                'plan_label' => 'Plan',
                'status_label' => 'Upgrade status',
                'save_label' => 'Save billing settings',
                'invoices_title' => 'Invoices',
                'invoices_copy' => 'Invoice history will appear here when payment processing is connected.',
                'payment_title' => 'Payment method',
                'payment_copy' => 'Payment method management is reserved for the billing integration.',
                'team_title' => 'Account access',
                'team_copy' => 'Employer account access changes are handled by platform staff.',
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    private static function routes(): array
    {
        return [
            'overview' => 'business.dashboard',
            'company' => 'business.company',
            'jobs' => 'business.jobs',
            'applicants' => 'business.applicants',
            'candidates' => 'business.candidates',
            'billing' => 'business.billing',
            'promotion' => 'business.promotion',
            'services' => 'business.services',
        ];
    }

    private static function string(mixed $value): string
    {
        return trim(strip_tags((string) $value));
    }

    private static function text(mixed $value): string
    {
        return trim(strip_tags((string) $value));
    }

    private static function employerSafeText(string $value): string
    {
        return str_replace(
            [
                'Admin Center',
                'admin center',
                'admin tools',
                'Admin tools',
                'admin privileges',
                'Admin privileges',
                'permissions',
            ],
            [
                'Billing & Account',
                'billing & account',
                'account tools',
                'Account tools',
                'account access',
                'Account access',
                'access settings',
            ],
            $value,
        );
    }
}

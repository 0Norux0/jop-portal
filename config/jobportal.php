<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Brand
    |--------------------------------------------------------------------------
    | The final brand name is selected later. It is read from configuration,
    | never hard-coded into source. Override via JOBPORTAL_BRAND_NAME.
    */
    'brand_name' => env('JOBPORTAL_BRAND_NAME', 'Job Portal'),

    'defaults' => [
        'country' => env('JOBPORTAL_DEFAULT_COUNTRY', 'KW'),
        'currency' => env('JOBPORTAL_DEFAULT_CURRENCY', 'KWD'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Security
    |--------------------------------------------------------------------------
    */
    'security' => [
        'login_max_attempts' => (int) env('LOGIN_MAX_ATTEMPTS', 5),
        'login_decay_seconds' => (int) env('LOGIN_DECAY_SECONDS', 60),
        'login_activity_retention_days' => (int) env('LOGIN_ACTIVITY_RETENTION_DAYS', 90),
        'admin_2fa_required' => (bool) env('ADMIN_2FA_REQUIRED', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Feature flags (Phase 1A defaults)
    |--------------------------------------------------------------------------
    | Everything not yet built is disabled. A disabled feature must not appear
    | in navigation and must not be reachable by direct URL.
    */
    'features' => [
        'candidate_registration' => true,
        'employer_registration' => true,
        'agency_registration' => true,
        'organisation_invitations' => false,
        'job_posting' => false,
        'candidate_search' => false,
        'video_profiles' => false,
        'portfolios' => false,
        'payments' => false,
        'messaging' => false,
        'ai_features' => false,
        'semantic_search' => false,
        'public_candidate_profiles' => false,
        'blog' => false,
        'multilingual_interface' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | AI preparation (all disabled in Phase 1A — no provider required)
    |--------------------------------------------------------------------------
    */
    'ai' => [
        'enabled' => (bool) env('AI_ENABLED', false),
        'embeddings_enabled' => (bool) env('AI_EMBEDDINGS_ENABLED', false),
        'generative_enabled' => (bool) env('AI_GENERATIVE_ENABLED', false),
        'active_provider' => env('AI_ACTIVE_PROVIDER', 'none'),
        'daily_budget' => (float) env('AI_DAILY_BUDGET', 0),
        'monthly_budget' => (float) env('AI_MONTHLY_BUDGET', 0),
        'log_retention_days' => (int) env('AI_LOG_RETENTION_DAYS', 30),
        'log_sensitive_content' => (bool) env('AI_LOG_SENSITIVE_CONTENT', false),
    ],

    'monitoring' => [
        'disk_threshold_percent' => (int) env('HEALTH_DISK_THRESHOLD_PERCENT', 90),
    ],
];

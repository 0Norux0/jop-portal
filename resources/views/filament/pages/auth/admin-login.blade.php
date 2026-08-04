@php
    $site = \App\Support\SiteContent::load();
    $primary = $site['brand']['primary_color'];
    $secondary = $site['brand']['secondary_color'];
@endphp

<div class="admin-login-shell min-h-screen text-slate-950" style="background: {{ $secondary }};">
    <style>
        .admin-login-shell {
            min-height: 100vh;
            font-family: var(--font-family), ui-sans-serif, system-ui, sans-serif;
        }

        .admin-login-shell .fi-input-wrp,
        .admin-login-shell input[type='email'],
        .admin-login-shell input[type='password'],
        .admin-login-shell input[type='text'] {
            width: 100%;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            background: #f8fafc;
            box-shadow: none;
        }

        .admin-login-shell input[type='email'],
        .admin-login-shell input[type='password'],
        .admin-login-shell input[type='text'] {
            min-height: 48px;
            padding: 12px 14px;
            font-size: 15px;
            color: #0f172a;
        }

        .admin-login-shell input:focus {
            border-color: {{ $primary }};
            outline: 3px solid color-mix(in srgb, {{ $primary }} 18%, transparent);
        }

        .admin-login-shell label,
        .admin-login-shell .fi-fo-field-wrp-label span {
            color: #0f172a;
            font-weight: 700;
        }

        .admin-login-shell .fi-fo-component-ctn,
        .admin-login-shell .fi-sc,
        .admin-login-shell form {
            display: grid;
            gap: 18px;
        }

        .admin-login-shell .fi-btn {
            width: 100%;
            min-height: 48px;
            justify-content: center;
            border-radius: 8px;
            background: {{ $primary }};
            color: #fff;
            font-weight: 800;
            box-shadow: none;
        }

        .admin-login-shell .fi-checkbox-input {
            border-radius: 4px;
            border-color: #cbd5e1;
        }

        .admin-login-shell .admin-login-card {
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            background: #fff;
            box-shadow: 0 18px 45px rgba(15, 23, 42, 0.10);
        }

        .admin-login-shell .admin-login-mobile-brand {
            display: flex;
        }

        @media (min-width: 1024px) {
            .admin-login-shell .admin-login-mobile-brand {
                display: none;
            }
        }
    </style>
    <main class="grid min-h-screen lg:grid-cols-[0.95fr_1.05fr]">
        <section class="relative hidden overflow-hidden lg:block" style="background: {{ $primary }};">
            <img src="{{ \App\Support\SiteContent::assetUrl($site['home']['hero_image_path'], 'images/global-career-hero.png') }}" alt="{{ $site['brand']['name'] }}" class="absolute inset-0 h-full w-full object-cover opacity-90 mix-blend-screen">
            <div class="absolute inset-0" style="background: color-mix(in srgb, {{ $primary }} 58%, transparent);"></div>
            <div class="relative z-10 flex min-h-screen flex-col justify-between px-12 py-10 text-white">
                <div class="inline-flex w-fit items-center gap-2">
                    <a href="/" class="flex items-center overflow-hidden rounded-[18px] border-2 border-white bg-white font-bold shadow-sm" style="color: {{ $primary }};">
                        @if (! empty($site['brand']['logo_path']))
                            <img src="{{ \App\Support\SiteContent::assetUrl($site['brand']['logo_path']) }}" alt="{{ $site['brand']['name'] }}" class="h-10 w-[150px] object-contain px-3 py-1">
                        @else
                            <span class="px-4 py-2">{{ $site['brand']['name'] }}</span>
                        @endif
                    </a>
                    <a href="/jobs" aria-label="Search jobs" class="flex h-11 w-11 items-center justify-center rounded-[16px] text-white shadow-sm" style="background: {{ $primary }};">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="m20 20-4.2-4.2m1.2-5.3a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0Z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </a>
                </div>

                <div class="max-w-xl pb-10">
                    <p class="text-sm font-semibold uppercase tracking-[0.24em]">{{ $site['brand']['powered_by'] }}</p>
                    <h1 class="mt-5 text-5xl font-extrabold leading-tight">Protected platform control center</h1>
                    <p class="mt-5 text-lg leading-8 text-white/85">Manage jobs, users, page content, verification, reports, SEO pages, and platform settings from one secure admin workspace.</p>
                    <div class="mt-8 grid gap-3 text-sm font-semibold sm:grid-cols-3">
                        <div class="rounded-lg bg-white/15 p-4 backdrop-blur">Role-based access</div>
                        <div class="rounded-lg bg-white/15 p-4 backdrop-blur">Audit activity</div>
                        <div class="rounded-lg bg-white/15 p-4 backdrop-blur">Content control</div>
                    </div>
                </div>
            </div>
        </section>

        <section class="flex min-h-screen flex-col px-4 py-6 sm:px-6 lg:px-10">
            <header class="mx-auto flex w-full max-w-xl items-center justify-between">
                <a href="/" class="font-bold" style="color: {{ $primary }};">{{ $site['brand']['name'] }}</a>
                <a href="/" class="text-sm font-semibold text-slate-600 hover:text-slate-950">Back to site</a>
            </header>

            <div class="mx-auto flex w-full max-w-xl flex-1 items-center py-8">
                <div class="admin-login-card w-full p-5 sm:p-7">
                    <div class="admin-login-mobile-brand mb-6 items-center gap-2">
                        <a href="/" class="flex items-center overflow-hidden rounded-[18px] border-2 bg-white font-bold shadow-sm" style="border-color: {{ $primary }}; color: {{ $primary }};">
                            @if (! empty($site['brand']['logo_path']))
                                <img src="{{ \App\Support\SiteContent::assetUrl($site['brand']['logo_path']) }}" alt="{{ $site['brand']['name'] }}" class="h-10 w-[150px] object-contain px-3 py-1">
                            @else
                                <span class="px-4 py-2">{{ $site['brand']['name'] }}</span>
                            @endif
                        </a>
                        <a href="/jobs" aria-label="Search jobs" class="flex h-11 w-11 items-center justify-center rounded-[16px] text-white shadow-sm" style="background: {{ $primary }};">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="m20 20-4.2-4.2m1.2-5.3a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0Z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                        </a>
                    </div>
                    <div class="mb-6">
                        <p class="text-sm font-semibold uppercase tracking-wide text-[#2a7190]">Admin access</p>
                        <h1 class="mt-2 text-3xl font-extrabold">Sign in to admin</h1>
                        <p class="mt-2 text-sm leading-6 text-slate-600">Authorized staff access only.</p>
                    </div>

                    {{ $this->content }}
                </div>
            </div>
        </section>
    </main>
</div>

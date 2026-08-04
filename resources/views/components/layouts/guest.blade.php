@php
    $site = \App\Support\SiteContent::load();
    $primary = $site['brand']['primary_color'];
    $secondary = $site['brand']['secondary_color'];
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $site['brand']['name'] }}</title>
    @if (! empty($site['brand']['favicon_path']))
        <link rel="icon" href="{{ \App\Support\SiteContent::assetUrl($site['brand']['favicon_path']) }}">
    @endif
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen text-slate-950 antialiased" style="background: {{ $secondary }};">
    <main class="grid min-h-screen lg:grid-cols-[0.95fr_1.05fr]">
        <section class="relative hidden overflow-hidden lg:block" style="background: {{ $primary }};">
            <img src="{{ \App\Support\SiteContent::assetUrl($site['home']['hero_image_path'], 'images/global-career-hero.png') }}" alt="{{ $site['brand']['name'] }}" class="absolute inset-0 h-full w-full object-cover opacity-90 mix-blend-screen">
            <div class="absolute inset-0" style="background: color-mix(in srgb, {{ $primary }} 55%, transparent);"></div>
            <div class="relative z-10 flex min-h-screen flex-col justify-between px-12 py-10 text-white">
                <a href="/" class="inline-flex w-fit items-center overflow-hidden rounded-[18px] border-2 border-white bg-white font-bold shadow-sm" style="color: {{ $primary }};">
                    @if (! empty($site['brand']['logo_path']))
                        <img src="{{ \App\Support\SiteContent::assetUrl($site['brand']['logo_path']) }}" alt="{{ $site['brand']['name'] }}" class="h-10 max-w-[170px] object-contain px-3">
                    @else
                        <span class="px-4 py-2">{{ $site['brand']['name'] }}</span>
                    @endif
                    <span class="flex h-10 w-10 items-center justify-center text-white" style="background: {{ $primary }};">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="m20 20-4.2-4.2m1.2-5.3a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0Z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </span>
                </a>
                <div class="max-w-xl pb-10">
                    <p class="text-sm font-semibold uppercase tracking-[0.24em]">{{ $site['brand']['powered_by'] }}</p>
                    <h1 class="mt-5 text-5xl font-extrabold leading-tight">{{ $site['brand']['tagline'] }}</h1>
                    <p class="mt-5 text-lg leading-8 text-white/85">{{ $site['brand']['description'] }}</p>
                    <div class="mt-8 grid gap-3 text-sm font-semibold sm:grid-cols-3">
                        <div class="rounded-lg bg-white/15 p-4 backdrop-blur">Global profiles</div>
                        <div class="rounded-lg bg-white/15 p-4 backdrop-blur">Verified hiring</div>
                        <div class="rounded-lg bg-white/15 p-4 backdrop-blur">Career tools</div>
                    </div>
                </div>
            </div>
        </section>

        <section class="flex min-h-screen flex-col px-4 py-6 sm:px-6 lg:px-10">
            <header class="mx-auto flex w-full max-w-3xl items-center justify-between">
                <a href="/" class="font-bold" style="color: {{ $primary }};">{{ $site['brand']['name'] }}</a>
                <a href="/" class="text-sm font-semibold text-slate-600 hover:text-slate-950">Back to site</a>
            </header>
            <div class="mx-auto flex w-full max-w-3xl flex-1 items-center py-8">
                <div class="w-full rounded-lg border border-slate-200 bg-white p-5 shadow-[0_18px_45px_rgba(15,23,42,0.10)] sm:p-7">
                    {{ $slot }}
                </div>
            </div>
        </section>
    </main>
</body>
</html>

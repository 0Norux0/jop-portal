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
    <title>{{ $title ?? $site['brand']['name'] }}</title>
    @if (! empty($site['brand']['favicon_path']))
        <link rel="icon" href="{{ \App\Support\SiteContent::assetUrl($site['brand']['favicon_path']) }}">
    @endif
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen text-slate-950 antialiased" style="background: {{ $secondary }};">
    <header class="sticky top-0 z-30 backdrop-blur" style="background: color-mix(in srgb, {{ $secondary }} 95%, transparent);">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-5 sm:px-6 lg:px-8">
            <div class="flex items-center gap-3">
                <a href="/" class="flex shrink-0 items-center overflow-hidden rounded-lg border bg-white font-bold shadow-sm" style="border-color: {{ $primary }}; color: {{ $primary }};">
                    @if (! empty($site['brand']['logo_path']))
                        <img src="{{ \App\Support\SiteContent::assetUrl($site['brand']['logo_path']) }}" alt="{{ $site['brand']['name'] }}" class="h-12 w-[170px] object-contain px-3 py-1">
                    @else
                        <span class="px-4 py-3">{{ $site['brand']['name'] }}</span>
                    @endif
                </a>
                <form action="/jobs" method="GET" class="hidden h-12 w-[280px] items-center overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm sm:flex">
                    <input name="q" value="{{ request('q') }}" class="h-full min-w-0 flex-1 border-0 bg-transparent px-4 text-sm text-slate-800 placeholder:text-slate-400 focus:ring-0" placeholder="Search jobs">
                    <button type="submit" class="flex h-full items-center gap-2 px-4 text-sm font-semibold text-white" style="background: {{ $primary }};">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="m20 20-4.2-4.2m1.2-5.3a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0Z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                        <span>Search</span>
                    </button>
                </form>
            </div>
            <nav class="hidden items-center gap-8 text-sm font-medium text-slate-800 lg:flex">
                @foreach ($site['navigation']['links'] as $link)
                    @if ($link['enabled'])
                        <a href="{{ $link['url'] }}" @class(['border-b-4 pb-1 font-bold' => request()->is(ltrim($link['url'], '/') ?: '/')]) @if (request()->is(ltrim($link['url'], '/') ?: '/')) style="border-color: {{ $primary }};" @endif>
                            {{ $link['label'] }}
                        </a>
                    @endif
                @endforeach
            </nav>
            <nav class="flex items-center gap-3 text-sm font-medium">
                @auth
                    <a href="{{ auth()->user()->hasAnyRole([\App\Domain\Identity\Enums\Role::Employer->value, \App\Domain\Identity\Enums\Role::RecruitmentAgency->value]) ? route('business.dashboard') : route('dashboard') }}" style="color: {{ $primary }};">Dashboard</a>
                    <a href="{{ route('logout.safe') }}" class="text-slate-500 underline">Sign out</a>
                @else
                    <a href="/login" class="hidden text-slate-700 sm:inline">{{ $site['navigation']['sign_in_label'] }}</a>
                    <a href="/register" class="flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-4 py-2 shadow-sm" style="color: {{ $primary }};">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M9 12h10m0 0-3.5-3.5M19 12l-3.5 3.5M13 5H5v14h8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        {{ $site['navigation']['register_label'] }}
                    </a>
                @endauth
            </nav>
        </div>
    </header>
    <main>
        {{ $slot }}
    </main>
    <footer class="border-t border-slate-200" style="background: {{ $secondary }};">
        <div class="mx-auto grid max-w-7xl gap-8 px-4 py-10 sm:px-6 md:grid-cols-4 lg:px-8">
            <div class="md:col-span-2">
                <p class="font-semibold" style="color: {{ $primary }};">{{ $site['brand']['name'] }}</p>
                <p class="mt-3 max-w-xl text-sm leading-6 text-slate-600">{{ $site['footer']['description'] }}</p>
                <p class="mt-3 text-xs font-medium text-slate-500">{{ $site['footer']['powered_by'] }}</p>
                @if ($site['footer']['copyright'])
                    <p class="mt-3 text-xs text-slate-500">{{ $site['footer']['copyright'] }}</p>
                @endif
            </div>
            <div>
                <p class="font-semibold">{{ $site['footer']['platform_heading'] }}</p>
                <div class="mt-3 grid gap-2 text-sm text-slate-600">
                    @foreach ($site['footer']['platform_links'] as $link)
                        @if ($link['enabled'])
                            <a href="{{ $link['url'] }}">{{ $link['label'] }}</a>
                        @endif
                    @endforeach
                </div>
            </div>
            <div>
                <p class="font-semibold">{{ $site['footer']['policies_heading'] }}</p>
                <div class="mt-3 grid gap-2 text-sm text-slate-600">
                    @foreach ($site['footer']['policy_links'] as $link)
                        @if ($link['enabled'])
                            <a href="{{ $link['url'] }}">{{ $link['label'] }}</a>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </footer>
</body>
</html>

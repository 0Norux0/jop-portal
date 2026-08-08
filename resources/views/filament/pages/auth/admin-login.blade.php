@php
    $site = \App\Support\SiteContent::load();
    $primary = $site['brand']['primary_color'];
    $secondary = $site['brand']['secondary_color'];
@endphp

<div class="admin-login-shell">
    <style>
        .admin-login-shell,
        .admin-login-shell * {
            box-sizing: border-box;
        }

        .admin-login-shell {
            min-height: 100vh;
            background: {{ $secondary }};
            color: #0f172a;
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        .admin-login-grid {
            min-height: 100vh;
            display: grid;
            grid-template-columns: minmax(420px, 0.95fr) minmax(420px, 1.05fr);
        }

        .admin-login-hero {
            position: sticky;
            top: 0;
            min-height: 100vh;
            overflow: hidden;
            background: {{ $primary }};
        }

        .admin-login-hero img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.32;
        }

        .admin-login-hero::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, color-mix(in srgb, {{ $primary }} 92%, #000 8%), color-mix(in srgb, {{ $primary }} 68%, #000 32%));
            opacity: 0.92;
        }

        .admin-login-hero-inner {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 44px 56px;
            color: #fff;
        }

        .admin-login-logo {
            display: inline-flex;
            width: fit-content;
            min-height: 52px;
            align-items: center;
            border: 2px solid rgba(255, 255, 255, 0.85);
            border-radius: 8px;
            background: #fff;
            color: {{ $primary }};
            text-decoration: none;
            font-weight: 900;
            overflow: hidden;
            box-shadow: 0 14px 34px rgba(15, 23, 42, 0.18);
        }

        .admin-login-logo img {
            position: static;
            width: 174px;
            height: 52px;
            object-fit: contain;
            padding: 8px 14px;
            opacity: 1;
        }

        .admin-login-logo span {
            padding: 12px 18px;
        }

        .admin-login-copy {
            max-width: 560px;
            padding-bottom: 24px;
        }

        .admin-login-copy p:first-child {
            margin: 0;
            font-size: 13px;
            font-weight: 800;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.82);
        }

        .admin-login-copy h1 {
            margin: 18px 0 0;
            font-size: clamp(42px, 5vw, 68px);
            line-height: 0.96;
            font-weight: 950;
            letter-spacing: 0;
        }

        .admin-login-copy p:last-child {
            margin: 22px 0 0;
            max-width: 520px;
            font-size: 18px;
            line-height: 1.65;
            color: rgba(255, 255, 255, 0.86);
        }

        .admin-login-badges {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
            margin-top: 30px;
        }

        .admin-login-badges span {
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.14);
            padding: 14px;
            font-size: 13px;
            font-weight: 800;
            backdrop-filter: blur(12px);
        }

        .admin-login-panel {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            padding: 28px 40px;
        }

        .admin-login-top {
            width: 100%;
            max-width: 560px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .admin-login-top a {
            color: #475569;
            text-decoration: none;
            font-size: 14px;
            font-weight: 800;
        }

        .admin-login-top a:first-child {
            color: {{ $primary }};
        }

        .admin-login-center {
            width: 100%;
            max-width: 560px;
            margin: auto;
            padding: 36px 0;
        }

        .admin-login-card {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            background: #fff;
            padding: 34px;
            box-shadow: 0 20px 50px rgba(15, 23, 42, 0.10);
        }

        .admin-login-card .eyebrow {
            margin: 0;
            color: {{ $primary }};
            font-size: 13px;
            font-weight: 900;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .admin-login-card h2 {
            margin: 10px 0 0;
            font-size: 38px;
            line-height: 1.05;
            font-weight: 950;
            letter-spacing: 0;
        }

        .admin-login-card .subcopy {
            margin: 12px 0 28px;
            color: #475569;
            font-size: 15px;
            line-height: 1.7;
        }

        .admin-login-shell form,
        .admin-login-shell .fi-fo-component-ctn,
        .admin-login-shell .fi-sc {
            display: grid;
            gap: 18px;
        }

        .admin-login-shell .fi-fo-field-wrp-label,
        .admin-login-shell label {
            display: block;
            margin-bottom: 7px;
            color: #0f172a;
            font-size: 14px;
            font-weight: 850;
        }

        .admin-login-shell .fi-input-wrp {
            width: 100%;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            background: #f8fafc;
            box-shadow: none;
            overflow: hidden;
        }

        .admin-login-shell .fi-input,
        .admin-login-shell input[type="email"],
        .admin-login-shell input[type="password"],
        .admin-login-shell input[type="text"] {
            width: 100%;
            min-height: 50px;
            border: 0;
            background: transparent;
            padding: 12px 14px;
            color: #0f172a;
            font-size: 15px;
            outline: none;
            box-shadow: none;
        }

        .admin-login-shell input:focus {
            outline: 3px solid color-mix(in srgb, {{ $primary }} 18%, transparent);
        }

        .admin-login-shell .fi-btn {
            width: 100%;
            min-height: 50px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 0;
            border-radius: 8px;
            background: {{ $primary }};
            color: #fff;
            font-size: 15px;
            font-weight: 900;
            box-shadow: none;
            cursor: pointer;
        }

        .admin-login-shell .fi-checkbox-input,
        .admin-login-shell input[type="checkbox"] {
            width: 16px;
            height: 16px;
            border-radius: 4px;
            accent-color: {{ $primary }};
        }

        .admin-login-shell .fi-link {
            color: {{ $primary }};
            font-weight: 800;
        }

        .admin-login-shell .fi-icon-btn,
        .admin-login-shell button[type="button"] {
            min-width: 42px;
        }

        @media (max-width: 980px) {
            .admin-login-grid {
                display: block;
            }

            .admin-login-hero {
                display: none;
            }

            .admin-login-panel {
                padding: 22px;
            }

            .admin-login-card {
                padding: 26px;
            }
        }
    </style>

    <main class="admin-login-grid">
        <section class="admin-login-hero">
            <img src="{{ \App\Support\SiteContent::assetUrl($site['home']['hero_image_path'], 'images/global-career-hero.webp') }}" alt="">
            <div class="admin-login-hero-inner">
                <a href="/" class="admin-login-logo">
                    @if (! empty($site['brand']['logo_path']))
                        <img src="{{ \App\Support\SiteContent::assetUrl($site['brand']['logo_path']) }}" alt="{{ $site['brand']['name'] }}">
                    @else
                        <span>{{ $site['brand']['name'] }}</span>
                    @endif
                </a>

                <div class="admin-login-copy">
                    <p>{{ $site['brand']['powered_by'] }}</p>
                    <h1>Protected platform control center</h1>
                    <p>Manage jobs, users, page content, verification, reports, SEO pages, and platform settings from one secure admin workspace.</p>
                    <div class="admin-login-badges">
                        <span>Role-based access</span>
                        <span>Audit activity</span>
                        <span>Content control</span>
                    </div>
                </div>
            </div>
        </section>

        <section class="admin-login-panel">
            <header class="admin-login-top">
                <a href="/">{{ $site['brand']['name'] }}</a>
                <a href="/">Back to site</a>
            </header>

            <div class="admin-login-center">
                <div class="admin-login-card">
                    <p class="eyebrow">Admin access</p>
                    <h2>Sign in to admin</h2>
                    <p class="subcopy">Authorized staff access only.</p>

                    {{ $this->content }}
                </div>
            </div>
        </section>
    </main>
</div>

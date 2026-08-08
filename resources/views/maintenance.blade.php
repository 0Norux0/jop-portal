@php($site = \App\Support\SiteContent::load())
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Maintenance - {{ $site['brand']['name'] }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 text-slate-950">
    <main class="flex min-h-screen items-center justify-center px-6 py-12">
        <section class="w-full max-w-xl rounded-lg border border-slate-200 bg-white p-8 text-center shadow-sm">
            <p class="text-sm font-bold uppercase tracking-wide text-[#2a7190]">Maintenance mode</p>
            <h1 class="mt-3 text-3xl font-extrabold">{{ $site['brand']['name'] }} is being updated</h1>
            <p class="mt-4 leading-7 text-slate-600">{{ $message }}</p>
        </section>
    </main>
</body>
</html>

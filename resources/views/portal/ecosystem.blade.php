@php($pageContent = \App\Support\PageContent::get('ecosystem'))
<x-layouts.app :title="$pageContent['title']">
    <section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <p class="font-semibold text-[#2a7190]">{{ $pageContent['eyebrow'] }}</p>
        <h1 class="mt-2 text-3xl font-bold">{{ $pageContent['title'] }}</h1>
        <p class="mt-3 max-w-3xl text-slate-600">{{ $pageContent['description'] }}</p>
        <div class="mt-6 flex flex-wrap gap-3">
            <a href="/institution-dashboards" class="rounded bg-[#2a7190] px-5 py-3 text-sm font-semibold text-white">Institution dashboards</a>
            <a href="/career-coach" class="rounded border border-[#2a7190] px-5 py-3 text-sm font-semibold text-[#2a7190]">Career coach</a>
        </div>
        <div class="mt-8 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
            @foreach (\App\Support\PortalCapabilities::publicModules() as $module)
                <div class="rounded-lg border border-emerald-200 bg-white p-4">
                    <p class="font-semibold">{{ $module['label'] }}</p>
                    <p class="mt-2 text-xs font-bold uppercase tracking-wide text-emerald-700">Live capability</p>
                </div>
            @endforeach
            @foreach (\App\Support\PortalCapabilities::futureModules() as $module)
                <div class="rounded-lg border border-slate-200 bg-white p-4">
                    <p class="font-semibold">{{ $module['label'] }}</p>
                    <p class="mt-2 text-xs font-bold uppercase tracking-wide text-slate-500">{{ $module['phase'] }}</p>
                </div>
            @endforeach
        </div>
        <div class="mt-12 grid gap-5 lg:grid-cols-5">
            @foreach ($portal['roadmap'] as $phase)
                <div class="rounded-lg border border-slate-200 bg-white p-5">
                    <p class="text-sm font-semibold text-emerald-700">{{ $phase['phase'] }}</p>
                    <h2 class="mt-2 font-bold">{{ $phase['title'] }}</h2>
                    <ul class="mt-4 grid gap-2 text-sm text-slate-700">
                        @foreach ($phase['items'] as $item)
                            <li>• {{ $item }}</li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    </section>
</x-layouts.app>

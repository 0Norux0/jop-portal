@php($pageContent = \App\Support\PageContent::get('packages'))
<x-layouts.app :title="$pageContent['title']">
    <section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <p class="font-semibold text-[#2a7190]">{{ $pageContent['eyebrow'] }}</p>
        <h1 class="mt-2 text-3xl font-bold">{{ $pageContent['title'] }}</h1>
        <p class="mt-3 max-w-3xl text-slate-600">{{ $pageContent['description'] }}</p>
        <div class="mt-8 grid gap-5 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($portal['packages'] as $package)
                <article class="rounded-lg border border-slate-200 bg-white p-5">
                    <p class="text-sm font-semibold text-emerald-700">{{ $package['price'] }}</p>
                    <h2 class="mt-2 text-xl font-bold">{{ $package['name'] }}</h2>
                    <ul class="mt-4 grid gap-2 text-sm text-slate-700">
                        @foreach ($package['features'] as $feature)
                            <li>• {{ $feature }}</li>
                        @endforeach
                    </ul>
                </article>
            @endforeach
        </div>
    </section>
</x-layouts.app>

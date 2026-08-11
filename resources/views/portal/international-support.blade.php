@php($pageContent = \App\Support\PageContent::get('international-support'))
<x-layouts.app :title="$pageContent['title']">
    <section class="bg-[#f7f7f7]">
        <div class="mx-auto max-w-7xl px-6 py-12 lg:px-8">
            <p class="font-semibold text-[#2a7190]">{{ $pageContent['eyebrow'] }}</p>
            <h1 class="mt-2 text-4xl font-extrabold text-[#2a7190]">{{ $pageContent['title'] }}</h1>
            <p class="mt-4 max-w-3xl leading-7 text-slate-700">{{ $pageContent['description'] }}</p>

            <div class="mt-8 grid gap-5 lg:grid-cols-3">
                <section class="rounded-lg bg-white p-6 shadow-sm">
                    <h2 class="text-xl font-bold">Location and authorization data</h2>
                    <div class="mt-4 grid gap-3 text-sm">
                        @foreach (['Country', 'City', 'State/province', 'Region', 'Time zone', 'Currency', 'Work authorization', 'Visa sponsorship', 'Relocation preference'] as $item)
                            <div class="rounded border border-slate-200 bg-slate-50 px-4 py-3 font-semibold">{{ $item }}</div>
                        @endforeach
                    </div>
                </section>
                <section class="rounded-lg bg-white p-6 shadow-sm lg:col-span-2">
                    <h2 class="text-xl font-bold">Supported currencies</h2>
                    <div class="mt-4 flex flex-wrap gap-2">
                        @foreach ($portal['currencies'] as $currency)
                            <span class="rounded-full bg-[#e9f3f7] px-3 py-1 text-sm font-bold text-[#2a7190]">{{ $currency }}</span>
                        @endforeach
                    </div>
                    <h3 class="mt-8 font-bold">Salary display types</h3>
                    <div class="mt-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                        @foreach ($portal['salary_types'] as $type)
                            <div class="rounded border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold">{{ $type }}</div>
                        @endforeach
                    </div>
                </section>
            </div>

            <div class="mt-8 grid gap-5 lg:grid-cols-2">
                <section class="rounded-lg bg-white p-6 shadow-sm">
                    <h2 class="text-xl font-bold">Multi-country setup</h2>
                    <div class="mt-4 flex flex-wrap gap-2">
                        @foreach ($portal['countries'] as $country)
                            <span class="rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-sm">{{ $country }}</span>
                        @endforeach
                    </div>
                </section>
                <section class="rounded-lg bg-white p-6 shadow-sm">
                    <h2 class="text-xl font-bold">Language support</h2>
                    <div class="mt-4 grid gap-3 sm:grid-cols-2">
                        @foreach ($portal['languages'] as $language)
                            <div class="rounded border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold">
                                {{ $language }}{{ $language === 'English' ? ' · active' : '' }}
                            </div>
                        @endforeach
                    </div>
                    <p class="mt-4 text-sm leading-6 text-slate-600">Important pages use centralized content patterns for consistent wording.</p>
                </section>
            </div>
        </div>
    </section>
</x-layouts.app>

@php($pageContent = \App\Support\PageContent::get('institution-dashboards'))
<x-layouts.app :title="$pageContent['title']">
    <section class="bg-[#f7f7f7]">
        <div class="mx-auto max-w-7xl px-6 py-12 lg:px-8">
            <p class="font-semibold text-[#2a7190]">{{ $pageContent['eyebrow'] }}</p>
            <h1 class="mt-2 text-4xl font-extrabold text-[#2a7190]">{{ $pageContent['title'] }}</h1>
            <p class="mt-4 max-w-3xl leading-7 text-slate-700">{{ $pageContent['description'] }}</p>

            <div class="mt-8 grid gap-5 md:grid-cols-2">
                @foreach ($dashboards as $dashboard)
                    <a href="{{ route('institution-dashboard.show', $dashboard['slug']) }}" class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm transition hover:border-[#2a7190]">
                        <div class="flex flex-wrap items-start justify-between gap-3">
                            <div>
                                <p class="text-sm font-semibold text-[#2a7190]">{{ $dashboard['location'] }}</p>
                                <h2 class="mt-1 text-2xl font-bold">{{ $dashboard['name'] }}</h2>
                            </div>
                            <span class="rounded-full bg-[#e9f3f7] px-3 py-1 text-sm font-bold text-[#2a7190]">{{ $dashboard['placement_percentage'] }} placed</span>
                        </div>
                        <div class="mt-5 grid gap-3 sm:grid-cols-3">
                            <div class="rounded bg-slate-50 p-4"><p class="text-xs text-slate-500">Employment</p><p class="mt-1 font-bold">{{ $dashboard['graduate_employment_rate'] }}</p></div>
                            <div class="rounded bg-slate-50 p-4"><p class="text-xs text-slate-500">Average salary</p><p class="mt-1 font-bold">{{ $dashboard['average_salary'] }}</p></div>
                            <div class="rounded bg-slate-50 p-4"><p class="text-xs text-slate-500">Partners</p><p class="mt-1 font-bold">{{ $dashboard['employer_partners'] }}</p></div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
</x-layouts.app>

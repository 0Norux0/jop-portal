<x-layouts.app :title="$page['title']">
    <section class="bg-white">
        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold">{{ $page['title'] }}</h1>
            <p class="mt-3 max-w-3xl text-slate-600">{{ $page['focus'] }}</p>
            <div class="mt-6 flex flex-wrap gap-3">
                <a href="/jobs" class="rounded bg-emerald-700 px-5 py-3 text-sm font-semibold text-white">Search jobs</a>
                <a href="/register" class="rounded border border-slate-300 px-5 py-3 text-sm font-semibold">Create free profile</a>
            </div>
        </div>
    </section>
    <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold">Relevant openings</h2>
        <div class="mt-6 grid gap-4 md:grid-cols-2">
            @foreach ($portal['jobs'] as $job)
                <a href="/jobs/{{ $job['slug'] }}" class="rounded-lg border border-slate-200 bg-white p-5 hover:border-emerald-700">
                    <h3 class="font-bold">{{ $job['title'] }}</h3>
                    <p class="mt-2 text-sm text-slate-600">{{ $job['company'] }} · {{ $job['city'] }}, {{ $job['country'] }}</p>
                    <p class="mt-2 text-sm font-medium">{{ $job['salary'] }}</p>
                </a>
            @endforeach
        </div>
    </section>
</x-layouts.app>

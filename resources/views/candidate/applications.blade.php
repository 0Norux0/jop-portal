<x-layouts.app title="Applications">
    <section class="bg-[#f7f7f7]">
        <div class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">
            <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
                <div>
                    <p class="font-semibold text-[#2a7190]">Job seeker dashboard</p>
                    <h1 class="mt-1 text-3xl font-extrabold">Applied jobs</h1>
                </div>
                <a href="/jobs" class="rounded bg-[#2a7190] px-4 py-2 text-sm font-bold text-white">Find more jobs</a>
            </div>

            @if (session('status'))
                <div class="mt-5 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">{{ session('status') }}</div>
            @endif

            <div class="mt-6 grid gap-4">
                @forelse ($applications as $application)
                    <article class="rounded-lg bg-white p-5 shadow-sm">
                        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
                            <div>
                                <h2 class="text-xl font-bold">{{ $application->job?->title }}</h2>
                                <p class="mt-1 text-sm text-slate-600">{{ $application->job?->employer?->name }} · Submitted {{ $application->created_at->diffForHumans() }}</p>
                                <span class="mt-3 inline-flex rounded-full bg-[#e9f3f7] px-3 py-1 text-xs font-bold text-[#2a7190]">{{ str($application->status)->headline() }}</span>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('jobs.show', $application->job?->slug) }}" class="rounded border border-[#2a7190] px-4 py-2 text-sm font-bold text-[#2a7190]">View job</a>
                                <a href="{{ route('candidate.messages') }}" class="rounded bg-[#2a7190] px-4 py-2 text-sm font-bold text-white">Messages</a>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="rounded-lg bg-white p-8 text-center shadow-sm">
                        <p class="font-bold">No applications yet</p>
                        <p class="mt-2 text-sm text-slate-600">Apply from a published job page and your applications will appear here.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
</x-layouts.app>

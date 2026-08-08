<x-layouts.app title="Saved Jobs">
    <section class="bg-[#f7f7f7]">
        <div class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">
            <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
                <div>
                    <p class="font-semibold text-[#2a7190]">Job seeker dashboard</p>
                    <h1 class="mt-1 text-3xl font-extrabold">Saved jobs</h1>
                </div>
                <a href="/jobs" class="rounded bg-[#2a7190] px-4 py-2 text-sm font-bold text-white">Browse jobs</a>
            </div>

            @if (session('status'))
                <div class="mt-5 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">{{ session('status') }}</div>
            @endif

            <div class="mt-6 grid gap-4">
                @forelse ($savedJobs as $savedJob)
                    <article class="rounded-lg bg-white p-5 shadow-sm">
                        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
                            <div>
                                <h2 class="text-xl font-bold">{{ $savedJob->job?->title }}</h2>
                                <p class="mt-1 text-sm text-slate-600">{{ $savedJob->job?->employer?->name }} · {{ $savedJob->job?->city }}, {{ $savedJob->job?->country }}</p>
                                <p class="mt-2 text-xs font-semibold text-slate-500">Saved {{ $savedJob->saved_at?->diffForHumans() ?? $savedJob->created_at->diffForHumans() }}</p>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('jobs.show', $savedJob->job?->slug) }}" class="rounded border border-[#2a7190] px-4 py-2 text-sm font-bold text-[#2a7190]">View job</a>
                                <form method="POST" action="{{ route('candidate.saved-jobs.destroy', $savedJob) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="rounded border border-red-200 bg-red-50 px-4 py-2 text-sm font-bold text-red-700">Remove</button>
                                </form>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="rounded-lg bg-white p-8 text-center shadow-sm">
                        <p class="font-bold">No saved jobs yet</p>
                        <p class="mt-2 text-sm text-slate-600">Save jobs from the job details page and they will appear here.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
</x-layouts.app>

<x-layouts.app title="Employer Jobs">
    <section class="bg-[#f7f7f7]">
        <div class="mx-auto grid max-w-7xl gap-6 px-4 py-10 sm:px-6 lg:grid-cols-[280px_1fr] lg:px-8">
            @include('employer.partials.nav')
            <div class="space-y-6">
                @if (session('status'))
                    <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">{{ session('status') }}</div>
                @endif

                <section class="rounded-lg bg-white p-6 shadow-sm">
                    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
                        <div>
                            <p class="font-semibold text-[#2a7190]">Employer jobs</p>
                            <h1 class="mt-1 text-3xl font-extrabold">Your job posts</h1>
                        </div>
                        <a href="#create-job" class="rounded bg-[#2a7190] px-5 py-3 text-sm font-bold text-white">Create a job</a>
                    </div>
                    <div class="mt-4 grid gap-4">
                        @forelse ($jobs as $job)
                            <details class="rounded border border-slate-200 p-4">
                                <summary class="cursor-pointer list-none">
                                    <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
                                        <div>
                                            <p class="font-bold">{{ $job->title }}</p>
                                            <p class="mt-1 text-sm text-slate-600">{{ $job->city }}, {{ $job->country }} · {{ str($job->status)->headline() }} · {{ $job->applications_count }} applicants</p>
                                        </div>
                                        <a href="{{ $job->status === 'published' ? route('jobs.show', $job->slug) : '#' }}" class="rounded border border-slate-300 px-4 py-2 text-sm font-bold">Public view</a>
                                    </div>
                                </summary>
                                <form method="POST" action="{{ route('business.jobs.update', $job) }}" class="mt-5 grid gap-5 border-t border-slate-100 pt-5 sm:grid-cols-2">
                                    @csrf
                                    @method('PUT')
                                    @include('employer.partials.job-form', ['job' => $job, 'categories' => $categories, 'portal' => $portal])
                                    <div class="sm:col-span-2 flex flex-wrap gap-3">
                                        <button name="action" value="draft" class="rounded border border-slate-300 px-5 py-3 font-bold">Save as draft</button>
                                        <button name="action" value="publish" class="rounded bg-[#2a7190] px-5 py-3 font-bold text-white">Publish/update</button>
                                    </div>
                                </form>
                            </details>
                        @empty
                            <p class="rounded border border-dashed border-slate-300 p-5 text-sm text-slate-600">No job posts yet.</p>
                        @endforelse
                    </div>
                </section>

                <section id="create-job" class="rounded-lg bg-white p-6 shadow-sm">
                    <p class="font-semibold text-[#2a7190]">Post a job for free</p>
                    <h2 class="mt-1 text-2xl font-extrabold">Create job post</h2>
                    <form method="POST" action="{{ route('business.jobs.store') }}" class="mt-6 grid gap-5 sm:grid-cols-2">
                        @csrf
                        @include('auth._errors')
                        @include('employer.partials.job-form', ['job' => null, 'categories' => $categories, 'portal' => $portal])
                        <div class="sm:col-span-2 flex flex-wrap gap-3">
                            <button name="action" value="draft" class="rounded border border-slate-300 px-5 py-3 font-bold">Save draft</button>
                            <button name="action" value="publish" class="rounded bg-[#2a7190] px-5 py-3 font-bold text-white">Publish job</button>
                        </div>
                    </form>
                </section>
            </div>
        </div>
    </section>
</x-layouts.app>

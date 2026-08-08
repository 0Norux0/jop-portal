<x-layouts.app title="Applicants">
    <section class="bg-[#f7f7f7]">
        <div class="mx-auto grid max-w-7xl gap-6 px-4 py-10 sm:px-6 lg:grid-cols-[280px_1fr] lg:px-8">
            @include('employer.partials.nav')
            <div class="space-y-5">
                @if (session('status'))
                    <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">{{ session('status') }}</div>
                @endif
                <section class="rounded-lg bg-white p-6 shadow-sm">
                    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
                        <div>
                            <p class="font-semibold text-[#2a7190]">Applicant management</p>
                            <h1 class="mt-1 text-3xl font-extrabold">Review applications</h1>
                        </div>
                        <form method="GET">
                            <select name="status" onchange="this.form.submit()" class="rounded border-slate-300 px-4 py-3 text-sm">
                                <option value="">All statuses</option>
                                @foreach (['submitted', 'reviewed', 'shortlisted', 'interview', 'rejected', 'hired'] as $status)
                                    <option value="{{ $status }}" @selected($selectedStatus === $status)>{{ str($status)->headline() }}</option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                </section>
                @forelse ($applications as $application)
                    <form method="POST" action="{{ route('business.applicants.update', $application) }}" class="rounded-lg bg-white p-5 shadow-sm">
                        @csrf
                        @method('PUT')
                        <div class="grid gap-5 lg:grid-cols-[1fr_260px]">
                            <div>
                                <h2 class="text-xl font-bold">{{ $application->candidate_name }}</h2>
                                <p class="mt-1 text-sm text-slate-600">{{ $application->candidate_email }} · {{ $application->job?->title }}</p>
                                <div class="mt-4 grid gap-2 text-sm sm:grid-cols-2">
                                    @if ($application->linkedin_url)
                                        <a href="{{ $application->linkedin_url }}" class="rounded border border-slate-200 px-3 py-2 font-semibold">LinkedIn profile</a>
                                    @endif
                                    <a href="{{ $application->cv_path ? asset($application->cv_path) : '#' }}" class="rounded border border-slate-200 px-3 py-2 font-semibold">CV / resume</a>
                                </div>
                                @if ($application->cover_letter)
                                    <p class="mt-4 text-sm leading-6 text-slate-700">{{ $application->cover_letter }}</p>
                                @endif
                                <textarea name="internal_notes" rows="3" class="mt-4 w-full rounded border-slate-300 px-4 py-3 text-sm" placeholder="Internal notes">{{ old('internal_notes', $application->internal_notes) }}</textarea>
                            </div>
                            <div>
                                <label class="text-sm font-bold">Status</label>
                                <select name="status" class="mt-2 w-full rounded border-slate-300 px-4 py-3">
                                    @foreach (['submitted', 'reviewed', 'shortlisted', 'interview', 'rejected', 'hired'] as $status)
                                        <option value="{{ $status }}" @selected($application->status === $status)>{{ str($status)->headline() }}</option>
                                    @endforeach
                                </select>
                                <label class="mt-4 flex items-center gap-2 rounded border border-slate-200 px-4 py-3 text-sm font-semibold">
                                    <input type="checkbox" name="request_interview" value="1">
                                    Request interview
                                </label>
                                <button class="mt-4 w-full rounded bg-[#2a7190] px-4 py-3 font-bold text-white">Update applicant</button>
                            </div>
                        </div>
                    </form>
                    @if ($application->user_id)
                        <form method="POST" action="{{ route('business.applicants.message', $application) }}" class="rounded-lg bg-white p-5 shadow-sm">
                            @csrf
                            <label class="text-sm font-bold">Message candidate</label>
                            <textarea name="body" rows="3" class="mt-2 w-full rounded border-slate-300 px-4 py-3 text-sm" placeholder="Send an interview request, question, or update"></textarea>
                            <button class="mt-3 rounded bg-[#2a7190] px-4 py-2 text-sm font-bold text-white">Send message</button>
                        </form>
                    @endif
                @empty
                    <p class="rounded-lg bg-white p-6 text-sm text-slate-600 shadow-sm">No applicants found for this employer account.</p>
                @endforelse
                {{ $applications->links() }}
            </div>
        </div>
    </section>
</x-layouts.app>

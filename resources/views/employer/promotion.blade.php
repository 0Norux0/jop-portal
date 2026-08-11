<x-layouts.app title="Advertise">
    <section class="bg-[#f7f7f7]">
        <div class="mx-auto grid max-w-7xl gap-6 px-4 py-10 sm:px-6 lg:grid-cols-[280px_1fr] lg:px-8">
            @include('employer.partials.nav')
            <div class="space-y-6">
                @if (session('status'))
                    <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">{{ session('status') }}</div>
                @endif
                <section class="rounded-lg bg-white p-6 shadow-sm">
                    <p class="font-semibold text-[#2a7190]">Advertise</p>
                    <h1 class="mt-1 text-3xl font-extrabold">Promote jobs and company pages</h1>
                    <p class="mt-3 max-w-3xl text-slate-600">Create an advertisement request for platform review. Featured-job requests use featured credits when available.</p>
                    <form method="POST" action="{{ route('business.promotion.store') }}" class="mt-6 grid gap-4 sm:grid-cols-2">
                        @csrf
                        @include('auth._errors')
                        <div>
                            <label class="text-sm font-bold">Campaign name</label>
                            <input name="campaign_name" required class="mt-2 w-full rounded border-slate-300 px-4 py-3" placeholder="Caregiver hiring boost">
                        </div>
                        <div>
                            <label class="text-sm font-bold">Related job</label>
                            <select name="job_id" class="mt-2 w-full rounded border-slate-300 px-4 py-3">
                                <option value="">Company page campaign</option>
                                @foreach ($employer->jobs as $job)
                                    <option value="{{ $job->id }}">{{ $job->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-sm font-bold">Goal</label>
                            <select name="goal" required class="mt-2 w-full rounded border-slate-300 px-4 py-3">
                                <option value="more_applicants">More applicants</option>
                                <option value="featured_job">Featured job</option>
                                <option value="company_awareness">Company awareness</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-sm font-bold">Budget</label>
                            <input name="budget" type="number" min="0" step="0.01" required class="mt-2 w-full rounded border-slate-300 px-4 py-3" placeholder="50">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="text-sm font-bold">Notes</label>
                            <textarea name="notes" rows="3" class="mt-2 w-full rounded border-slate-300 px-4 py-3" placeholder="Audience, target country, timing..."></textarea>
                        </div>
                        <button class="rounded bg-[#2a7190] px-5 py-3 font-bold text-white">Create advertisement request</button>
                    </form>
                </section>
                @php($requests = $employer->serviceRequests()->where('type', 'advertising')->latest()->get())
                @if ($requests->isNotEmpty())
                    <section class="rounded-lg bg-white p-6 shadow-sm">
                        <h2 class="text-xl font-bold">Advertisement requests</h2>
                        <div class="mt-4 grid gap-3">
                            @foreach ($requests as $request)
                                <div class="rounded border border-slate-200 p-4">
                                    <p class="font-bold">{{ $request->title }}</p>
                                    <p class="mt-1 text-sm text-slate-600">{{ str($request->payload['goal'] ?? 'requested')->replace('_', ' ')->headline() }} · {{ $request->budget ?? 0 }} · {{ str($request->status)->headline() }}</p>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif
                <div class="grid gap-4 lg:grid-cols-2">
                    @forelse ($employer->jobs as $job)
                        <article class="rounded-lg bg-white p-5 shadow-sm">
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <h2 class="font-bold">{{ $job->title }}</h2>
                                    <p class="mt-1 text-sm text-slate-600">{{ str($job->promotion_status)->headline() }}</p>
                                </div>
                                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold">{{ str($job->status)->headline() }}</span>
                            </div>
                            <a href="#top" class="mt-4 inline-flex rounded border border-slate-300 px-4 py-2 text-sm font-bold text-[#2a7190]">Create campaign above</a>
                        </article>
                    @empty
                        <p class="rounded-lg bg-white p-6 text-sm text-slate-600 shadow-sm">Post a job first, then promotion options will appear here.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>

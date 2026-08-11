<x-layouts.app title="Employer Paid Services">
    <section class="bg-[#f7f7f7]">
        <div class="mx-auto grid max-w-7xl gap-6 px-4 py-10 sm:px-6 lg:grid-cols-[280px_1fr] lg:px-8">
            @include('employer.partials.nav')
            <div class="space-y-6">
                @if (session('status'))
                    <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">{{ session('status') }}</div>
                @endif

                <section class="rounded-lg bg-white p-6 shadow-sm">
                    <p class="font-semibold text-[#2a7190]">Paid services</p>
                    <div class="mt-1 flex flex-col justify-between gap-4 lg:flex-row lg:items-end">
                        <div>
                            <h1 class="text-3xl font-extrabold">Employer growth tools</h1>
                            <p class="mt-2 max-w-3xl text-slate-600">Request packages, candidate access credits, premium matching, advertising support, AI recruitment services, and plan changes for platform review.</p>
                        </div>
                        <a href="{{ route('business.billing') }}" class="rounded border border-[#2a7190] px-4 py-2 text-sm font-bold text-[#2a7190]">View plan</a>
                    </div>
                </section>

                <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                    @foreach ([
                        'Job limit' => $employer->job_post_limit,
                        'Featured jobs' => $employer->featured_job_credits,
                        'CV credits' => $employer->cv_access_credits,
                        'Contact credits' => $employer->candidate_contact_credits,
                        'Matching credits' => $employer->matching_request_credits,
                        'AI credits' => $employer->ai_recruitment_credits,
                    ] as $label => $value)
                        <div class="rounded-lg bg-white p-5 shadow-sm">
                            <p class="text-3xl font-extrabold text-[#2a7190]">{{ $value }}</p>
                            <p class="mt-1 text-sm text-slate-600">{{ $label }}</p>
                        </div>
                    @endforeach
                </section>

                <section class="rounded-lg bg-white p-6 shadow-sm">
                    <h2 class="text-2xl font-extrabold">Request a paid service</h2>
                    <form method="POST" action="{{ route('business.services.store') }}" class="mt-6 grid gap-5 sm:grid-cols-2">
                        @csrf
                        @include('auth._errors')
                        <div>
                            <label class="text-sm font-bold">Service type</label>
                            <select name="type" required class="mt-2 w-full rounded border-slate-300 px-4 py-3">
                                <option value="subscription">Plan change request</option>
                                <option value="recruitment_package">Recruitment package</option>
                                <option value="credit_topup">Credit top-up</option>
                                <option value="premium_matching">Premium candidate matching</option>
                                <option value="ai_recruitment">AI recruitment tools</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-sm font-bold">Request title</label>
                            <input name="title" required class="mt-2 w-full rounded border-slate-300 px-4 py-3" placeholder="Healthcare hiring package">
                        </div>
                        <div>
                            <label class="text-sm font-bold">Budget</label>
                            <input name="budget" type="number" min="0" step="0.01" class="mt-2 w-full rounded border-slate-300 px-4 py-3" placeholder="Optional">
                        </div>
                        <div>
                            <label class="text-sm font-bold">Requested plan</label>
                            <select name="requested_plan" class="mt-2 w-full rounded border-slate-300 px-4 py-3">
                                <option value="">Not a plan change</option>
                                <option value="growth">Growth</option>
                                <option value="premium">Premium Employer Package</option>
                                <option value="enterprise">Enterprise</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-sm font-bold">Current plan</label>
                            <input value="{{ $plans[$employer->billing_plan]['label'] ?? str($employer->billing_plan)->headline() }}" disabled class="mt-2 w-full rounded border-slate-300 bg-slate-50 px-4 py-3">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="text-sm font-bold">Notes</label>
                            <textarea name="notes" rows="4" class="mt-2 w-full rounded border-slate-300 px-4 py-3" placeholder="Hiring volume, countries, timeline, roles, or candidate type needed"></textarea>
                        </div>
                        <div class="sm:col-span-2">
                            <button class="rounded bg-[#2a7190] px-5 py-3 font-bold text-white">Create request</button>
                        </div>
                    </form>
                </section>

                <section class="rounded-lg bg-white p-6 shadow-sm">
                    <h2 class="text-xl font-bold">Service requests</h2>
                    <div class="mt-4 grid gap-3">
                        @forelse ($requests as $request)
                            <article class="rounded border border-slate-200 p-4">
                                <div class="flex flex-col justify-between gap-2 sm:flex-row sm:items-center">
                                    <div>
                                        <p class="font-bold">{{ $request->title }}</p>
                                        <p class="mt-1 text-sm text-slate-600">{{ str($request->type)->replace('_', ' ')->headline() }} · {{ $request->created_at->diffForHumans() }}</p>
                                    </div>
                                    <span class="rounded-full bg-[#e9f3f7] px-3 py-1 text-xs font-bold text-[#2a7190]">{{ str($request->status)->headline() }}</span>
                                </div>
                                @if ($request->notes)
                                    <p class="mt-3 text-sm leading-6 text-slate-600">{{ $request->notes }}</p>
                                @endif
                            </article>
                        @empty
                            <p class="rounded border border-dashed border-slate-300 p-5 text-sm text-slate-600">No paid-service requests yet.</p>
                        @endforelse
                    </div>
                    <div class="mt-4">{{ $requests->links() }}</div>
                </section>
            </div>
        </div>
    </section>
</x-layouts.app>

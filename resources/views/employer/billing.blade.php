<x-layouts.app title="Employer Admin Center">
    <section class="bg-[#f7f7f7]">
        <div class="mx-auto grid max-w-7xl gap-6 px-4 py-10 sm:px-6 lg:grid-cols-[280px_1fr] lg:px-8">
            @include('employer.partials.nav')
            <div class="space-y-6">
                @if (session('status'))
                    <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">{{ session('status') }}</div>
                @endif
                <section class="rounded-lg bg-white p-6 shadow-sm">
                    <p class="font-semibold text-[#2a7190]">Admin Center</p>
                    <h1 class="mt-1 text-3xl font-extrabold">Billing and account details</h1>
                    <form method="POST" action="{{ route('business.billing.update') }}" class="mt-6 grid gap-5 sm:grid-cols-2">
                        @csrf
                        <div>
                            <label class="text-sm font-bold">Account owner</label>
                            <input value="{{ $employer->contact_name }}" disabled class="mt-2 w-full rounded border-slate-300 bg-slate-50 px-4 py-3">
                        </div>
                        <div>
                            <label class="text-sm font-bold">Billing email</label>
                            <input name="billing_email" type="email" value="{{ old('billing_email', $employer->billing_email ?: $employer->contact_email) }}" required class="mt-2 w-full rounded border-slate-300 px-4 py-3">
                        </div>
                        <div>
                            <label class="text-sm font-bold">Plan</label>
                            <select name="billing_plan" class="mt-2 w-full rounded border-slate-300 px-4 py-3">
                                @foreach (['free' => 'Free Employer Account', 'growth' => 'Growth', 'premium' => 'Premium Employer Package', 'enterprise' => 'Enterprise'] as $value => $label)
                                    <option value="{{ $value }}" @selected($employer->billing_plan === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-sm font-bold">Premium status</label>
                            <input value="{{ str($employer->premium_status)->headline() }}" disabled class="mt-2 w-full rounded border-slate-300 bg-slate-50 px-4 py-3">
                        </div>
                        <div class="sm:col-span-2">
                            <button class="rounded bg-[#2a7190] px-5 py-3 font-bold text-white">Save billing settings</button>
                        </div>
                    </form>
                </section>
                <div class="grid gap-4 md:grid-cols-3">
                    @foreach ([
                        'Invoices' => 'Invoice history will appear here when payment processing is connected.',
                        'Payment method' => 'Payment method management is reserved for the billing integration.',
                        'Team members' => 'Employer staff roles and permissions can be enabled in a later account-team module.',
                    ] as $title => $copy)
                        <section class="rounded-lg bg-white p-5 shadow-sm">
                            <h2 class="font-bold">{{ $title }}</h2>
                            <p class="mt-2 text-sm leading-6 text-slate-600">{{ $copy }}</p>
                        </section>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>

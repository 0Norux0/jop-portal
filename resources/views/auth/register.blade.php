@php
    $site = \App\Support\SiteContent::load();
    $portal = \App\Support\PortalData::load();
    $selectedPurpose = old('purpose', 'find_job');
    $showEmployerFields = in_array($selectedPurpose, ['hire', 'recruitment_agency'], true);
@endphp
<x-layouts.guest>
    <div class="mb-6">
        <p class="text-sm font-semibold uppercase tracking-wide text-[#2a7190]">Get started</p>
        <h1 class="mt-2 text-3xl font-extrabold">What do you want to do?</h1>
        <p class="mt-2 text-sm leading-6 text-slate-600">Create the account first. Job seekers can add experience, education, certificates, and portfolio items after signing in.</p>
    </div>

    @include('auth._errors')

    <form method="POST" action="/register" class="space-y-5" data-register-form>
        @csrf

        <section class="rounded-lg border border-slate-200 bg-white p-3">
            <div class="grid gap-2 sm:grid-cols-3">
                @foreach ([
                    'find_job' => ['Find a job', 'Build my profile'],
                    'hire' => ['Post a job', 'Hire candidates'],
                    'recruitment_agency' => ['Recruit talent', 'Agency account'],
                ] as $value => [$label, $copy])
                    <label class="cursor-pointer rounded border border-slate-200 bg-slate-50 p-4 transition hover:border-[#2a7190] has-[:checked]:border-[#2a7190] has-[:checked]:bg-[#e9f3f7]" data-purpose-card>
                        <input type="radio" name="purpose" value="{{ $value }}" class="sr-only" @checked($selectedPurpose === $value)>
                        <span class="block text-base font-bold text-slate-950">{{ $label }}</span>
                        <span class="mt-1 block text-sm text-slate-600">{{ $copy }}</span>
                    </label>
                @endforeach
            </div>
        </section>

        <section class="rounded-lg border border-slate-200 bg-white p-4">
            <div class="flex items-center justify-between gap-3">
                <p class="font-bold">Account details</p>
                <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700">Step 1</span>
            </div>
            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-semibold" for="name">Full name</label>
                    <input id="name" name="name" type="text" required autofocus value="{{ old('name') }}" autocomplete="name" class="mt-2 w-full rounded border-slate-300 px-4 py-3" placeholder="Your full name">
                </div>
                <div>
                    <label class="block text-sm font-semibold" for="email">Email address</label>
                    <input id="email" name="email" type="email" required value="{{ old('email') }}" autocomplete="email" class="mt-2 w-full rounded border-slate-300 px-4 py-3" placeholder="you@example.com">
                </div>
                <div>
                    <label class="block text-sm font-semibold" for="country">Country</label>
                    <select id="country" name="country" required class="mt-2 w-full rounded border-slate-300 px-4 py-3">
                        <option value="">Select country</option>
                        @foreach ($portal['countries'] as $country)
                            <option value="{{ $country }}" @selected(old('country') === $country)>{{ $country }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold" for="phone">Mobile / WhatsApp <span class="font-normal text-slate-500">optional</span></label>
                    <input id="phone" name="phone" type="tel" value="{{ old('phone') }}" autocomplete="tel" class="mt-2 w-full rounded border-slate-300 px-4 py-3" placeholder="+965...">
                </div>
            </div>
        </section>

        <section id="employer-details" class="rounded-lg border border-slate-200 bg-slate-50 p-4 {{ $showEmployerFields ? '' : 'hidden' }}" data-employer-details>
            <div class="flex items-center justify-between gap-3">
                <p class="font-bold">Employer details</p>
                <span class="rounded-full bg-sky-50 px-3 py-1 text-xs font-bold text-sky-700">For hiring accounts</span>
            </div>
            <p class="mt-2 text-sm text-slate-600">Only needed when you choose Post a job or Recruit talent.</p>
            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-semibold" for="company_name">Company name</label>
                    <input id="company_name" name="company_name" type="text" value="{{ old('company_name') }}" class="mt-2 w-full rounded border-slate-300 px-4 py-3" placeholder="Company or agency name" data-employer-required @required($showEmployerFields)>
                </div>
                <div>
                    <label class="block text-sm font-semibold" for="company_industry">Industry</label>
                    <input id="company_industry" name="company_industry" type="text" value="{{ old('company_industry') }}" class="mt-2 w-full rounded border-slate-300 px-4 py-3" placeholder="Healthcare, IT, hospitality...">
                </div>
                <div>
                    <label class="block text-sm font-semibold" for="company_size">Company size</label>
                    <select id="company_size" name="company_size" class="mt-2 w-full rounded border-slate-300 px-4 py-3">
                        <option value="">Select size</option>
                        @foreach (['1-10 employees', '11-50 employees', '51-200 employees', '201-500 employees', '501-1000 employees', '1000+ employees'] as $size)
                            <option value="{{ $size }}" @selected(old('company_size') === $size)>{{ $size }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold" for="company_website_url">Company website</label>
                    <input id="company_website_url" name="company_website_url" type="url" value="{{ old('company_website_url') }}" class="mt-2 w-full rounded border-slate-300 px-4 py-3" placeholder="https://">
                </div>
            </div>
        </section>

        <section class="rounded-lg border border-slate-200 bg-white p-4">
            <div class="flex items-center justify-between gap-3">
                <p class="font-bold">Security</p>
                <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700">Step 2</span>
            </div>
            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-semibold" for="password">Password</label>
                    <div class="mt-2 flex rounded border border-slate-300 bg-white focus-within:ring-4 focus-within:ring-emerald-100">
                        <input id="password" name="password" type="password" required autocomplete="new-password" class="min-w-0 flex-1 border-0 bg-transparent px-4 py-3 focus:ring-0" placeholder="At least 12 characters">
                        <button type="button" data-password-toggle="password" class="px-4 text-sm font-bold text-[#2a7190]">Show</button>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold" for="password_confirmation">Confirm password</label>
                    <div class="mt-2 flex rounded border border-slate-300 bg-white focus-within:ring-4 focus-within:ring-emerald-100">
                        <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" class="min-w-0 flex-1 border-0 bg-transparent px-4 py-3 focus:ring-0" placeholder="Repeat password">
                        <button type="button" data-password-toggle="password_confirmation" class="px-4 text-sm font-bold text-[#2a7190]">Show</button>
                    </div>
                </div>
            </div>
        </section>

        <section class="rounded-lg border border-dashed border-slate-300 bg-slate-50 p-4 text-sm leading-6 text-slate-600">
            <p class="font-bold text-slate-900">After signup</p>
            <p class="mt-1">Job seekers will finish profile details from the dashboard: experience, education, certificates, projects, portfolio, work preferences, and video CV.</p>
        </section>

        <div class="space-y-3">
            <label class="flex items-start gap-2 text-sm">
                <input type="checkbox" name="terms" value="on" required class="mt-1 rounded border-slate-300">
                <span>I accept the Terms and Privacy Policy.</span>
            </label>
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="marketing_consent" value="1" class="rounded border-slate-300"> Send me product updates
            </label>
        </div>

        <button type="submit" class="w-full rounded px-4 py-3 font-semibold text-white" style="background: {{ $site['brand']['primary_color'] }};">Continue</button>
        <p class="text-center text-sm text-slate-600">
            Already have an account?
            <a class="font-semibold text-[#2a7190]" href="/login">Sign in</a>
        </p>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.querySelector('[data-register-form]');
            const employerDetails = document.querySelector('[data-employer-details]');
            const employerRequiredFields = document.querySelectorAll('[data-employer-required]');

            if (!form || !employerDetails) {
                return;
            }

            const syncPurpose = () => {
                const purpose = form.querySelector('input[name="purpose"]:checked')?.value;
                const needsEmployerDetails = ['hire', 'recruitment_agency'].includes(purpose);

                employerDetails.classList.toggle('hidden', !needsEmployerDetails);
                employerRequiredFields.forEach((field) => {
                    field.required = needsEmployerDetails;
                });
            };

            form.querySelectorAll('input[name="purpose"]').forEach((input) => {
                input.addEventListener('change', syncPurpose);
            });

            syncPurpose();
        });
    </script>
</x-layouts.guest>

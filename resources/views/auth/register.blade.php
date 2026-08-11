@php
    $site = \App\Support\SiteContent::load();
    $portal = \App\Support\PortalData::load();
    $nationalities = \App\Support\CountryRepository::nationalities();
@endphp
<x-layouts.guest>
    <div class="mb-6">
        <p class="text-sm font-semibold uppercase tracking-wide text-[#2a7190]">Create your global profile</p>
        <h1 class="mt-2 text-3xl font-extrabold">Join {{ $site['brand']['name'] }}</h1>
        <p class="mt-2 text-sm leading-6 text-slate-600">Create a candidate, employer, or recruitment agency account and start with the right dashboard.</p>
    </div>

    @include('auth._errors')

    <form method="POST" action="/register" class="space-y-6">
        @csrf

        <section class="rounded-lg border border-slate-200 bg-slate-50 p-4">
            <p class="font-bold">Account details</p>
            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-semibold" for="name">Full name</label>
                    <input id="name" name="name" type="text" required autofocus value="{{ old('name') }}" autocomplete="name" class="mt-2 w-full rounded border-slate-300 px-4 py-3">
                </div>
                <div>
                    <label class="block text-sm font-semibold" for="email">Email address</label>
                    <input id="email" name="email" type="email" required value="{{ old('email') }}" autocomplete="email" class="mt-2 w-full rounded border-slate-300 px-4 py-3">
                </div>
                <div>
                    <label class="block text-sm font-semibold" for="phone">Mobile / WhatsApp</label>
                    <input id="phone" name="phone" type="tel" value="{{ old('phone') }}" autocomplete="tel" class="mt-2 w-full rounded border-slate-300 px-4 py-3" placeholder="+965...">
                </div>
                <div>
                    <label class="block text-sm font-semibold" for="purpose">Account type</label>
                    <select id="purpose" name="purpose" required class="mt-2 w-full rounded border-slate-300 px-4 py-3">
                        <option value="find_job" @selected(old('purpose', 'find_job') === 'find_job')>Find a job</option>
                        <option value="hire" @selected(old('purpose') === 'hire')>Hire candidates</option>
                        <option value="recruitment_agency" @selected(old('purpose') === 'recruitment_agency')>Recruitment agency</option>
                        <option value="general" @selected(old('purpose') === 'general')>General account</option>
                    </select>
                </div>
            </div>
        </section>

        <section class="rounded-lg border border-slate-200 bg-white p-4">
            <p class="font-bold">Profile basics</p>
            <div class="mt-4 grid gap-4 sm:grid-cols-2">
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
                    <label class="block text-sm font-semibold" for="city">City</label>
                    <input id="city" name="city" type="text" required value="{{ old('city') }}" class="mt-2 w-full rounded border-slate-300 px-4 py-3">
                </div>
                <div>
                    <label class="block text-sm font-semibold" for="nationality">Nationality</label>
                    <select id="nationality" name="nationality" required class="mt-2 w-full rounded border-slate-300 px-4 py-3">
                        <option value="">Select nationality</option>
                        @foreach ($nationalities as $nationality)
                            <option value="{{ $nationality }}" @selected(old('nationality') === $nationality)>{{ $nationality }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold" for="current_job_title">Current status / job title</label>
                    <select id="current_job_title" name="current_job_title" required class="mt-2 w-full rounded border-slate-300 px-4 py-3">
                        <option value="">Select current status</option>
                        @foreach (['Working full-time', 'Working part-time', 'Unemployed', 'Student', 'Fresh graduate', 'Freelancer', 'Career changer', 'Business owner'] as $status)
                            <option value="{{ $status }}" @selected(old('current_job_title') === $status)>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-semibold" for="preferred_job_category">Preferred job category</label>
                    <select id="preferred_job_category" name="preferred_job_category" required class="mt-2 w-full rounded border-slate-300 px-4 py-3">
                        <option value="">Select category</option>
                        @foreach ($portal['categories'] as $category)
                            <option value="{{ $category }}" @selected(old('preferred_job_category') === $category)>{{ $category }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </section>

        <section class="rounded-lg border border-slate-200 bg-white p-4">
            <p class="font-bold">Company details</p>
            <p class="mt-1 text-sm text-slate-600">Required for employer and recruitment agency accounts.</p>
            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-semibold" for="company_name">Company name</label>
                    <input id="company_name" name="company_name" type="text" value="{{ old('company_name') }}" class="mt-2 w-full rounded border-slate-300 px-4 py-3" placeholder="Company or agency name">
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
            <p class="font-bold">Employer plan request</p>
            <p class="mt-1 text-sm text-slate-600">For employer and recruitment agency accounts, choose the plan you want. Paid plans require platform approval after payment.</p>
            <div class="mt-4 grid gap-3 lg:grid-cols-4">
                @foreach (\App\Support\EmployerEntitlements::plans() as $value => $plan)
                    <label @class([
                        'cursor-pointer rounded-lg border p-4',
                        'border-[#2a7190] bg-[#e9f3f7]' => old('requested_employer_plan', 'free') === $value,
                        'border-slate-200 bg-white' => old('requested_employer_plan', 'free') !== $value,
                    ])>
                        <input type="radio" name="requested_employer_plan" value="{{ $value }}" @checked(old('requested_employer_plan', 'free') === $value) class="sr-only">
                        <span class="block font-bold">{{ $plan['label'] }}</span>
                        <span class="mt-3 grid gap-1 text-xs text-slate-600">
                            <span>{{ $plan['job_posts'] }} job posts</span>
                            <span>{{ $plan['featured_jobs'] }} featured jobs</span>
                            <span>{{ $plan['cv_credits'] }} CV credits</span>
                            <span>{{ $plan['contact_credits'] }} contact credits</span>
                        </span>
                    </label>
                @endforeach
            </div>
        </section>

        <section class="rounded-lg border border-slate-200 bg-slate-50 p-4">
            <p class="font-bold">Work preferences</p>
            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-semibold" for="visa_work_permit_status">Visa / work permit status</label>
                    <select id="visa_work_permit_status" name="visa_work_permit_status" class="mt-2 w-full rounded border-slate-300 px-4 py-3">
                        <option value="">Select status</option>
                        @foreach (['Citizen', 'Resident', 'Has work permit', 'Needs sponsorship', 'Open to relocation', 'Not sure yet'] as $status)
                            <option value="{{ $status }}" @selected(old('visa_work_permit_status') === $status)>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <p class="block text-sm font-semibold">Preferred countries</p>
                    <div class="mt-2 grid max-h-52 gap-2 overflow-auto rounded border border-slate-200 bg-white p-3">
                        @foreach ($portal['countries'] as $country)
                            <label class="flex items-center gap-2 rounded bg-slate-50 px-3 py-2 text-sm">
                                <input type="checkbox" name="preferred_work_countries[]" value="{{ $country }}" @checked(in_array($country, old('preferred_work_countries', []), true))>
                                <span>{{ $country }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="mt-4 grid gap-3 text-sm sm:grid-cols-2">
                <label class="flex items-center gap-2 rounded border border-slate-200 bg-white px-4 py-3">
                    <input type="checkbox" name="willing_to_relocate" value="1" @checked(old('willing_to_relocate'))>
                    <span>Willing to relocate</span>
                </label>
                <label class="flex items-center gap-2 rounded border border-slate-200 bg-white px-4 py-3">
                    <input type="checkbox" name="available_for_remote_work" value="1" @checked(old('available_for_remote_work'))>
                    <span>Available for remote work</span>
                </label>
            </div>
        </section>

        <details class="rounded-lg border border-slate-200 bg-white p-4">
            <summary class="cursor-pointer font-bold">Optional professional links</summary>
            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                @foreach ([
                    'linkedin_url' => 'LinkedIn profile',
                    'portfolio_url' => 'Portfolio link',
                    'personal_website_url' => 'Personal website',
                    'github_url' => 'GitHub',
                    'behance_url' => 'Behance',
                    'youtube_url' => 'YouTube',
                    'tiktok_url' => 'TikTok professional profile',
                ] as $name => $label)
                    <div>
                        <label class="block text-sm font-semibold" for="{{ $name }}">{{ $label }}</label>
                        <input id="{{ $name }}" name="{{ $name }}" type="url" value="{{ old($name) }}" class="mt-2 w-full rounded border-slate-300 px-4 py-3" placeholder="https://">
                    </div>
                @endforeach
            </div>
        </details>

        <section class="rounded-lg border border-slate-200 bg-white p-4">
            <p class="font-bold">Security</p>
            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-semibold" for="password">Password</label>
                    <div class="mt-2 flex rounded border border-slate-300 bg-white focus-within:ring-4 focus-within:ring-emerald-100">
                        <input id="password" name="password" type="password" required autocomplete="new-password" class="min-w-0 flex-1 border-0 bg-transparent px-4 py-3 focus:ring-0">
                        <button type="button" data-password-toggle="password" class="px-4 text-sm font-bold text-[#2a7190]">Show</button>
                    </div>
                    <p class="mt-1 text-xs text-slate-500">At least 12 characters.</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold" for="password_confirmation">Confirm password</label>
                    <div class="mt-2 flex rounded border border-slate-300 bg-white focus-within:ring-4 focus-within:ring-emerald-100">
                        <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" class="min-w-0 flex-1 border-0 bg-transparent px-4 py-3 focus:ring-0">
                        <button type="button" data-password-toggle="password_confirmation" class="px-4 text-sm font-bold text-[#2a7190]">Show</button>
                    </div>
                </div>
            </div>
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

        <button type="submit" class="w-full rounded px-4 py-3 font-semibold text-white" style="background: {{ $site['brand']['primary_color'] }};">Create account</button>
        <p class="text-center text-sm text-slate-600">
            Already have an account?
            <a class="font-semibold text-[#2a7190]" href="/login">Sign in</a>
        </p>
    </form>
</x-layouts.guest>

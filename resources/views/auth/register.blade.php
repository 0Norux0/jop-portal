@php
    $site = \App\Support\SiteContent::load();
    $portal = \App\Support\PortalData::load();
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
                    <input id="nationality" name="nationality" type="text" required value="{{ old('nationality') }}" class="mt-2 w-full rounded border-slate-300 px-4 py-3">
                </div>
                <div>
                    <label class="block text-sm font-semibold" for="current_job_title">Current job title</label>
                    <input id="current_job_title" name="current_job_title" type="text" required value="{{ old('current_job_title') }}" class="mt-2 w-full rounded border-slate-300 px-4 py-3">
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

        <section class="rounded-lg border border-slate-200 bg-slate-50 p-4">
            <p class="font-bold">Work preferences</p>
            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-semibold" for="visa_work_permit_status">Visa / work permit status</label>
                    <input id="visa_work_permit_status" name="visa_work_permit_status" type="text" value="{{ old('visa_work_permit_status') }}" class="mt-2 w-full rounded border-slate-300 px-4 py-3" placeholder="Citizen, resident, needs sponsorship...">
                </div>
                <div>
                    <label class="block text-sm font-semibold" for="preferred_work_countries">Preferred countries</label>
                    <select id="preferred_work_countries" name="preferred_work_countries[]" multiple class="mt-2 min-h-32 w-full rounded border-slate-300 px-4 py-3">
                        @foreach ($portal['countries'] as $country)
                            <option value="{{ $country }}" @selected(in_array($country, old('preferred_work_countries', []), true))>{{ $country }}</option>
                        @endforeach
                    </select>
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
                    <input id="password" name="password" type="password" required autocomplete="new-password" class="mt-2 w-full rounded border-slate-300 px-4 py-3">
                    <p class="mt-1 text-xs text-slate-500">At least 12 characters.</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold" for="password_confirmation">Confirm password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" class="mt-2 w-full rounded border-slate-300 px-4 py-3">
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

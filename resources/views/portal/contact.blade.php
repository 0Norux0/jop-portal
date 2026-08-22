@php($site = \App\Support\SiteContent::load())
<x-layouts.app title="Contact">
    <section class="bg-[#f7f7f7]">
        <div class="mx-auto grid max-w-7xl gap-8 px-4 py-12 sm:px-6 lg:grid-cols-[0.9fr_1.1fr] lg:px-8">
            <div>
                <p class="font-semibold text-[#2a7190]">Contact</p>
                <h1 class="mt-2 text-4xl font-extrabold text-slate-950">Talk to the right team</h1>
                <p class="mt-4 leading-7 text-slate-600">Use this page for employer questions, candidate support, account access, job reports, and verification questions.</p>
                <div class="mt-6 grid gap-3 text-sm">
                    <a href="mailto:{{ $site['contact']['email'] ?: 'support@example.com' }}" class="rounded-lg border border-slate-200 bg-white p-4 font-semibold">{{ $site['contact']['email'] ?: 'support@example.com' }}</a>
                    @if ($site['contact']['phone'])
                        <a href="tel:{{ $site['contact']['phone'] }}" class="rounded-lg border border-slate-200 bg-white p-4 font-semibold">{{ $site['contact']['phone'] }}</a>
                    @endif
                    @if ($site['contact']['whatsapp_url'])
                        <a href="{{ $site['contact']['whatsapp_url'] }}" class="rounded-lg border border-slate-200 bg-white p-4 font-semibold">WhatsApp</a>
                    @endif
                </div>
            </div>

            <form method="GET" action="mailto:{{ $site['contact']['email'] ?: 'support@example.com' }}" class="rounded-lg bg-white p-6 shadow-sm">
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="text-sm font-bold" for="contact_name">Name</label>
                        <input id="contact_name" name="name" class="mt-2 w-full rounded border-slate-300 px-4 py-3" placeholder="Your name">
                    </div>
                    <div>
                        <label class="text-sm font-bold" for="contact_email">Email</label>
                        <input id="contact_email" name="email" type="email" class="mt-2 w-full rounded border-slate-300 px-4 py-3" placeholder="you@example.com">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="text-sm font-bold" for="subject">Topic</label>
                        <select id="subject" name="subject" class="mt-2 w-full rounded border-slate-300 px-4 py-3">
                            <option>Candidate support</option>
                            <option>Employer support</option>
                            <option>Account access</option>
                            <option>Report a job or user</option>
                            <option>Verification question</option>
                        </select>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="text-sm font-bold" for="body">Message</label>
                        <textarea id="body" name="body" rows="6" class="mt-2 w-full rounded border-slate-300 px-4 py-3" placeholder="Tell us what you need help with"></textarea>
                    </div>
                </div>
                <button class="mt-5 rounded bg-[#2a7190] px-5 py-3 text-sm font-bold text-white">Open email</button>
            </form>
        </div>
    </section>
</x-layouts.app>
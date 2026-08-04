@php($site = \App\Support\SiteContent::load())
<x-layouts.guest>
    <div class="mb-6">
        <p class="text-sm font-semibold uppercase tracking-wide text-[#2a7190]">Almost done</p>
        <h1 class="mt-2 text-3xl font-extrabold">Verify your email</h1>
        <p class="mt-2 text-sm leading-6 text-slate-600">Click the verification link we sent. You can request another email below.</p>
    </div>
    @if (session('status') === 'verification-link-sent')
        <div class="mb-4 rounded border border-green-300 bg-green-50 p-3 text-sm text-green-700">A new verification link has been sent.</div>
    @endif
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <form method="POST" action="/email/verification-notification">
            @csrf
            <button type="submit" class="rounded px-4 py-3 font-semibold text-white" style="background: {{ $site['brand']['primary_color'] }};">Resend email</button>
        </form>
        <form method="POST" action="/logout">
            @csrf
            <button type="submit" class="text-sm font-semibold text-slate-500 underline">Sign out</button>
        </form>
    </div>
</x-layouts.guest>

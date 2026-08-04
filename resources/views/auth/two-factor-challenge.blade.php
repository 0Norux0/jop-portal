@php($site = \App\Support\SiteContent::load())
<x-layouts.guest>
    <div class="mb-6">
        <p class="text-sm font-semibold uppercase tracking-wide text-[#2a7190]">Two-factor security</p>
        <h1 class="mt-2 text-3xl font-extrabold">Verify your sign in</h1>
        <p class="mt-2 text-sm leading-6 text-slate-600">Enter your authenticator code or use a recovery code.</p>
    </div>
    @include('auth._errors')
    <form method="POST" action="/two-factor-challenge" class="space-y-5">
        @csrf
        <div>
            <label class="block text-sm font-semibold" for="code">Authentication code</label>
            <input id="code" name="code" type="text" inputmode="numeric" autofocus autocomplete="one-time-code" class="mt-2 w-full rounded border-slate-300 px-4 py-3">
        </div>
        <details class="rounded border border-slate-200 bg-slate-50 p-4 text-sm">
            <summary class="cursor-pointer font-semibold text-[#2a7190]">Use a recovery code instead</summary>
            <input name="recovery_code" type="text" autocomplete="one-time-code" class="mt-3 w-full rounded border-slate-300 px-4 py-3">
        </details>
        <button type="submit" class="w-full rounded px-4 py-3 font-semibold text-white" style="background: {{ $site['brand']['primary_color'] }};">Verify</button>
    </form>
</x-layouts.guest>

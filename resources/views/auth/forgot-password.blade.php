@php($site = \App\Support\SiteContent::load())
<x-layouts.guest>
    <div class="mb-6">
        <p class="text-sm font-semibold uppercase tracking-wide text-[#2a7190]">Password help</p>
        <h1 class="mt-2 text-3xl font-extrabold">Reset your password</h1>
        <p class="mt-2 text-sm leading-6 text-slate-600">Enter your email and we will send a reset link.</p>
    </div>
    @include('auth._errors')
    @if (session('status'))
        <div class="mb-4 rounded border border-green-300 bg-green-50 p-3 text-sm text-green-700">{{ session('status') }}</div>
    @endif
    <form method="POST" action="/forgot-password" class="space-y-5">
        @csrf
        <div>
            <label class="block text-sm font-semibold" for="email">Email address</label>
            <input id="email" name="email" type="email" required autofocus value="{{ old('email') }}" class="mt-2 w-full rounded border-slate-300 px-4 py-3">
        </div>
        <button type="submit" class="w-full rounded px-4 py-3 font-semibold text-white" style="background: {{ $site['brand']['primary_color'] }};">Send reset link</button>
        <p class="text-center text-sm"><a class="font-semibold text-[#2a7190]" href="/login">Back to sign in</a></p>
    </form>
</x-layouts.guest>

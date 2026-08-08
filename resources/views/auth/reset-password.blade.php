@php($site = \App\Support\SiteContent::load())
<x-layouts.guest>
    <div class="mb-6">
        <p class="text-sm font-semibold uppercase tracking-wide text-[#2a7190]">Secure access</p>
        <h1 class="mt-2 text-3xl font-extrabold">Choose a new password</h1>
    </div>
    @include('auth._errors')
    <form method="POST" action="/reset-password" class="space-y-5">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">
        <div>
            <label class="block text-sm font-semibold" for="email">Email address</label>
            <input id="email" name="email" type="email" required autofocus value="{{ old('email', $request->email) }}" class="mt-2 w-full rounded border-slate-300 px-4 py-3">
        </div>
        <div>
            <label class="block text-sm font-semibold" for="password">New password</label>
            <div class="mt-2 flex rounded border border-slate-300 bg-white focus-within:ring-4 focus-within:ring-emerald-100">
                <input id="password" name="password" type="password" required class="min-w-0 flex-1 border-0 bg-transparent px-4 py-3 focus:ring-0">
                <button type="button" data-password-toggle="password" class="px-4 text-sm font-bold text-[#2a7190]">Show</button>
            </div>
            <p class="mt-1 text-xs text-slate-500">At least 12 characters.</p>
        </div>
        <div>
            <label class="block text-sm font-semibold" for="password_confirmation">Confirm new password</label>
            <div class="mt-2 flex rounded border border-slate-300 bg-white focus-within:ring-4 focus-within:ring-emerald-100">
                <input id="password_confirmation" name="password_confirmation" type="password" required class="min-w-0 flex-1 border-0 bg-transparent px-4 py-3 focus:ring-0">
                <button type="button" data-password-toggle="password_confirmation" class="px-4 text-sm font-bold text-[#2a7190]">Show</button>
            </div>
        </div>
        <button type="submit" class="w-full rounded px-4 py-3 font-semibold text-white" style="background: {{ $site['brand']['primary_color'] }};">Reset password</button>
    </form>
</x-layouts.guest>

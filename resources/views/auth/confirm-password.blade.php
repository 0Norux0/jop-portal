@php($site = \App\Support\SiteContent::load())
<x-layouts.guest>
    <div class="mb-6">
        <p class="text-sm font-semibold uppercase tracking-wide text-[#2a7190]">Confirm access</p>
        <h1 class="mt-2 text-3xl font-extrabold">Enter your password</h1>
    </div>
    @include('auth._errors')
    <form method="POST" action="/user/confirm-password" class="space-y-5">
        @csrf
        <div>
            <label class="block text-sm font-semibold" for="password">Password</label>
            <input id="password" name="password" type="password" required autofocus class="mt-2 w-full rounded border-slate-300 px-4 py-3">
        </div>
        <button type="submit" class="w-full rounded px-4 py-3 font-semibold text-white" style="background: {{ $site['brand']['primary_color'] }};">Confirm</button>
    </form>
</x-layouts.guest>

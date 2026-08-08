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
            <div class="mt-2 flex rounded border border-slate-300 bg-white focus-within:ring-4 focus-within:ring-emerald-100">
                <input id="password" name="password" type="password" required autofocus class="min-w-0 flex-1 border-0 bg-transparent px-4 py-3 focus:ring-0">
                <button type="button" data-password-toggle="password" class="px-4 text-sm font-bold text-[#2a7190]">Show</button>
            </div>
        </div>
        <button type="submit" class="w-full rounded px-4 py-3 font-semibold text-white" style="background: {{ $site['brand']['primary_color'] }};">Confirm</button>
    </form>
</x-layouts.guest>

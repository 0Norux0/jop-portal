@php($site = \App\Support\SiteContent::load())
<x-layouts.guest>
    <div class="mb-6">
        <p class="text-sm font-semibold uppercase tracking-wide text-[#2a7190]">Welcome back</p>
        <h1 class="mt-2 text-3xl font-extrabold">Sign in to your account</h1>
        <p class="mt-2 text-sm leading-6 text-slate-600">Access your dashboard, saved jobs, applications, and admin tools from one place.</p>
    </div>

    @include('auth._errors')

    <form method="POST" action="/login" class="space-y-5">
        @csrf
        <div>
            <label class="block text-sm font-semibold" for="email">Email address</label>
            <input id="email" name="email" type="email" required autofocus value="{{ old('email') }}" autocomplete="email" class="mt-2 w-full rounded border-slate-300 px-4 py-3">
        </div>
        <div>
            <div class="flex items-center justify-between">
                <label class="block text-sm font-semibold" for="password">Password</label>
                <a class="text-sm font-semibold text-[#2a7190]" href="/forgot-password">Forgot password?</a>
            </div>
            <div class="mt-2 flex rounded border border-slate-300 bg-white focus-within:ring-4 focus-within:ring-emerald-100">
                <input id="password" name="password" type="password" required autocomplete="current-password" class="min-w-0 flex-1 border-0 bg-transparent px-4 py-3 focus:ring-0">
                <button type="button" data-password-toggle="password" class="px-4 text-sm font-bold text-[#2a7190]">Show</button>
            </div>
        </div>
        <label class="flex items-center gap-2 text-sm text-slate-700">
            <input type="checkbox" name="remember" class="rounded border-slate-300"> Remember me
        </label>
        <button type="submit" class="w-full rounded px-4 py-3 font-semibold text-white" style="background: {{ $site['brand']['primary_color'] }};">Sign in</button>
        <p class="text-center text-sm text-slate-600">
            New to {{ $site['brand']['name'] }}?
            <a class="font-semibold text-[#2a7190]" href="/register">Create account</a>
        </p>
    </form>
</x-layouts.guest>

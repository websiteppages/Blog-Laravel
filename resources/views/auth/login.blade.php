@extends('web.layouts.app')

@section('title', config('app.name', 'Inkwell'))

{{-- ── Page-level meta tags ─────────────────────────────── --}}
@push('styles')

@endpush

@section('web-content')




{{-- ════════════════════════════════════════════════════════
     SECTION 5 — BOTTOM CTA STRIP
     ════════════════════════════════════════════════════════ --}}
@guest
<section class="bg-cream-mid border-t border-black/10 py-16">
    <div class="max-w-[600px] mx-auto px-6 md:px-8 text-center">
        <form method="POST" action="{{ route('login') }}" class="max-w-md mx-auto bg-white shadow-md rounded-xl p-6 space-y-6">
    @csrf

    <h2 class="text-2xl font-bold text-gray-800 text-center">Login to your account</h2>

    <!-- Email -->
    <div>
        <label for="email" class="block text-sm font-medium text-gray-700">
            Email
        </label>

        <input
            id="email"
            type="email"
            name="email"
            value="{{ old('email') }}"
            required
            autofocus
            autocomplete="username"
            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
        >

        @error('email')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Password -->
    <div>
        <label for="password" class="block text-sm font-medium text-gray-700">
            Password
        </label>

        <input
            id="password"
            type="password"
            name="password"
            required
            autocomplete="current-password"
            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
        >

        @error('password')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Remember + Forgot -->
    <div class="flex items-center justify-between">
        <label class="flex items-center text-sm text-gray-600">
            <input
                type="checkbox"
                name="remember"
                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
            >
            <span class="ml-2">Remember me</span>
        </label>

        @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="text-sm text-indigo-600 hover:underline">
                Forgot password?
            </a>
        @endif
    </div>

    <!-- Submit -->
    <div>
        <button
            type="submit"
            class="w-full bg-indigo-600 text-white font-semibold py-2.5 rounded-lg hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition"
        >
            Login
        </button>
    </div>

</form>

    </div>
</section>
@endguest

@endsection


{{-- ── Page-level scripts ────────────────────────────────── --}}
@push('after-scripts')

@endpush

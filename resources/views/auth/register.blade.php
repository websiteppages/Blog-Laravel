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
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
    <form method="POST" action="{{ route('register') }}" class="max-w-md w-full bg-white shadow-md rounded-xl p-6 space-y-6">
        @csrf

        <h2 class="text-2xl font-bold text-gray-800 text-center">Create an account</h2>

        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">
                Full Name
            </label>

            <input
                id="name"
                type="text"
                name="name"
                value="{{ old('name') }}"
                required
                autofocus
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            >

            @error('name')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

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
                autocomplete="new-password"
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            >

            @error('password')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                Confirm Password
            </label>

            <input
                id="password_confirmation"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password"
                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            >
        </div>

        <!-- Submit -->
        <div>
            <button
                type="submit"
                class="w-full bg-indigo-600 text-white font-semibold py-2.5 rounded-lg hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition"
            >
                Register
            </button>
        </div>

        <!-- Login Link -->
        <p class="text-sm text-center text-gray-600">
            Already have an account?
            <a href="{{ route('login') }}" class="text-indigo-600 hover:underline font-medium">
                Login
            </a>
        </p>
    </form>
</div>

    </div>
</section>
@endguest

@endsection


{{-- ── Page-level scripts ────────────────────────────────── --}}
@push('after-scripts')

@endpush

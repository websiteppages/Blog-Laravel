@extends('web.layouts.app')

@section('title', config('app.name', 'Inkwell'))

{{-- ── Page-level meta tags ─────────────────────────────── --}}
@push('styles')
<meta name="description" content="Explore thoughtfully crafted articles on design, technology, and the craft of building things that matter.">

<style>
    /* ── Hero diagonal pattern ──────────────────────────── */
    .hero-pattern {
        background: repeating-linear-gradient(
            -45deg,
            transparent,
            transparent 28px,
            rgba(201, 136, 58, 0.06) 28px,
            rgba(201, 136, 58, 0.06) 29px
        );
    }

    /* ── Post card hover lift ───────────────────────────── */
    .post-card-link {
        display: block;
        text-decoration: none;
        color: inherit;
    }
    .post-card-link:hover .post-card-inner {
        transform: translateY(-3px);
        box-shadow: 0 12px 40px rgba(13, 13, 13, 0.10);
    }
    .post-card-inner {
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    /* ── Featured card overlay ──────────────────────────── */
    .featured-overlay {
        background: linear-gradient(
            to top,
            rgba(13,13,13,0.88) 0%,
            rgba(13,13,13,0.40) 60%,
            transparent 100%
        );
    }

    /* ── Cover image zoom on hover ──────────────────────── */
    .cover-zoom {
        transition: transform 0.5s ease;
    }
    .post-card-link:hover .cover-zoom {
        transform: scale(1.04);
    }

    /* ── Filter pill active state ───────────────────────── */
    .filter-pill { transition: all 0.15s ease; }
    .filter-pill.active,
    .filter-pill:hover {
        background-color: var(--color-ink);
        color: var(--color-cream);
        border-color: var(--color-ink);
    }
    .filter-pill.active { pointer-events: none; }

    /* ── Skeleton loader ────────────────────────────────── */
    @keyframes shimmer {
        0%   { background-position: -600px 0; }
        100% { background-position: 600px 0; }
    }
    .skeleton {
        background: linear-gradient(90deg, #ede7d8 25%, #f5f0e8 50%, #ede7d8 75%);
        background-size: 600px 100%;
        animation: shimmer 1.4s infinite;
        border-radius: 6px;
    }

    /* ── Reading progress bar ───────────────────────────── */
    #reading-progress {
        position: fixed;
        top: var(--header-height, 64px);
        left: 0;
        height: 2px;
        background: var(--color-amber);
        z-index: 99;
        width: 0%;
        transition: width 0.1s linear;
    }

    /* ── Fade-in-up animation ───────────────────────────── */
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(20px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .fade-up { animation: fadeUp 0.55s ease both; }
    .delay-1 { animation-delay: 0.08s; }
    .delay-2 { animation-delay: 0.16s; }
    .delay-3 { animation-delay: 0.24s; }
    .delay-4 { animation-delay: 0.32s; }

    /* ── Search input clear button ──────────────────────── */
    .search-clear { display: none; }
    #search-input:not(:placeholder-shown) ~ .search-clear { display: flex; }

    /* ── Popular post rank number ───────────────────────── */
    .rank-number {
        font-family: var(--font-display);
        font-size: 2rem;
        font-weight: 700;
        color: var(--color-cream-deep);
        line-height: 1;
        min-width: 2rem;
        text-align: center;
        flex-shrink: 0;
    }
</style>
@endpush

@section('web-content')




{{-- ════════════════════════════════════════════════════════
     SECTION 5 — BOTTOM CTA STRIP
     ════════════════════════════════════════════════════════ --}}
@guest
<section class="bg-cream-mid border-t border-black/10 py-16">
    <div class="max-w-[600px] mx-auto px-6 md:px-8 text-center">

        <div class="inline-flex items-center gap-2 bg-amber-pale text-amber
                     text-[11px] font-semibold tracking-widest uppercase
                     px-3 py-1.5 rounded-full mb-5">
            <span class="w-1.5 h-1.5 rounded-full bg-amber"></span>
            Join Inkwell
        </div>

        <h2 class="font-display text-3xl md:text-4xl font-bold
                    tracking-tight text-ink mb-4">
            Start writing your story
        </h2>

        <p class="text-gray-500 mb-8 leading-relaxed">
            Join thousands of writers sharing ideas on design, technology,
            and everything in between. Free to start.
        </p>

        <div class="flex flex-wrap gap-3 justify-center">
            <a href="{{ route('register') }}"
               class="btn-primary py-3 px-8 text-sm">
                Create free account →
            </a>
            <a href="{{ route('login') }}"
               class="btn-outline py-3 px-8 text-sm">
                Sign in
            </a>
        </div>
    </div>
</section>
@endguest

@endsection


{{-- ── Page-level scripts ────────────────────────────────── --}}
@push('after-scripts')

@endpush

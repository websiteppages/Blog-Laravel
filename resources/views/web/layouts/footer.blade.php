{{-- Footer --}}
<footer class="bg-ink text-cream/50 py-12 mt-16">
    <div class="max-w-[1100px] mx-auto px-6 md:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">

            {{-- Brand --}}
            <div>
                <div class="flex items-center gap-2 mb-3">


                </div>
                {{-- @if($siteDesc)

                @endif --}}
            </div>

            {{-- Quick links --}}
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-cream/30 mb-3">
                    Navigation
                </h4>
                <div class="space-y-2">
                    {{-- @foreach([
                        [route('home'), 'Home'],
                        [route('posts.index'), 'Articles'],
                        [route('register'), 'Sign Up'],
                    ] as [$url, $label])
                    <a href="{{ $url }}"
                       class="block text-sm hover:text-cream transition-colors">
                        {{ $label }}
                    </a>
                    @endforeach --}}
                </div>
            </div>

            {{-- Social links from settings --}}
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-cream/30 mb-3">
                    Connect
                </h4>
                <div class="flex flex-wrap gap-3">

                </div>
            </div>
        </div>

        <div class="border-t border-white/10 pt-6 flex flex-col sm:flex-row
                     items-center justify-between gap-3">
            {{-- <p class="text-xs text-cream/30">
                © {{ date('Y') }} {{ $siteName }}. All rights reserved.
            </p> --}}
            {{-- @if(\App\Models\Setting::get('site_email'))
            <a href="mailto:{{ \App\Models\Setting::get('site_email') }}"
               class="text-xs text-cream/30 hover:text-cream/60 transition-colors">
                {{ \App\Models\Setting::get('site_email') }}
            </a>
            @endif --}}
        </div>
    </div>
</footer>

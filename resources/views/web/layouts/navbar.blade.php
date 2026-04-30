<nav class="sticky top-0 z-50 bg-cream/95 backdrop-blur-md border-b border-black/8">
    <div class="max-w-[1100px] mx-auto px-6 md:px-8">
        <div class="flex items-center justify-between h-14">
            {{-- Logo (from settings) --}}

            {{-- Desktop nav --}}
            <div class="hidden md:flex items-center gap-1">
                <a href="{{ route('home') }}"
                   class="px-4 py-2 rounded-lg text-sm font-medium transition-colors
                           {{ active_route('home')
                               ? 'bg-ink text-cream'
                               : 'text-gray-500 hover:text-ink hover:bg-cream-mid' }}">
                    Home
                </a>
                <a href=""
                   class="px-4 py-2 rounded-lg text-sm font-medium transition-colors
                           {{ active_route('posts.*')
                               ? 'bg-ink text-cream'
                               : 'text-gray-500 hover:text-ink hover:bg-cream-mid' }}">
                    Articles
                </a>
            </div>

            {{-- Right side --}}
            <div class="flex items-center gap-2">

                {{-- Notification bell --}}
                @auth

                @endauth

                {{-- User menu --}}
                @auth


                @else
                <a href="{{ route('login') }}"
                   class="text-sm font-medium text-gray-500
                           hover:text-ink transition-colors">
                    Login
                </a>
                <a href="{{ route('register') }}"
                   class="btn-primary text-sm py-2 px-4">
                    Get Started
                </a>
                @endauth

                {{-- Social links from settings --}}

            </div>
        </div>
    </div>
</nav>

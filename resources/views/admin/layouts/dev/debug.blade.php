 {{--
    composer require barryvdh/laravel-debugbar --dev
    or
    composer require barryvdh/laravel-debugbar --dev -W

    composer remove barryvdh/laravel-debugbar

    .env check (மிக முக்கியம்)
    ----------------------------
        APP_DEBUG=true
        APP_ENV=local

        👉 Debugbar only debug mode-ல் தான் visible


        Debugbar install ஆன பிறகு:
        -------------------------------
            php artisan config:clear
            php artisan cache:clear
            php artisan view:clear
            php artisan route:clear

            php artisan optimize:clear



        பிறகு browser-ல் open பண்ணும்போது:
        ----------------------------------
            queries count
            load time
            memory usage

        எல்லாம் bottom-ல் காட்டும் (debug bar)


        --------------------------------------------
        🏆 Best Performance Benchmarks (Laravel)
            🟢 Queries
                Best: 10 – 50 🔥
                Good: 50 – 100
                Bad: 100+

            👉 நீங்கள்: 49 → BEST range ✅

            🟢 Models
                Best: < 150
                Good: 150 – 300
                Bad: 300+

            👉 நீங்கள்: 236 → GOOD (acceptable) 👍

            🟢 Cache
                Best: smart usage (5 – 50 meaningful calls)
                Bad: thousands (loop misuse)

            👉 நீங்கள்: 6 → PERFECT ✅

                🟢 Response Time (important!)
                    Best: < 200ms ⚡
                    Good: < 500ms
                    Bad: > 1s


                🟢 Memory
                    Best: < 20MB
                    Good: 20–40MB

            👉 இது Debugbar-ல் check பண்ணுங்க


        ----------------------------------------------------

            php artisan vendor:publish --provider="Barryvdh\Debugbar\ServiceProvider"

                பிறகு config/debugbar.php file-ல் check பண்ணுங்க:

                'enabled' => env('APP_DEBUG', false),



                ✅ 4. Middleware / API routes check

                    👉 Debugbar web routes-ல் மட்டும் வரும்

                        routes/api.php  ❌ இது வராது:

                        routes/web.php ✔️ இது வரும்:


--}}

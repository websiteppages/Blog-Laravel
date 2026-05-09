{{--
    Task Scheduling (Automatic Job)
    --------------------------------
        இது ஒரு scheduled task (cron job)
        👉 என்ன செய்கிறது?
        ------------------------
            ஒவ்வொரு மணிநேரத்துக்கும் (hourly) run ஆகும்
            InviteService class-ல் இருக்கும் expireStale() method-ஐ call பண்ணும்
        InviteService::expireStale()
        ------------------------------
            expires_at time கடந்த invites-ஐ கண்டுபிடிக்கும்
            அவற்றை expired என்று mark பண்ணும்

      Schedule::call(...)->hourly();
      -----------------------------
            👉 இது நேரடியாக server run ஆகாது
            👉 அதுக்கு ஒரு master cron தேவை

            👉 Step 1: Terminal open பண்ணு
                crontab -e
            👉 Step 2: இந்த line add பண்ணு
                * * * * * php /path-to-your-project/artisan schedule:run >> /dev/null 2>&1
                உங்க project path: /var/www/html/my-laravel-app
                * * * * * php /var/www/html/my-laravel-app/artisan schedule:run >> /dev/null 2>&1

    Shared Hosting (cPanel) இருந்தா?
    ------------------------------------
        👉 Steps:
            cPanel login
            Cron Jobs section open
            Add new cron job

        👉 Settings:
            Common Settings → Once per minute (* * * * *)
        Command:
            php /home/username/public_html/artisan schedule:run

    👉 Manual run பண்ணி test பண்ணலாம்:
    --------------------------------------------
        php artisan schedule:run
        or
        php artisan schedule:list

    👉 production-ல் இதைப் பயன்படுத்தலாம்:
    ---------------------------------------------
        * * * * * php /path/artisan schedule:run >> storage/logs/cron.log 2>&1
            👉 use when:
                errors check பண்ணணும்
                first setup testing
        * * * * * php /path/artisan schedule:run >> /dev/null 2>&1
            👉 use when:
                எல்லாம் stable
                unnecessary logs வேண்டாம்

    🔹 Low traffic siteனா?
    ---------------------------
        👉 Traffic-க்கு cron-க்கு சம்பந்தமே இல்லை
        👉 இது server-level task (user visit வேண்டாம்)
            schedule:run run ஆகும் → few milliseconds
            load almost zero

            👉 அதனால்:
            ✔️ even small VPS / shared hosting → safe
    -----------------------------------------------------------------------------
    Artisan Command (inspire)
    --------------------------
        👉 inspire command production use காக இல்ல, அது ஒரு example / demo command தான்.

        👉 terminal-ல் run பண்ணலாம்:
        -----------------------------------
            php artisan inspire


    -----------------------------------------------------------------














--}}

{{--
    my role எப்படி get பண்ணுவது?
    ----------------------------------
        Auth::user()->getRoleNames()->first();

        auth()->user()->hasRole(Role::Owner->value)

    getRoleNames() எங்கிருந்து வருகிறது?
    --------------------------------------
        இது Spatie Permission package-லிருந்து வரும்

    Check specific role
    ---------------------
        @if(auth()->user()->hasRole(\App\Enums\UserRole::Owner->value))

        Service-ல்
        --------------
            public function getMyRole(): ?string
            {
                return auth()->user()?->getRoleNames()->first();
            }

------------------------------------------------------------------------
    @foreach(\App\Enums\UserRole::cases() as $role) - get enums role only

    @foreach($allRoles as $role) - get all row
------------------------------------------------------------------------












--}}

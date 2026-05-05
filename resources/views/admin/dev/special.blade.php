{{--
    before()
    ----------
        இந்த method முதலில் run ஆகும் (first check)
        மற்ற எல்லா permission checks-க்கும் முன்னாடி run ஆகும்
    --------------------------------------------------------------------------------
    string $ability
    ------------------
    👉 இது user என்ன action பண்ண try பண்ணுறான் என்பதை சொல்லும்
        Examples: view, create, update,delete - ($ability = "delete-post")
    --------------------------------------------------------------------------------
    bool
    ------
        👉 இது return type declaration
                bool = true / false
                ?bool = true / false / null (3 values allowed)
                null என்றால்:“இந்த method decide பண்ணல, அடுத்த policy rules check பண்ணு”
    --------------------------------------------------------------------------------
    $target = உள்ளே போக முயற்சி பண்ணுறவர்()
    ---------
        நீ access பண்ண try பண்ணுற அந்த user

        public function view(User $user, User $target): bool
            நீ (Anand) login பண்ணிருக்க
            நீ மற்றொரு user (Ravi) profile பார்க்குற
            or
            நீ உன் profile மட்டும் தான் பார்க்கலாம்
            or
            எல்லாரும் எல்லாரையும் பார்க்கலாம்

        public function view(User $user, User $target): bool
            {
                return $user->hasRole('admin');
             }
            Admin மட்டும் யாரையும் பார்க்கலாம்
    --------------------------------------------------------------------------------
        return true = full access
        return null = continue other checks
    --------------------------------------------------------------------------------
    --------------------------------------------------------------------------------



















--}}

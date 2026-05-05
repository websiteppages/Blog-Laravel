{{--
    whereHas
    ----------
        relation உள்ள records-ல condition match ஆகுற parent records மட்டும் எடு
        relation table-ல filter பண்ணணும்

        Filter by specific role
        ---------------------------
            User::whereHas('roles', function ($q) use ($role) {
                $q->where('name', $role); // 👉 roles table-ல name column-ஐ filter பண்ணுது
            })->get();

            Why whereHas?
            -------------
                👉 Users table-ல் role column இல்ல ❌
                👉 roles relation வேற table-ல் இருக்கு ✅

                👉 User model-ல relationship:
                -------------------------------
                    public function roles()
                    {
                        return $this->belongsToMany(Role::class);
                    }

        Filter by role
        --------------------------
             public function getUsersWithRoles(): Collection
            {
                return User::whereHas('roles')->get();
            }
            whereHas('roles') = roles relation இருக்கும் users மட்டும்
            -------------------------------------------------------------
                👉 role assign ஆகாத users → exclude ஆகும் ❌
                👉 role assign ஆன users → மட்டும் வரும் ✅

    ----------------------------------------------------------

















--}}

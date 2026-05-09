{{--
    belongsToWorkspace() - "user இந்த workspace-க்கு சேர்ந்தவரா?"
    --------------------------------------------------------------------
    BelongsTo
    ------------
        இந்த model இன்னொரு model-க்கு சேர்ந்தது
        Many → One
        Many posts belong to one user.

        | Relationship | Meaning           |
        | ------------ | ----------------- |
        | BelongsTo    | child → parent    |
        | HasMany      | parent → children |


    --------------------------------------------------------------------
    BelongsToMany
    --------------
        Many-to-Many relationship class
        Many users ↔ many workspaces.

        இரண்டு tables-மும் ஒன்றுக்கொன்று பல records வைத்திருக்கும் relationship

        "ஒரு user பல workspaces-ல் இருக்கலாம்,ஒரு workspace-ல் பல users இருக்கலாம்"

        | Relationship  | Meaning     |
        | ------------- | ----------- |
        | HasOne        | one         |
        | HasMany       | many        |
        | BelongsToMany | many ↔ many |

    --------------------------------------------------------------------
    wherePivot()
    --------------
        wherePivot() என்பது many-to-many relationship-ல pivot table column-ஐ filter பண்ண பயன்படுத்தப்படும் method.

    withPivot
    ------------
        pivot table-ல் இருக்கும் extra columns-ஐ access செய்ய பயன்படுத்தப்படும் method
        pivot table-ல status, role columns-யும் load பண்ணு

        ->withPivot('status')
        $workspace->pivot->status

        Normally Laravel only loads:user_id, workspace_id
            👉 Extra columns: status, role available ஆகாது.
    --------------------------------------------------------------------
    Route::resource - create, store, index, edit, update, destroy
    -----------------
        Route::resource('workspaces', WorkspaceController::class)->only(['index', 'create', 'store']);
        Route::resource('workspaces', WorkspaceController::class)->except(['index', 'create', 'store']);

    --------------------------------------------------------------------
    Workspace::class
    -----------------
        Workspace model class name-ஐ string ஆக கொடுக்கிறது.
    --------------------------------------------------------------------
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
    $before
    ---------
        $before = $invite->toArray();

        இதன் வேலை:
        -----------------
        👉 பழைய data save பண்ணுவது.

        ஆனா:
        ---------
            ❌ log இல்லை
            ❌ history இல்லை
            ❌ compare இல்லை

            னா use இல்லை.
            அதனால் remove பண்ணலாம் 👍
    ----------------------------------------------------------



















--}}

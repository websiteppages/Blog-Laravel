{{--
    # Install பண்ணுங்க
    ------------------------
        composer require spatie/laravel-permission

        php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"

        php artisan migrate


        php artisan make:middleware RoleMiddleware

        app/Http/Middleware/RoleMiddleware.php
        ----------------------------------------
            Undefined method 'hasRole' --- ide error just ignore no problem

        bootstrap/app.php — Middleware Register
        -----------------

        Add User.php model
        ------------

        Add Enums Folder
        ------------------
            UserRole.php
            Permission.php

        app/Policies/UserPolicy.php
        -----------------------------
            RolePolicy.php
            ----------------

         app/Providers/AuthServiceProvider.php
         ---------------------------------------
            AppServiceProvider.php
            -----------------------

        config/permission.php
        ---------------------
            'models' => [
                'role' => App\Models\Role::class,
            ]




        php artisan cache:clear
        php artisan config:clear



        --------------------------------------------------------------------------------
        Permission.php
        ---------------
            | Approach                      | Safe | Flexible | Scalable | Professional |
            | ----------------------------- | ---- | -------- | -------- | ------------ |
            | Enum only                     | ❌    | ❌        | ❌        | ❌            |
            | DB only                       | ✅    | ✅        | ✅        | ✅            |
            | **Enum + DB (your approach)** | ✅✅   | ✅✅       | ✅✅       | ⭐⭐⭐⭐⭐        |

            Enum + DB - if any change both


        ----------------------------------------------------------------------------
        Error
        -----
            Call to undefined method Spatie\Permission\Models\Role::isProtected()
            config/permission.php
            ---------------------
                'models' => [
                        'role' => App\Models\Role::class,
                    ]

                    php artisan config:clear
                    php artisan cache:clear
                    php artisan optimize:clear


    ----------------------------------------------------------------
        toEnum
        --------
            toEnum() என்பது Database role name → Enum value ஆக மாற்றும் function.

            Database-ல உள்ள role name - owner , admin

            UserRole::tryFrom(...) - 👉 அந்த string-ஐ Enum-ஆக மாற்ற முயற்சி பண்ணும்
            ----------------------

                | DB value          | Enum output     |
                | ----------------- | --------------- |
                | owner             | UserRole::Owner |
                | admin             | UserRole::Admin |

                ❌ Without Enum - if ($role->name == 'owner') --- 👉 typo risk ❌ 👉 hard to maintain ❌

                ✅ With Enum - $role->toEnum()?->isProtected() --- ✔ safe ✔ centralized logic ✔ scalable

    ----------------------------------------------------------------
    UserRole::cases()
    ------------------
        👉 எல்லா roles-யும் எடுக்கிறது (Enum)
    ----------------------------------------------------------------
        scopeProtected
        ---------------
            Protected roles மட்டும் database-ல இருந்து filter பண்ணி எடுக்க உதவும் function”

            அதாவது:
            ----------

                delete பண்ணக்கூடாத roles
                system முக்கிய roles
                modify பண்ணக்கூடாத roles

            Instead of:
            -----------
                Role::whereIn('name', ['owner','admin'])->get();
                You use:
                ---------
                    Role::protected(); or
                    Role::protected()->get();

    ----------------------------------------------------------------
     isProtected()
    --------------
        👉 அந்த role பாதுகாக்கப்பட்டதா என்று check பண்ணும் method
        👉 delete செய்யலாம் (optional), ஆனால்:
            confirmation தேவை
            extra validation
            restricted assignment
    ----------------------------------------------------------------
    isImmutable()
    -------------
        Immutable = மாற்ற முடியாதது
        👉 “மாற்ற முடியாத role (மாறாத role)” என்பதை check செய்யும் function

        இந்த role-ஐ change (edit/delete) பண்ண முடியாது -  owner

        @unless() - இந்த role immutable இல்லனா மட்டும் run ஆகும்

        👉 அதாவது அந்த role:
        ----------------------------
            ❌ edit செய்ய முடியாது
            ❌ permissions மாற்ற முடியாது
            ❌ delete செய்ய முடியாது (உங்கள் logic-ல்)

            UserRole::Owner
            UserRole::Admin

            abort_if($role->isImmutable(), 403);
            ----------------------------------------
            👉 அதாவது:

                “இந்த role-ஐ edit பண்ண முடியாது”
                → Access denied (403)
    ----------------------------------------------------------------
    hasRole('owner')
        இந்த userக்கு ‘owner’ role இருக்கா?” என்று check பண்ணும்
        இந்த userக்கு owner role assign பண்ணப்பட்டிருக்கா?”

        இது எங்கிருந்து வருகிறது?
            custom method (நீங்கள் எழுதினது)
            அல்லது
            Spatie Laravel Permission package method
    --------------------------------------------------------------------------
    hasName()
    ----------
        👉 இந்த role-க்கு கொடுத்த name சரியா இருக்கா என்று check பண்ணும்

            $role->hasName('admin');
            👉 இந்த role name "admin" ஆ? என்று check பண்ணுது

            if ($role->hasName('owner')) {
                abort(403);
            }

    ----------------------------------------------------------------
    hasUsers()
    -----------
        👉 இந்த role-க்கு users attach பண்ணப்பட்டிருக்கா இல்லையா என்று check பண்ணும்

            $role->hasUsers();
            👉 இந்த role-க்கு users இருக்காங்கலா? என்று check பண்ணுது

            if ($role->hasUsers()) {
                throw new Exception("Cannot delete role");
            }

    ----------------------------------------------------------------
    usersCount()
    -------------
        👉 இந்த role-க்கு எத்தனை users இருக்காங்கன்னு எண்ணிக்கை சொல்லும்

        {{ $role->usersCount() }}

    ----------------------------------------------------------------

        1. Spatie Role (default package)
        ----------------------------------
            use Spatie\Permission\Models\Role;

            👉 இது என்ன?
            ----------------
                Laravel Spatie package கொடுக்கும் ready-made Role model
                Basic features:
                roles table access
                permissions attach
                users relation
                ❌ Limitation

                👉 இது generic (எல்லாருக்கும் பொதுவான) model
                ------------------------------------------------------

                business logic இல்லை
                your project rules இல்லை
                enum integration இல்லை
                customization limited



        ஏன் App\Models\Role பயன்படுத்த வேண்டும்?
        --------------------------------------------------
            use App\Models\Role;

            Business logic சேர்க்கலாம்
            ------------------------------
                public function isProtected()
                public function isImmutable()
                public function hasUsers()

                👉 Spatie-ல் இது இல்லை

            Enum integration
            ------------------
                UserRole::tryFrom($this->name)
                👉 Strong type system (safe coding)

            No duplication
            ----------------
                Without custom model: in_array($role->name, ['owner','admin']) - 👉 everywhere repeat ❌

                With custom model: $role->isProtected()

            | Feature           | Spatie Role | Custom Role |
            | ----------------- | ----------- | ----------- |
            | Basic role system | ✅           | ✅           |
            | Business logic    | ❌           | ✅           |
            | Enum support      | ❌           | ✅           |
            | Reusability       | ❌           | ✅           |
            | Maintainability   | medium      | high        |

    ----------------------------------------------------------------
    Policy vs Gate - authorization (permission control) க்கு பயன்படுத்தப்படுகின்றது
    ----------------
        👉 Gate = Simple permission rule
            Gate = Security Guard at entrance
                👉 ஒரே check: “ID இருக்கா? உள்ளே போகலாம்”

            Policy = Model-based permissions க்கு use பண்ணப்படுகிறது (CRUD control)
                ஒரு user இந்த model data-க்கு என்ன செய்யலாம்?” என்பதைக் control பண்ணுவது

                Post → create / view / update / delete
                👉 இதை control பண்ண Policy use பண்ணுவோம்.

                Controller
                ------------
                    $this->authorize('update', $post);

                Blade usage
                --------------
                    @can('removeUserRole', $user)

                        removeUserRole(User $authUser, User $target)
                            authUser = login user
                            target   = $user

    ----------------------------------------------------------------





--}}

{{--

    ஒவ்வொரு workspace தனி உலகம்
        👉 User பல workspace use பண்ணலாம்
        👉 ஒவ்வொரு workspace-க்கும்:
                தனி roles
                தனி users
                தனி data
        👉 data mix ஆகாது ❌
        👉 fully isolated ✔

    ------------------------------------------------------------------------
    users
    -------
        current_workspace_id
            👉 ஒரு user ஒரே நேரத்தில் பல workspace-ல இருக்கலாம்
            👉 ஆனால் UI-ல ஒரே workspace தான் active இருக்கும்

            "User belongs to many workspaces,
but works in ONE workspace at a time"

            Example

                anand → 3 workspace இருக்கலாம்
                ஆனா current → "Company A"
    ------------------------------------------------------------------------
    workspaces
    ------------
        👉 இது ஒரு company / team / project space

        🔹 fields
        ------------
            owner_id - 👉 அந்த workspace create பண்ணியவர் (owner)
            slug - 👉 URL use பண்ணலாம்
            example: my-company
    ------------------------------------------------------------------------
    workspace_members
    -------------------
        👉 user + workspace join table

        🔹 fields
        -------------
            workspace_role_id - 👉 அந்த userக்கு என்ன role
            status - 👉 invited / active / suspended

        | User | Workspace | Role   |
        | ---- | --------- | ------ |
        | B    | A         | editor |
        | C    | A         | viewer |

    ------------------------------------------------------------------------
    workspace_roles
    ------------------
        👉 workspace-specific roles

        👉 global roles இல்லை ❌
        👉 per workspace roles ✔

        🔹 fields
         ------------
            name → editor, manager
            permissions (json) → ["edit_post", "delete_post"]
            is_system
            👉 default role (delete செய்ய முடியாது)

        👉 "editor" role - global roles இல்லை ❌
        -----------------
            Workspace A-ல் வேற
            Workspace B-ல் வேற
    ------------------------------------------------------------------------
    workspace_invites
    -------------------
        Admin invite send
        User accept
        member create
    ------------------------------------------------------------------------
    workspace_settings
    --------------------
        👉 dynamic settings
                theme = dark
                timezone = IST
    ------------------------------------------------------------------------
    ------------------------------------------------------------------------


    👑 1. Site Owner (Super Admin)

        👉 Highest level control

        எல்லா content-க்கும் full access
        எந்த user-ஓட content-யும் edit / delete / view
        Admin / Moderator/ developer create பண்ணலாம்
        permission rules override பண்ணலாம்

        User A (Content Owner)
        தனது contentக்கு custom roles define பண்ணலாம்
        editor
        viewer
        manager

        👉 இந்த roles User A scopeக்குள் மட்டும் (global இல்லை)

        User B / C
        User A assign பண்ணிய role அடிப்படையில் access கிடைக்கும்


        User B or user C தனது content மட்டும் control பண்ணுவார்
        Reverse access கிடையாது
        Site Owner மட்டும் exception"


    ------------------------------------------------------------------------

















--}}

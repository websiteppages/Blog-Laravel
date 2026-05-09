{{--
    InviteService - Invite create செய்கிறது
        ↓
    MemberInvited Event - "Member invited!"
        ↓
    EventServiceProvider - இந்த eventக்கு யார் வேலை செய்ய வேண்டும்?
        ↓
    SendInviteEmail Listener - Send email
        ↓
    WorkspaceInviteMail - email structure class
        ↓
    resources/views/emails/workspace-invite.blade.php
    ---------------------------------------------------------------
    InviteService
    ----------------
        இங்கே தான் main business logic இருக்கும்.
            ✅ invite create பண்ணும்
            ✅ token generate பண்ணும்
            ✅ database save பண்ணும்

            பிறகு event dispatch பண்ணும்.
    ---------------------------------------------------------------
    MemberInvited Event
    ----------------------
        "ஒரு member invite செய்யப்பட்டார்" என்று application-க்கு announce பண்ணுவது.
        Example:
        ----------
            MemberInvited::dispatch(
                $invite,
                $workspace,
                $inviter
            );
    ---------------------------------------------------------------
    EventServiceProvider
    -----------------------
        "MemberInvited event நடந்தால் எந்த listener run ஆக வேண்டும்?"
    ---------------------------------------------------------------
    SendInviteEmail Listener
    --------------------------
        இந்த listener actual action execute பண்ணும்.
        இங்கே:
        ---------
            ✅ notification settings check
            ✅ accept URL generate
            ✅ Mail send
    ---------------------------------------------------------------
    WorkspaceInviteMail
    ----------------------
        இது email structure class.

        இதில்:
        --------
            ✅ subject
            ✅ view
            ✅ data passing

        define பண்ணுவோம்.
    ---------------------------------------------------------------




















--}}

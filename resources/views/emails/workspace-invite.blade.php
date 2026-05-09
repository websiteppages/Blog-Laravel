<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Workspace Invitation</title>
</head>
<body style="font-family: Arial, sans-serif;">

    <h2>
        Workspace Invitation
    </h2>

    <p>
        Hello,
    </p>

    <p>
        You have been invited to join
        <strong>{{ $workspace->name }}</strong>.
    </p>

    <p>
        Click the button below to accept the invitation:
    </p>

    <p>
        <a
            href="{{ $acceptUrl }}"
            style="
                display:inline-block;
                padding:12px 20px;
                background:#2563eb;
                color:#ffffff;
                text-decoration:none;
                border-radius:6px;
            "
        >
            Accept Invitation
        </a>
    </p>

    <p>
        This invitation may expire in 7 days.
    </p>

</body>
</html>

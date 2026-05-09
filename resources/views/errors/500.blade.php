<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 — Server Error</title>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@700&family=Plus+Jakarta+Sans:wght@400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            background: #0d0d0d;
            color: #f5f0e8;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 2rem;
        }
        .code {
            font-family: 'Fraunces', serif;
            font-size: clamp(6rem, 20vw, 10rem);
            font-weight: 700;
            color: rgba(201,136,58,0.25);
            line-height: 1;
            margin-bottom: 1rem;
            letter-spacing: -0.05em;
        }
        h1 {
            font-family: 'Fraunces', serif;
            font-size: 1.75rem;
            font-weight: 700;
            color: #f5f0e8;
            margin-bottom: 0.75rem;
        }
        p { color: rgba(245,240,232,0.5); font-size:0.95rem; line-height:1.7; margin-bottom:2rem; }
        .btn {
            display: inline-flex;
            background: #c9883a;
            color: #fff;
            padding: 10px 24px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div style="max-width:440px">
        <div class="code">500</div>
        <h1>⚡ Server Error</h1>
        <p>
            Something went wrong on our end. We've been notified
            and are working to fix it. Please try again shortly.
        </p>
        <a href="{{ route('home') }}" class="btn">← Go Home</a>
    </div>
</body>
</html>

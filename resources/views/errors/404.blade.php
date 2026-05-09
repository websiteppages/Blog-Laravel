<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 — Page Not Found</title>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,wght@0,700;1,400&family=Plus+Jakarta+Sans:wght@400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            background: #f7f6f3;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 2rem;
        }
        .content { max-width: 440px; }
        .code {
            font-family: 'Fraunces', serif;
            font-size: clamp(6rem, 20vw, 10rem);
            font-weight: 700;
            color: #ede7d8;
            line-height: 1;
            margin-bottom: 1rem;
            letter-spacing: -0.05em;
        }
        h1 {
            font-family: 'Fraunces', serif;
            font-size: 1.75rem;
            font-weight: 700;
            color: #0d0d0d;
            margin-bottom: 0.75rem;
            letter-spacing: -0.02em;
        }
        p {
            color: #7a7a7a;
            font-size: 0.95rem;
            line-height: 1.7;
            margin-bottom: 2rem;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #0d0d0d;
            color: #f5f0e8;
            padding: 10px 24px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: background 0.15s;
        }
        .btn:hover { background: #1a1a1a; }
        .btn-ghost {
            background: transparent;
            color: #7a7a7a;
            border: 1px solid rgba(13,13,13,0.15);
            margin-left: 8px;
        }
        .btn-ghost:hover { background: rgba(13,13,13,0.04); }
    </style>
</head>
<body>
    <div class="content">
        <div class="code">404</div>
        <h1>Page not found</h1>
        <p>
            The page you're looking for doesn't exist or has been moved.
            Let's get you back on track.
        </p>
        <div>
            <a href="{{ route('home') }}" class="btn">← Back to Home</a>
            <a href="{{ url()->previous() }}" class="btn btn-ghost">Go Back</a>
        </div>
    </div>
</body>
</html>

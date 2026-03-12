<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Socket Notification' }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --bg: #0f1117;
            --surface: #1a1d27;
            --surface2: #22263a;
            --border: #2e3250;
            --accent: #6c63ff;
            --accent-hover: #5a52e0;
            --text: #e8eaf6;
            --text-muted: #8b90b0;
            --green: #4caf82;
            --red: #e05a5a;
            --msg-out: #2d2a4a;
            --msg-in: #1e2235;
        }

        body {
            font-family: 'Segoe UI', system-ui, sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
        }

        /* NAV */
        nav {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            padding: 0 2rem;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .nav-brand {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--accent);
            letter-spacing: 0.5px;
        }

        .nav-brand span { color: var(--text); }

        .nav-user {
            display: flex;
            align-items: center;
            gap: 1rem;
            color: var(--text-muted);
            font-size: 0.875rem;
        }

        .nav-user strong { color: var(--text); }

        .btn-logout {
            background: transparent;
            border: 1px solid var(--border);
            color: var(--text-muted);
            padding: 0.35rem 0.9rem;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.8rem;
            transition: all 0.2s;
        }

        .btn-logout:hover {
            border-color: var(--red);
            color: var(--red);
        }

        /* MAIN */
        main { max-width: 1100px; margin: 0 auto; padding: 2rem 1rem; }

        /* FORMS */
        .auth-wrap {
            max-width: 420px;
            margin: 6rem auto 0;
        }

        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 2.5rem;
        }

        .card h1 {
            font-size: 1.4rem;
            margin-bottom: 2rem;
            color: var(--text);
        }

        .form-group { margin-bottom: 1.2rem; }

        label {
            display: block;
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-bottom: 0.4rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 0.7rem 1rem;
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--text);
            font-size: 0.95rem;
            transition: border-color 0.2s;
        }

        input:focus {
            outline: none;
            border-color: var(--accent);
        }

        .btn {
            width: 100%;
            padding: 0.75rem;
            background: var(--accent);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
            margin-top: 0.5rem;
        }

        .btn:hover { background: var(--accent-hover); }

        .auth-link {
            text-align: center;
            margin-top: 1.2rem;
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        .auth-link a { color: var(--accent); text-decoration: none; }
        .auth-link a:hover { text-decoration: underline; }

        .error {
            background: rgba(224, 90, 90, 0.1);
            border: 1px solid rgba(224, 90, 90, 0.3);
            border-radius: 6px;
            padding: 0.6rem 0.9rem;
            font-size: 0.82rem;
            color: var(--red);
            margin-bottom: 1rem;
        }

        /* BADGE */
        .badge {
            background: var(--accent);
            color: #fff;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 0.15rem 0.5rem;
            border-radius: 99px;
            min-width: 20px;
            text-align: center;
        }

        /* STATUS DOT */
        .dot {
            width: 8px; height: 8px;
            border-radius: 50%;
            background: var(--text-muted);
            display: inline-block;
        }
        .dot.online { background: var(--green); }
    </style>
    @stack('styles')
</head>
<body>

@auth
<nav>
    <div class="nav-brand">Socket<span>Notification</span></div>
    <div class="nav-user">
        <span>Привіт, <strong>{{ auth()->user()->name }}</strong></span>
        <form method="POST" action="{{ route('logout') }}" style="display:inline">
            @csrf
            <button type="submit" class="btn-logout">Вийти</button>
        </form>
    </div>
</nav>
@endauth

<main>
    {{ $slot }}
</main>

@stack('scripts')
</body>
</html>

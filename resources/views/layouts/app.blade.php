<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Campus Event Management System - Discover, register, and manage campus events seamlessly.">
    <title>@yield('title', 'EventKampus - Campus Event Platform')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        /* ============================================
           DESIGN SYSTEM - CSS Custom Properties
           ============================================ */
        :root {
            --bg-primary: #06080f;
            --bg-secondary: #0d1117;
            --bg-tertiary: #161b22;
            --bg-card: rgba(22, 27, 34, 0.6);
            --bg-card-hover: rgba(30, 37, 48, 0.8);
            --border-subtle: rgba(255, 255, 255, 0.06);
            --border-card: rgba(255, 255, 255, 0.08);
            --border-focus: rgba(99, 102, 241, 0.5);
            --border-glow: rgba(99, 102, 241, 0.25);

            --primary: #818cf8;
            --primary-deep: #6366f1;
            --primary-glow: rgba(99, 102, 241, 0.15);
            --accent: #c084fc;
            --accent-deep: #a855f7;
            --accent-glow: rgba(168, 85, 247, 0.15);

            --text-primary: #f0f6fc;
            --text-secondary: #c9d1d9;
            --text-muted: #8b949e;
            --text-faint: #6e7681;

            --success: #3fb950;
            --success-bg: rgba(63, 185, 80, 0.1);
            --success-border: rgba(63, 185, 80, 0.25);
            --warning: #d29922;
            --warning-bg: rgba(210, 153, 34, 0.1);
            --warning-border: rgba(210, 153, 34, 0.25);
            --danger: #f85149;
            --danger-bg: rgba(248, 81, 73, 0.1);
            --danger-border: rgba(248, 81, 73, 0.25);
            --info: #58a6ff;
            --info-bg: rgba(88, 166, 255, 0.1);

            --font-display: 'Outfit', sans-serif;
            --font-body: 'Inter', sans-serif;

            --radius-sm: 6px;
            --radius-md: 10px;
            --radius-lg: 16px;
            --radius-xl: 20px;
            --radius-full: 9999px;

            --shadow-sm: 0 1px 3px rgba(0,0,0,0.3);
            --shadow-md: 0 4px 16px rgba(0,0,0,0.3);
            --shadow-lg: 0 8px 32px rgba(0,0,0,0.4);
            --shadow-glow: 0 0 20px rgba(99,102,241,0.15);

            --transition-fast: 150ms ease;
            --transition-base: 250ms ease;
            --transition-slow: 400ms ease;
        }

        /* ============================================
           RESET & BASE
           ============================================ */
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        html { scroll-behavior: smooth; }

        body {
            background-color: var(--bg-primary);
            color: var(--text-secondary);
            font-family: var(--font-body);
            font-size: 15px;
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background:
                radial-gradient(ellipse 80% 60% at 10% 20%, rgba(99,102,241,0.08) 0%, transparent 60%),
                radial-gradient(ellipse 60% 50% at 90% 80%, rgba(168,85,247,0.06) 0%, transparent 60%);
            pointer-events: none;
            z-index: 0;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: var(--font-display);
            font-weight: 700;
            letter-spacing: -0.025em;
            color: var(--text-primary);
            line-height: 1.2;
        }

        a { color: inherit; text-decoration: none; transition: all var(--transition-base); }
        img { max-width: 100%; display: block; }
        button { font-family: var(--font-body); }

        /* ============================================
           SCROLLBAR
           ============================================ */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: var(--bg-primary); }
        ::-webkit-scrollbar-thumb { background: var(--border-card); border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--text-faint); }

        /* ============================================
           NAVBAR
           ============================================ */
        .navbar {
            background: rgba(6, 8, 15, 0.75);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            border-bottom: 1px solid var(--border-subtle);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar-inner {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 1.5rem;
            height: 64px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            font-family: var(--font-display);
            font-size: 1.35rem;
            font-weight: 800;
            letter-spacing: -0.03em;
        }

        .navbar-brand-icon {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, var(--primary-deep), var(--accent-deep));
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
        }

        .navbar-brand-text {
            background: linear-gradient(135deg, var(--primary), var(--accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .navbar-nav {
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .nav-item {
            padding: 0.5rem 0.85rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--text-muted);
            border-radius: var(--radius-sm);
            transition: all var(--transition-fast);
            white-space: nowrap;
        }

        .nav-item:hover, .nav-item.active {
            color: var(--text-primary);
            background: rgba(255,255,255,0.04);
        }

        .navbar-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .navbar-user-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.35rem 0.75rem;
            background: var(--primary-glow);
            border: 1px solid rgba(99,102,241,0.2);
            border-radius: var(--radius-full);
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--primary);
        }

        .navbar-user-badge .role-dot {
            width: 6px; height: 6px;
            border-radius: 50%;
            background: var(--primary);
        }

        /* Mobile nav toggle */
        .nav-toggle {
            display: none;
            background: none;
            border: 1px solid var(--border-card);
            color: var(--text-secondary);
            padding: 0.4rem 0.6rem;
            border-radius: var(--radius-sm);
            cursor: pointer;
            font-size: 1.1rem;
        }

        @media (max-width: 768px) {
            .nav-toggle { display: flex; }
            .navbar-center {
                display: none;
                position: absolute;
                top: 64px; left: 0; right: 0;
                background: var(--bg-secondary);
                border-bottom: 1px solid var(--border-subtle);
                padding: 1rem;
                flex-direction: column;
                gap: 0.25rem;
            }
            .navbar-center.open { display: flex; }
            .navbar-actions { gap: 0.5rem; }
            .navbar-user-badge { display: none; }
        }

        /* ============================================
           BUTTONS
           ============================================ */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.6rem 1.25rem;
            font-size: 0.875rem;
            font-weight: 600;
            border-radius: var(--radius-md);
            border: none;
            cursor: pointer;
            transition: all var(--transition-base);
            white-space: nowrap;
            text-decoration: none;
            line-height: 1.4;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-deep), var(--accent-deep));
            color: #fff;
            box-shadow: 0 2px 12px rgba(99,102,241,0.25);
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 20px rgba(99,102,241,0.35);
        }

        .btn-primary:active { transform: translateY(0); }

        .btn-primary:disabled, .btn-primary.disabled {
            background: var(--bg-tertiary);
            color: var(--text-faint);
            cursor: not-allowed;
            box-shadow: none;
            transform: none;
        }

        .btn-secondary {
            background: rgba(255,255,255,0.04);
            color: var(--text-secondary);
            border: 1px solid var(--border-card);
        }

        .btn-secondary:hover {
            background: rgba(255,255,255,0.08);
            border-color: var(--text-faint);
            color: var(--text-primary);
        }

        .btn-danger-outline {
            background: transparent;
            border: 1px solid var(--danger-border);
            color: var(--danger);
            padding: 0.45rem 0.85rem;
            font-size: 0.8rem;
        }

        .btn-danger-outline:hover {
            background: var(--danger-bg);
            border-color: var(--danger);
        }

        .btn-sm { padding: 0.4rem 0.85rem; font-size: 0.8rem; }
        .btn-lg { padding: 0.85rem 1.75rem; font-size: 1rem; }
        .btn-block { width: 100%; }

        /* ============================================
           CARDS
           ============================================ */
        .card {
            background: var(--bg-card);
            border: 1px solid var(--border-card);
            border-radius: var(--radius-lg);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            transition: all var(--transition-base);
        }

        .card:hover {
            border-color: var(--border-glow);
            box-shadow: var(--shadow-glow);
        }

        .card-body { padding: 1.5rem; }

        /* ============================================
           BADGES
           ============================================ */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.2rem 0.6rem;
            border-radius: var(--radius-full);
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            border: 1px solid transparent;
        }

        .badge-free { background: var(--success-bg); color: var(--success); border-color: var(--success-border); }
        .badge-paid { background: var(--info-bg); color: var(--info); border-color: rgba(88,166,255,0.25); }
        .badge-full { background: var(--danger-bg); color: var(--danger); border-color: var(--danger-border); }
        .badge-available { background: var(--success-bg); color: var(--success); border-color: var(--success-border); }
        .badge-online { background: rgba(56,189,248,0.1); color: #38bdf8; border-color: rgba(56,189,248,0.25); }
        .badge-offline { background: rgba(251,191,36,0.1); color: #fbbf24; border-color: rgba(251,191,36,0.25); }
        .badge-registered { background: var(--success-bg); color: var(--success); border-color: var(--success-border); }
        .badge-pending { background: var(--warning-bg); color: var(--warning); border-color: var(--warning-border); }
        .badge-cancelled { background: var(--danger-bg); color: var(--danger); border-color: var(--danger-border); }
        .badge-published { background: var(--success-bg); color: var(--success); border-color: var(--success-border); }
        .badge-completed { background: rgba(255,255,255,0.05); color: var(--text-muted); border-color: var(--border-card); }
        .badge-category { background: var(--accent-glow); color: var(--accent); border-color: rgba(168,85,247,0.25); }

        /* ============================================
           ALERTS
           ============================================ */
        .alert {
            padding: 0.85rem 1.25rem;
            border-radius: var(--radius-md);
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            border: 1px solid transparent;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .alert-success { background: var(--success-bg); color: var(--success); border-color: var(--success-border); }
        .alert-danger { background: var(--danger-bg); color: var(--danger); border-color: var(--danger-border); }
        .alert-warning { background: var(--warning-bg); color: var(--warning); border-color: var(--warning-border); }

        /* ============================================
           FORMS
           ============================================ */
        .form-group { margin-bottom: 1.25rem; display: flex; flex-direction: column; gap: 0.4rem; }
        .form-label { font-size: 0.85rem; font-weight: 600; color: var(--text-muted); }

        .form-input {
            background: rgba(255,255,255,0.03);
            border: 1px solid var(--border-card);
            padding: 0.7rem 0.9rem;
            border-radius: var(--radius-md);
            color: var(--text-primary);
            font-family: var(--font-body);
            font-size: 0.9rem;
            transition: all var(--transition-fast);
        }

        .form-input:focus {
            outline: none;
            border-color: var(--border-focus);
            box-shadow: 0 0 0 3px var(--primary-glow);
        }

        .form-input::placeholder { color: var(--text-faint); }

        textarea.form-input { resize: vertical; min-height: 100px; }
        select.form-input { background: var(--bg-tertiary); cursor: pointer; }

        .form-row { display: flex; gap: 1rem; }
        .form-row .form-group { flex: 1; }

        @media (max-width: 640px) {
            .form-row { flex-direction: column; }
        }

        /* ============================================
           TABLES
           ============================================ */
        .table-wrap {
            background: var(--bg-card);
            border: 1px solid var(--border-card);
            border-radius: var(--radius-lg);
            overflow: hidden;
        }

        .table-wrap table { width: 100%; border-collapse: collapse; font-size: 0.9rem; }

        .table-wrap th {
            background: rgba(255,255,255,0.02);
            padding: 0.85rem 1.25rem;
            color: var(--text-muted);
            font-weight: 600;
            border-bottom: 1px solid var(--border-card);
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.06em;
            text-align: left;
        }

        .table-wrap td {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border-subtle);
            color: var(--text-secondary);
            vertical-align: middle;
        }

        .table-wrap tr:last-child td { border-bottom: none; }
        .table-wrap tr:hover td { background: rgba(255,255,255,0.015); }

        /* ============================================
           LAYOUT
           ============================================ */
        .page-container {
            flex: 1;
            position: relative;
            z-index: 1;
        }

        main {
            max-width: 1280px;
            width: 100%;
            margin: 0 auto;
            padding: 2rem 1.5rem;
        }

        /* ============================================
           QUOTA PROGRESS BAR
           ============================================ */
        .quota-bar {
            width: 100%;
            height: 6px;
            background: rgba(255,255,255,0.06);
            border-radius: 3px;
            overflow: hidden;
        }

        .quota-bar-fill {
            height: 100%;
            border-radius: 3px;
            transition: width 0.6s ease;
        }

        .quota-bar-fill.low { background: var(--success); }
        .quota-bar-fill.medium { background: var(--warning); }
        .quota-bar-fill.high { background: var(--danger); }

        /* ============================================
           EMPTY STATE
           ============================================ */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
        }

        .empty-state-icon { font-size: 3.5rem; margin-bottom: 1rem; opacity: 0.6; }
        .empty-state h3 { margin-bottom: 0.5rem; font-size: 1.25rem; }
        .empty-state p { color: var(--text-muted); margin-bottom: 1.5rem; max-width: 400px; margin-left: auto; margin-right: auto; }

        /* ============================================
           FOOTER
           ============================================ */
        footer {
            background: var(--bg-secondary);
            border-top: 1px solid var(--border-subtle);
            padding: 1.5rem;
            text-align: center;
            font-size: 0.8rem;
            color: var(--text-faint);
            margin-top: auto;
            position: relative;
            z-index: 1;
        }

        /* ============================================
           ANIMATIONS
           ============================================ */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-in {
            animation: fadeInUp 0.4s ease forwards;
            opacity: 0;
        }

        .animate-delay-1 { animation-delay: 0.05s; }
        .animate-delay-2 { animation-delay: 0.1s; }
        .animate-delay-3 { animation-delay: 0.15s; }
        .animate-delay-4 { animation-delay: 0.2s; }
    </style>
    @yield('styles')
</head>
<body>

    <nav class="navbar">
        <div class="navbar-inner">
            <a href="/" class="navbar-brand">
                <div class="navbar-brand-icon">🎓</div>
                <span class="navbar-brand-text">EventKampus</span>
            </a>

            <button class="nav-toggle" onclick="document.querySelector('.navbar-center').classList.toggle('open')" aria-label="Toggle navigation">☰</button>

            <div class="navbar-center navbar-nav">
                <a href="{{ route('user.home') }}" class="nav-item {{ request()->routeIs('user.home') ? 'active' : '' }}">Explore Events</a>
                @auth
                    <a href="{{ route('user.registrations') }}" class="nav-item {{ request()->routeIs('user.registrations') ? 'active' : '' }}">My Tickets</a>
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.*') ? 'active' : '' }}">Admin Panel</a>
                    @endif
                @endauth
            </div>

            <div class="navbar-actions">
                @auth
                    <span class="navbar-user-badge">
                        <span class="role-dot"></span>
                        {{ auth()->user()->name }}
                    </span>
                    <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-danger-outline btn-sm">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-secondary btn-sm">Sign In</a>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Sign Up</a>
                @endauth
            </div>
        </div>
    </nav>

    <div class="page-container">
        <main>
            @if(session('success'))
                <div class="alert alert-success">
                    <span>✓</span> {{ session('success') }}
                </div>
            @endif

            @if($errors->has('unauthorized'))
                <div class="alert alert-danger">
                    <span>✕</span> {{ $errors->first('unauthorized') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <footer>
        <p>&copy; {{ date('Y') }} EventKampus — Campus Event Management Platform. All rights reserved.</p>
    </footer>

    @yield('scripts')
</body>
</html>

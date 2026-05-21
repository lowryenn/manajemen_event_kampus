<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Manajemen Event Kampus')</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Custom Premium CSS -->
    <style>
        :root {
            --bg-primary: #0b0f19;
            --bg-secondary: #111827;
            --bg-card: rgba(255, 255, 255, 0.03);
            --border-card: rgba(255, 255, 255, 0.08);
            --border-glow: rgba(99, 102, 241, 0.2);
            --primary: #6366f1;
            --primary-hover: #4f46e5;
            --accent: #d946ef;
            --accent-hover: #c084fc;
            --text-main: #f3f4f6;
            --text-muted: #9ca3af;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --nav-bg: rgba(17, 24, 39, 0.7);
            --font-outfit: 'Outfit', sans-serif;
            --font-inter: 'Inter', sans-serif;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: var(--bg-primary);
            color: var(--text-main);
            font-family: var(--font-inter);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
            background-image: 
                radial-gradient(circle at 10% 20%, rgba(99, 102, 241, 0.15) 0%, transparent 40%),
                radial-gradient(circle at 90% 80%, rgba(217, 70, 239, 0.1) 0%, transparent 40%);
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: var(--font-outfit);
            font-weight: 700;
            letter-spacing: -0.02em;
        }

        a {
            color: inherit;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        /* Navbar */
        nav {
            background-color: var(--nav-bg);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border-card);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-family: var(--font-outfit);
            font-size: 1.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .nav-link {
            font-size: 0.95rem;
            font-weight: 500;
            color: var(--text-muted);
            padding: 0.5rem 0.75rem;
            border-radius: 8px;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--text-main);
            background-color: rgba(255, 255, 255, 0.05);
        }

        .btn-logout {
            background: transparent;
            border: 1px solid rgba(239, 68, 68, 0.4);
            color: #ef4444;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            cursor: pointer;
            font-family: var(--font-inter);
            font-weight: 500;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .btn-logout:hover {
            background-color: rgba(239, 68, 68, 0.1);
            border-color: #ef4444;
        }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--accent));
            color: #fff;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 4px 14px rgba(99, 102, 241, 0.3);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.4);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.05);
            color: var(--text-main);
            border: 1px solid var(--border-card);
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: var(--text-muted);
        }

        /* Container */
        main {
            flex: 1;
            max-width: 1200px;
            width: 100%;
            margin: 0 auto;
            padding: 2.5rem 1.5rem;
        }

        /* Footer */
        footer {
            background-color: var(--bg-secondary);
            border-top: 1px solid var(--border-card);
            padding: 2rem 1.5rem;
            text-align: center;
            font-size: 0.9rem;
            color: var(--text-muted);
            margin-top: auto;
        }

        /* Alerts & Badges */
        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            border: 1px solid transparent;
        }

        .alert-success {
            background-color: rgba(16, 185, 129, 0.1);
            color: var(--success);
            border-color: rgba(16, 185, 129, 0.2);
        }

        .alert-danger {
            background-color: rgba(239, 68, 68, 0.1);
            color: var(--danger);
            border-color: rgba(239, 68, 68, 0.2);
        }

        .badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .badge-registered {
            background-color: rgba(16, 185, 129, 0.15);
            color: var(--success);
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .badge-admin {
            background-color: rgba(217, 70, 239, 0.15);
            color: var(--accent);
            border: 1px solid rgba(217, 70, 239, 0.3);
        }

        .badge-user {
            background-color: rgba(99, 102, 241, 0.15);
            color: var(--primary);
            border: 1px solid rgba(99, 102, 241, 0.3);
        }

        /* Card Elements */
        .card {
            background: var(--bg-card);
            border: 1px solid var(--border-card);
            border-radius: 16px;
            padding: 1.5rem;
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s, border-color 0.3s, box-shadow 0.3s;
        }

        .card:hover {
            border-color: var(--border-glow);
            box-shadow: 0 15px 35px rgba(99, 102, 241, 0.1);
        }
    </style>
    @yield('styles')
</head>
<body>

    <nav>
        <div class="nav-container">
            <a href="/" class="logo">
                <span>🎓</span> EventKampus
            </a>
            
            <div class="nav-links">
                <a href="{{ route('user.home') }}" class="nav-link">Home Event</a>
                <a href="{{ route('user.registrations') }}" class="nav-link">Riwayat Event</a>
                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="nav-link">Dashboard Admin</a>
                        <a href="{{ route('admin.participants') }}" class="nav-link">Daftar Peserta</a>
                    @endif
                    
                    <span class="badge badge-user">{{ auth()->user()->name }} ({{ auth()->user()->role }})</span>
                    
                    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn-logout">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="nav-link">Login</a>
                    <a href="{{ route('register') }}" class="nav-link">Register</a>
                @endauth
            </div>
        </div>
    </nav>

    <main>
        @if(session('success'))
            <div class="alert alert-success">
                <span>✔</span> {{ session('success') }}
            </div>
        @endif

        @if($errors->has('unauthorized'))
            <div class="alert alert-danger">
                <span>❌</span> {{ $errors->first('unauthorized') }}
            </div>
        @endif

        @yield('content')
    </main>

    <footer>
        <p>&copy; 2026 Sistem Manajemen Event Kampus. All rights reserved.</p>
    </footer>

</body>
</html>

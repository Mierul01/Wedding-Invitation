<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#1e3329">
    <title>@yield('title', 'Admin') — Wedding Admin</title>
    <link rel="icon" href="{{ asset('icons/favicon-admin.svg') }}" type="image/svg+xml">
    <link rel="apple-touch-icon" href="{{ asset('icons/favicon-admin.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600&family=Great+Vibes&family=Outfit:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/invitation.css') }}?v=21">
</head>
<body class="admin-body">
    <div class="admin-shell">
        <header class="admin-mobile-bar">
            <button type="button" class="admin-menu-toggle" id="admin-menu-toggle" aria-label="Open menu" aria-expanded="false" aria-controls="admin-sidebar">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
            </button>
            <div class="admin-mobile-bar__brand">Wedding Admin</div>
        </header>

        <div class="admin-backdrop" id="admin-backdrop" aria-hidden="true"></div>

        <aside class="admin-sidebar" id="admin-sidebar">
            <div class="admin-sidebar__head">
                <div class="admin-brand">Wedding Admin</div>
                <button type="button" class="admin-menu-close" id="admin-menu-close" aria-label="Close menu">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                </button>
            </div>
            <nav class="admin-nav" aria-label="Admin navigation">
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
                <a href="{{ route('admin.rsvps.index') }}" class="{{ request()->routeIs('admin.rsvps.*') ? 'active' : '' }}">RSVP List</a>
                <a href="{{ route('admin.settings.edit') }}" class="{{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">Invitation Settings</a>
                <a href="{{ route('invitation.index') }}" target="_blank" rel="noopener">View Invitation</a>
            </nav>
            <form method="POST" action="{{ route('admin.logout') }}" class="admin-logout">
                @csrf
                <button type="submit" class="btn btn-outline btn-sm">Logout</button>
            </form>
        </aside>

        <main class="admin-main">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-error">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const sidebar = document.getElementById('admin-sidebar');
            const toggle = document.getElementById('admin-menu-toggle');
            const closeBtn = document.getElementById('admin-menu-close');
            const backdrop = document.getElementById('admin-backdrop');

            const setOpen = (open) => {
                sidebar?.classList.toggle('is-open', open);
                backdrop?.classList.toggle('is-visible', open);
                document.body.classList.toggle('admin-nav-open', open);
                toggle?.setAttribute('aria-expanded', open ? 'true' : 'false');
                toggle?.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
            };

            toggle?.addEventListener('click', () => setOpen(!sidebar?.classList.contains('is-open')));
            closeBtn?.addEventListener('click', () => setOpen(false));
            backdrop?.addEventListener('click', () => setOpen(false));
            sidebar?.querySelectorAll('.admin-nav a:not([target="_blank"])').forEach((link) => {
                link.addEventListener('click', () => setOpen(false));
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') setOpen(false);
            });
        });
    </script>
</body>
</html>

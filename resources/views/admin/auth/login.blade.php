<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#1e3329">
    <title>Admin Login — Kad Perkahwinan</title>
    <link rel="icon" href="{{ asset('icons/favicon-admin.svg') }}" type="image/svg+xml">
    <link rel="apple-touch-icon" href="{{ asset('icons/favicon-admin.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;1,500&family=Great+Vibes&family=Outfit:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/invitation.css') }}?v=20">
</head>
<body class="login-body">
    <div class="login-page">
        <div class="login-page__backdrop" aria-hidden="true">
            <span class="login-page__orb login-page__orb--1"></span>
            <span class="login-page__orb login-page__orb--2"></span>
            <span class="login-page__orb login-page__orb--3"></span>
        </div>

        <div class="login-shell">
            <aside class="login-brand" aria-label="Kad Perkahwinan">
                <div class="login-brand__mark">
                    <svg viewBox="0 0 48 48" aria-hidden="true">
                        <circle cx="24" cy="24" r="22" fill="none" stroke="currentColor" stroke-width="1.2" opacity="0.35"/>
                        <path d="M24 34s-8-5.5-8-12a4.5 4.5 0 018 0 4.5 4.5 0 018 0c0 6.5-8 12-8 12z" fill="currentColor"/>
                    </svg>
                </div>
                <p class="login-brand__script">Kad Perkahwinan</p>
                <h1 class="login-brand__title">Manage your wedding invitation with ease.</h1>
                <p class="login-brand__text">Update guest details, track RSVPs, and keep your digital kad looking perfect — all in one place.</p>
                <ul class="login-brand__features">
                    <li>
                        <span class="login-brand__feature-icon" aria-hidden="true">✓</span>
                        RSVP tracking &amp; guest limits
                    </li>
                    <li>
                        <span class="login-brand__feature-icon" aria-hidden="true">✓</span>
                        Invitation content &amp; music
                    </li>
                    <li>
                        <span class="login-brand__feature-icon" aria-hidden="true">✓</span>
                        Live preview anytime
                    </li>
                </ul>
            </aside>

            <div class="login-card">
                <header class="login-card__header">
                    <p class="login-card__eyebrow">Admin access</p>
                    <h2 class="login-card__title">Welcome back</h2>
                    <p class="login-card__subtitle">Sign in to continue to your dashboard.</p>
                </header>

                @if($errors->any())
                    <div class="login-alert login-alert--error" role="alert">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9" fill="none" stroke="currentColor" stroke-width="1.6"/><path d="M12 8v5M12 16h.01" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                        <div>
                            @foreach($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                @endif

                <form class="login-form" method="POST" action="{{ route('admin.login.submit') }}" novalidate>
                    @csrf

                    <div class="login-field">
                        <label for="email">Email address</label>
                        <div class="login-field__control">
                            <svg class="login-field__icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M4 6h16v12H4V6z" fill="none" stroke="currentColor" stroke-width="1.5"/><path d="M4 7l8 6 8-6" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                placeholder="admin@wedding.test"
                                autocomplete="email"
                                inputmode="email"
                                required
                                autofocus
                            >
                        </div>
                    </div>

                    <div class="login-field">
                        <label for="password">Password</label>
                        <div class="login-field__control">
                            <svg class="login-field__icon" viewBox="0 0 24 24" aria-hidden="true"><rect x="5" y="11" width="14" height="10" rx="2" fill="none" stroke="currentColor" stroke-width="1.5"/><path d="M8 11V8a4 4 0 118 0v3" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                            <input
                                id="password"
                                type="password"
                                name="password"
                                placeholder="Enter your password"
                                autocomplete="current-password"
                                required
                            >
                            <button type="button" class="login-field__toggle" id="toggle-password" aria-label="Show password" aria-pressed="false">
                                <svg class="login-field__toggle-show" viewBox="0 0 24 24" aria-hidden="true"><path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7-10-7-10-7z" fill="none" stroke="currentColor" stroke-width="1.5"/><circle cx="12" cy="12" r="3" fill="none" stroke="currentColor" stroke-width="1.5"/></svg>
                                <svg class="login-field__toggle-hide" viewBox="0 0 24 24" aria-hidden="true"><path d="M3 3l18 18M10.5 10.7A3 3 0 0012 15a3 3 0 002.3-1M7.2 7.4C5.4 8.6 3.9 10.4 3 12c0 0 4 7 9 7 1.6 0 3.1-.5 4.4-1.3M14.1 6.2C13.4 6.1 12.7 6 12 6 7 6 3 13 3 13a17.8 17.8 0 004.2 4.2" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                            </button>
                        </div>
                    </div>

                    <div class="login-form__row">
                        <label class="login-checkbox">
                            <input type="checkbox" name="remember" value="1" @checked(old('remember'))>
                            <span>Remember me</span>
                        </label>
                    </div>

                    <button type="submit" class="login-submit">
                        <span>Sign in</span>
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </button>
                </form>

                <footer class="login-card__footer">
                    <a href="{{ route('invitation.index') }}" target="_blank" rel="noopener">
                        View public invitation
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M14 5h5v5M10 14L19 5M19 14v5H5V5h5" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </a>
                </footer>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('toggle-password')?.addEventListener('click', function () {
            const input = document.getElementById('password');
            const show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            this.setAttribute('aria-pressed', show ? 'true' : 'false');
            this.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
            this.classList.toggle('is-visible', show);
        });
    </script>
</body>
</html>

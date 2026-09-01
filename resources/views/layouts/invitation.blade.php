<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover, maximum-scale=1">
    <meta name="description" content="Jemputan perkahwinan {{ $wedding->coupleNames() }}">
    <meta name="theme-color" content="#1a2620">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="format-detection" content="telephone=yes">
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $wedding->coupleNames() }} — Jemputan Perkahwinan">
    <meta property="og:description" content="Anda dijemput untuk meraikan hari bahagia kami.">
    <title>{{ $wedding->coupleNames() }} — Jemputan Perkahwinan</title>
    <link rel="icon" href="{{ asset('icons/favicon-invitation.svg') }}" type="image/svg+xml">
    <link rel="apple-touch-icon" href="{{ asset('icons/favicon-invitation.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:ital@0;1&family=Cormorant+Garamond:wght@400;500;600&family=Great+Vibes&family=Montserrat:wght@300;400;500&family=Playfair+Display:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/invitation.css') }}?v=30">
</head>
<body class="invite-body">
    @yield('content')
    <script src="{{ asset('js/invitation.js') }}?v=18"></script>
</body>
</html>

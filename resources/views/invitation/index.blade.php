@extends('layouts.invitation')

@php
    $weddingDate = $wedding->wedding_datetime->copy()->locale('ms');
    $inviteText = $wedding->welcome_message ?: "Dengan penuh kesyukuran, kami menjemput Dato' | Datin | Tuan | Puan | Encik | Cik seisi keluarga hadir ke majlis perkahwinan anakanda kami";
    $parentsLine = trim(($wedding->groom_parents ?? '') . ' & ' . ($wedding->bride_parents ?? ''), ' &');
    $groomFirst = explode(' ', trim($wedding->groom_name))[0];
    $brideFirst = explode(' ', trim($wedding->bride_name))[0];
    $shortBrand = "{$groomFirst} & {$brideFirst}";
    $heroBg = 'https://images.unsplash.com/photo-1519225421980-715cb0215aed?w=1200&q=80';
    $aturCaraBg = (($wedding->gallery_images ?? [])[1] ?? null) ?: 'https://images.unsplash.com/photo-1490750967868-88aa4486c946?w=1200&q=80';
    $eventSchedule = $wedding->event_schedule ?: [
        ['label' => 'Kehadiran tetamu', 'time' => '11:30 AM'],
        ['label' => 'Ketibaan Pengantin', 'time' => '12:30 PM'],
        ['label' => 'Makan Beradab', 'time' => '1:30 PM'],
        ['label' => 'Majlis Berakhir', 'time' => '4:00 PM'],
    ];
    $eventTime = $wedding->reception_time ?: '11:30 AM – 4:00 PM';
    $calendarStart = $wedding->wedding_datetime->copy();
    $calendarEnd = $calendarStart->copy()->addHours(6);
    $googleCalendarUrl = 'https://calendar.google.com/calendar/render?action=TEMPLATE&text='.urlencode($wedding->coupleNames().' — Walimatulurus').'&dates='.$calendarStart->format('Ymd\THis').'/'.$calendarEnd->format('Ymd\THis').'&details='.urlencode($wedding->venue_name).'&location='.urlencode($wedding->venue_address ?? $wedding->venue_name);
@endphp

@section('content')
<div class="invite-app" id="invite-app">
    <div class="invite-entry" id="invite-entry" aria-live="polite">
        <div class="invite-entry__veil" aria-hidden="true"></div>

        <div class="invite-entry__panel">
            <div class="invite-entry__loading" id="invite-entry-loading">
                <div class="invite-entry__spinner" role="status" aria-label="Memuatkan jemputan">
                    <span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span>
                </div>
            </div>

            <div class="invite-entry__gate" id="invite-entry-gate" hidden>
                <p class="invite-entry__kicker">Walimatulurus</p>
                <div class="invite-entry__frame-wrap">
                    <div class="invite-entry__frame" aria-hidden="true">
                        <svg class="invite-entry__hex invite-entry__hex--outer" viewBox="0 0 200 220" aria-hidden="true">
                            <polygon points="100,18 178,58 178,162 100,202 22,162 22,58" fill="none" stroke="currentColor" stroke-width="1.2"/>
                        </svg>
                        <svg class="invite-entry__hex invite-entry__hex--inner" viewBox="0 0 200 220" aria-hidden="true">
                            <polygon points="100,32 164,64 164,156 100,188 36,156 36,64" fill="none" stroke="currentColor" stroke-width="0.9"/>
                        </svg>
                    </div>
                    <h1 class="invite-entry__names">{{ $shortBrand }}</h1>
                </div>
                <button type="button" class="invite-entry__open" id="invite-entry-open">Buka</button>
            </div>
        </div>
    </div>

    <div class="invite-app__content" id="invite-app-content">
    @if($wedding->hasBackgroundMusic())
        <audio id="invite-music" class="invite-music" src="{{ $wedding->backgroundMusicUrl() }}" loop preload="metadata"></audio>
        <button type="button" class="music-toggle" id="music-toggle" aria-label="Mainkan muzik" aria-pressed="false">
            <svg class="music-toggle__icon music-toggle__icon--on" viewBox="0 0 24 24" aria-hidden="true">
                <path d="M9 18V6l10-2v14" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M7 16a2 2 0 100-4 2 2 0 000 4zm12-2a2 2 0 100-4 2 2 0 000 4z" fill="currentColor"/>
            </svg>
            <svg class="music-toggle__icon music-toggle__icon--off" viewBox="0 0 24 24" aria-hidden="true">
                <path d="M9 18V6l10-2v14" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M7 16a2 2 0 100-4 2 2 0 000 4zm12-2a2 2 0 100-4 2 2 0 000 4z" fill="currentColor"/>
                <path d="M4 4l16 16" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
            </svg>
        </button>
    @endif
    <main class="pages" id="invite-scroll">

        {{-- COVER: Main page with couple names --}}
        <section class="invite-section invite-section--cover" id="section-cover" data-section="cover">
            <div class="hero-bg" style="--hero-image: url('{{ $heroBg }}')" aria-hidden="true"></div>
            <div class="hero-overlay" aria-hidden="true"></div>
            <div class="cover-inner">
                <p class="hero-brand">{{ $shortBrand }}</p>

                <div class="cover-main">
                    <div class="cover-kicker">
                        <span class="cover-kicker__line" aria-hidden="true"></span>
                        <span class="cover-kicker__text">Bersama Keluarga</span>
                    </div>

                    <div class="kad-couple kad-couple--cover">
                        <span class="kad-couple__name">{{ $wedding->groom_name }}</span>
                        <span class="kad-couple__amp">&amp;</span>
                        <span class="kad-couple__name">{{ $wedding->bride_name }}</span>
                    </div>

                    <p class="kad-hero-date">{{ $weddingDate->translatedFormat('l, j F Y') }}</p>
                </div>
            </div>
        </section>

        {{-- Walimatulurus --}}
        <section class="invite-section invite-section--walimatulurus" id="section-walimatulurus" data-section="walimatulurus">
            <article class="kad-card">
                <h2 class="kad-title">Walimatulurus</h2>

                @if($parentsLine)
                    <p class="kad-script kad-script--parents">{{ $parentsLine }}</p>
                @endif

                <p class="kad-body">{{ $inviteText }}</p>

                <p class="kad-script kad-script--couple">
                    {{ $wedding->groom_name }}<br>
                    <span class="kad-amp">&amp;</span><br>
                    {{ $wedding->bride_name }}
                </p>

                <div class="kad-divider" aria-hidden="true"></div>

                <div class="kad-detail">
                    <h3>Tempat</h3>
                    <p>{{ $wedding->venue_name }}</p>
                    <p>{{ $wedding->venue_address }}</p>
                </div>

                <div class="kad-detail kad-detail--date">
                    <h3>Tarikh</h3>
                    <p>{{ $weddingDate->translatedFormat('l, j F Y') }}</p>
                    @if($wedding->reception_time)
                        <p class="kad-time">{{ $wedding->reception_time }}</p>
                    @endif
                </div>
            </article>
        </section>

        {{-- Atur Cara Majlis --}}
        <section class="invite-section invite-section--atur-cara" id="section-aturcara" data-section="aturcara">
            <div class="section-bg" style="--section-image: url('{{ $aturCaraBg }}')" aria-hidden="true"></div>
            <div class="section-bg-overlay section-bg-overlay--soft" aria-hidden="true"></div>
            <div class="section-content">
                <div class="atur-cara-box">
                    <h2>Atur Cara Majlis</h2>
                    @foreach($eventSchedule as $item)
                        <div class="atur-cara-item">
                            <strong>{{ $item['label'] ?? '' }}</strong>
                            <span>{{ $item['time'] ?? '' }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- Countdown (last) --}}
        <section class="invite-section invite-section--countdown" id="section-countdown" data-section="countdown">
            <div class="countdown-inner">
                <div class="countdown-intro">
                    <p class="countdown-bismillah" lang="ar">بِسْمِ ٱللَّٰهِ ٱلرَّحْمَٰنِ ٱلرَّحِيمِ</p>
                    <p class="countdown-prayer">Segala puji bagi Allah S.W.T. Selawat dan salam ke atas junjungan kami Nabi Muhammad S.A.W. Ya Allah, limpahkanlah barakah dan rahmatMu ke atas majlis perkahwinan ini.</p>
                </div>

                <div class="countdown-block">
                    <div class="countdown-heading-wrap">
                        <span class="countdown-heading__line" aria-hidden="true"></span>
                        <h2 class="countdown-heading">Menghitung Hari</h2>
                    </div>
                    <div class="countdown-grid" id="countdown-timer" data-target="{{ $wedding->wedding_datetime->toIso8601String() }}">
                        <div class="countdown-item countdown-item--1"><strong id="cd-days">00</strong><span>Hari</span></div>
                        <div class="countdown-item countdown-item--2"><strong id="cd-hours">00</strong><span>Jam</span></div>
                        <div class="countdown-item countdown-item--3"><strong id="cd-minutes">00</strong><span>Minit</span></div>
                        <div class="countdown-item countdown-item--4"><strong id="cd-seconds">00</strong><span>Saat</span></div>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <button type="button" class="fab-gift" data-open-modal="hadiah" aria-label="Idea hadiah">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="3" y="8" width="18" height="13" rx="1" stroke="currentColor" stroke-width="1.5"/><path d="M12 8V21M3 12h18M12 8c-2 0-4-1.5-4-3.5S10 1 12 1s4 1.5 4 3.5S14 8 12 8z" stroke="currentColor" stroke-width="1.5"/></svg>
        Idea Hadiah
    </button>

    <nav class="tab-bar" aria-label="Navigasi">
        <button type="button" class="tab-item" data-open-modal="telefon">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6.6 10.8a15.9 15.9 0 006.6 6.6l2.2-2.2a1 1 0 011-.25 11 11 0 003.5.55 1 1 0 011 1V20a1 1 0 01-1 1A16 16 0 013 4a1 1 0 011-1h3.5a1 1 0 011 1 11 11 0 00.55 3.5 1 1 0 01-.25 1L6.6 10.8z" fill="currentColor"/></svg>
            <span>Telefon</span>
        </button>
        <button type="button" class="tab-item" data-open-modal="lokasi">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5A2.5 2.5 0 1112 6a2.5 2.5 0 010 5.5z" fill="currentColor"/></svg>
            <span>Lokasi</span>
        </button>
        <button type="button" class="tab-item" data-open-modal="rsvp">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 3h10a2 2 0 012 2v14l-7-3-7 3V5a2 2 0 012-2z" fill="currentColor"/></svg>
            <span>RSVP</span>
        </button>
        <button type="button" class="tab-item" data-open-modal="masa">
            <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.8" fill="none"/><path d="M12 7v5l3 2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" fill="none"/></svg>
            <span>Masa</span>
        </button>
    </nav>

    <div
        id="rsvp-modal"
        class="invite-modal rsvp-modal{{ ($errors->any() || session('rsvp_success') || request('rsvp')) ? ' is-open' : '' }}"
        data-modal="rsvp"
        aria-hidden="{{ ($errors->any() || session('rsvp_success') || request('rsvp')) ? 'false' : 'true' }}"
        role="presentation"
    >
        <div class="invite-modal__backdrop" data-close-modal="rsvp" aria-hidden="true"></div>
        <div class="invite-modal__dialog rsvp-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="rsvp-modal-title">
            <button type="button" class="invite-modal__close" data-close-modal="rsvp" aria-label="Tutup">&times;</button>
            <h2 id="rsvp-modal-title" class="rsvp-modal__title">RSVP</h2>

            @if(session('rsvp_success'))
                <div class="alert alert-success">{{ session('rsvp_success') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-error">
                    <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif

            @if(! $wedding->isRsvpOpenForGuests())
                <div class="empty-state"><h3>RSVP Ditutup</h3><p>Penerimaan RSVP telah ditutup buat masa ini.</p></div>
            @else
                <form method="POST" action="{{ route('rsvp.store') }}" class="rsvp-form" novalidate>
                    @csrf
                    <div class="form-group">
                        <label for="guest_name">Nama</label>
                        <input id="guest_name" type="text" name="guest_name" value="{{ old('guest_name') }}" placeholder="Nama anda" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Nombor Telefon</label>
                        <input id="phone" type="tel" name="phone" inputmode="tel" autocomplete="tel" placeholder="No. telefon" value="{{ old('phone') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input id="email" type="email" name="email" inputmode="email" autocomplete="email" placeholder="Email anda" value="{{ old('email') }}">
                    </div>
                    <div class="rsvp-form__row">
                        <div class="form-group" id="guest-count-group">
                            <label for="guest_count">Tetamu</label>
                            <input id="guest_count" type="number" name="guest_count" min="1" max="{{ min(20, max(1, $remainingSeats)) }}" value="{{ old('guest_count', 1) }}" required>
                        </div>
                        <div class="form-group">
                            <label for="attending">Kehadiran</label>
                            <select id="attending" name="attending" required>
                                <option value="1" @selected(old('attending', '1') === '1')>Hadir</option>
                                <option value="0" @selected(old('attending') === '0')>Tidak Hadir</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="message">Ucapan</label>
                        <textarea id="message" name="message" rows="2" placeholder="Ucapan anda (pilihan)">{{ old('message') }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-rsvp-submit">Hantar RSVP</button>
                </form>
            @endif
        </div>
    </div>

    <div id="masa-modal" class="invite-modal masa-modal" data-modal="masa" aria-hidden="true" role="presentation">
        <div class="invite-modal__backdrop" data-close-modal="masa" aria-hidden="true"></div>
        <div class="invite-modal__dialog masa-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="masa-modal-title">
            <button type="button" class="invite-modal__close" data-close-modal="masa" aria-label="Tutup">&times;</button>

            <div class="masa-modal__block">
                <h3 id="masa-modal-title" class="masa-modal__label">Tarikh</h3>
                <p class="masa-modal__value">{{ $weddingDate->translatedFormat('l, j F Y') }}</p>
            </div>

            <div class="masa-modal__block">
                <h3 class="masa-modal__label">Masa</h3>
                <p class="masa-modal__value">{{ $eventTime }}</p>
            </div>

            <div class="masa-modal__actions">
                <a class="btn btn-calendar btn-calendar--google" href="{{ $googleCalendarUrl }}" target="_blank" rel="noopener">
                    <span class="btn-calendar__icon" aria-hidden="true">G</span>
                    Google Calendar
                </a>
                <a class="btn btn-calendar btn-calendar--apple" href="{{ route('invitation.calendar') }}">
                    <span class="btn-calendar__icon" aria-hidden="true">&#63743;</span>
                    Apple Calendar
                </a>
            </div>
        </div>
    </div>

    <div id="telefon-modal" class="invite-modal panel-modal" data-modal="telefon" aria-hidden="true" role="presentation">
        <div class="invite-modal__backdrop" data-close-modal="telefon" aria-hidden="true"></div>
        <div class="invite-modal__dialog panel-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="telefon-modal-title">
            <button type="button" class="invite-modal__close" data-close-modal="telefon" aria-label="Tutup">&times;</button>
            <h2 id="telefon-modal-title" class="panel-modal__title">Telefon</h2>
            <p class="panel-modal__subtitle">Hubungi kami untuk sebarang pertanyaan</p>
            <div class="panel-modal__body">
                @php
                    $phoneContacts = array_values(array_filter([
                        ['name' => $wedding->contact_name, 'phone' => $wedding->contact_phone],
                        ['name' => $wedding->contact_name_2, 'phone' => $wedding->contact_phone_2],
                    ], fn ($contact) => filled($contact['name']) && filled($contact['phone'])));
                @endphp

                @if(count($phoneContacts))
                    <div class="contact-list">
                        @foreach($phoneContacts as $contact)
                            <div class="contact-item">
                                <span class="contact-item__name">{{ $contact['name'] }}</span>
                                <div class="contact-item__actions">
                                    @if($wedding->phoneTelUrl($contact['phone']))
                                        <a class="contact-action contact-action--call" href="{{ $wedding->phoneTelUrl($contact['phone']) }}" aria-label="Panggil {{ $contact['name'] }}">
                                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6.6 10.8a15.9 15.9 0 006.6 6.6l2.2-2.2a1 1 0 011-.25 11 11 0 003.5.55 1 1 0 011 1V20a1 1 0 01-1 1A16 16 0 013 4a1 1 0 011-1h3.5a1 1 0 011 1 11 11 0 00.55 3.5 1 1 0 01-.25 1L6.6 10.8z" fill="currentColor"/></svg>
                                        </a>
                                    @endif
                                    @if($wedding->phoneWhatsAppUrl($contact['phone']))
                                        <a class="contact-action contact-action--whatsapp" href="{{ $wedding->phoneWhatsAppUrl($contact['phone']) }}" target="_blank" rel="noopener" aria-label="WhatsApp {{ $contact['name'] }}">
                                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2C6.5 2 2 6.5 2 12c0 1.9.5 3.7 1.4 5.3L2 22l4.9-1.3A9.9 9.9 0 0012 22c5.5 0 10-4.5 10-10S17.5 2 12 2zm5.2 14.2c-.2.6-1.2 1.1-1.7 1.2-.5.1-1 .2-2.9-.6-2.4-1-4-3.5-4.1-3.7-.1-.2-1-1.3-1-2.5s.6-1.8.9-2c.2-.2.5-.3.7-.3h.5c.2 0 .4 0 .6.5.2.5.7 1.7.8 1.8.1.1.1.3 0 .4-.1.2-.2.3-.3.5-.1.1-.2.2-.1.4.1.2.5.8 1.1 1.3.8.7 1.4.9 1.6 1 .2.1.4.1.5-.1.1-.2.6-.7.7-.9.1-.2.3-.2.5-.1l2.2 1c.2.1.4.2.5.3.1.2.1.8-.1 1.4z" fill="currentColor"/></svg>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div id="lokasi-modal" class="invite-modal panel-modal" data-modal="lokasi" aria-hidden="true" role="presentation">
        <div class="invite-modal__backdrop" data-close-modal="lokasi" aria-hidden="true"></div>
        <div class="invite-modal__dialog panel-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="lokasi-modal-title">
            <button type="button" class="invite-modal__close" data-close-modal="lokasi" aria-label="Tutup">&times;</button>
            <h2 id="lokasi-modal-title" class="panel-modal__title">Lokasi</h2>
            <p class="panel-modal__subtitle">Navigasi ke venue majlis</p>
            <div class="panel-modal__body">
                <h3 class="page-card__title">{{ $wedding->venue_name }}</h3>
                <p class="page-card__text">{{ $wedding->venue_address }}</p>
                @if($wedding->map_embed_url)
                    <iframe class="map-frame map-frame--modal" src="{{ $wedding->map_embed_url }}" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen title="Peta lokasi"></iframe>
                @endif
                @if($wedding->googleMapsUrl() || $wedding->wazeUrl())
                    <div class="lokasi-modal__actions">
                        @if($wedding->googleMapsUrl())
                            <a class="btn btn-map btn-map--google" href="{{ $wedding->googleMapsUrl() }}" target="_blank" rel="noopener">
                                <span class="btn-map__icon" aria-hidden="true">G</span>
                                Google Maps
                            </a>
                        @endif
                        @if($wedding->wazeUrl())
                            <a class="btn btn-map btn-map--waze" href="{{ $wedding->wazeUrl() }}" target="_blank" rel="noopener">
                                <span class="btn-map__icon" aria-hidden="true">W</span>
                                Waze
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div id="hadiah-modal" class="invite-modal panel-modal" data-modal="hadiah" aria-hidden="true" role="presentation">
        <div class="invite-modal__backdrop" data-close-modal="hadiah" aria-hidden="true"></div>
        <div class="invite-modal__dialog panel-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="hadiah-modal-title">
            <button type="button" class="invite-modal__close" data-close-modal="hadiah" aria-label="Tutup">&times;</button>
            <h2 id="hadiah-modal-title" class="panel-modal__title">Idea Hadiah</h2>
            <p class="panel-modal__subtitle">Maklumat hadiah &amp; kod pakaian</p>
            <div class="panel-modal__body">
                <div class="panel-modal__block">
                    <h3 class="page-card__title">Hadiah</h3>
                    <p class="page-card__text pre-line">{{ $wedding->gift_info }}</p>
                </div>
                <div class="panel-modal__block">
                    <h3 class="page-card__title">Kod Pakaian</h3>
                    <p class="page-card__text">{{ $wedding->dress_code }}</p>
                </div>
                @if($wedding->additional_notes)
                    <div class="panel-modal__block">
                        <h3 class="page-card__title">Nota</h3>
                        <p class="page-card__text pre-line">{{ $wedding->additional_notes }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    </div>
</div>
@endsection

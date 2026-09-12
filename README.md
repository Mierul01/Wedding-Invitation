# Kad Perkahwinan — Digital Wedding Invitation

A mobile-first digital wedding invitation built with Laravel. Guests receive an elegant scrollable kad with RSVP, maps, and contact options. Couples manage everything from a simple admin panel.

![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?logo=php&logoColor=white)
![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?logo=laravel&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-green)

---

## Features

### Guest invitation
- **Opening experience** — loading screen, blurred preview, then **Buka** to reveal the full kad
- **Scroll sections** — cover, Walimatulurus, atur cara majlis, countdown
- **Scroll animations** — text fades in as guests move through each section
- **Bottom navigation** — Telefon, Lokasi, RSVP, Masa (modal pop-ups)
- **RSVP form** — name, phone, email, guest count, attendance, ucapan
- **Guest limit** — RSVP closes automatically when seats are full (guests see “RSVP Ditutup”)
- **Lokasi** — embedded map with **Google Maps** and **Waze** links
- **Telefon** — call and WhatsApp buttons per contact
- **Masa** — event date/time with Google Calendar and Apple Calendar (`.ics`)
- **Idea Hadiah** — gift info, dress code, and notes (FAB button)
- **Background music** — optional, admin-configurable upload or URL
- **Malaysian styling** — off-white tones, gold accents, Malay copy

### Admin panel
- Dashboard with guest stats and recent RSVPs
- Edit / delete RSVP submissions
- Manage invitation content (couple, venue, schedule, contacts, gallery URLs)
- Upload background music or paste a direct audio URL
- Set guest limit and open/close RSVP manually
- Mobile-responsive admin UI

---

## Screenshots

| Guest kad | Admin dashboard |
|-----------|-----------------|
| Opening gate with **Buka** | RSVP stats & guest list |

---

## Requirements

- PHP **8.2+**
- [Composer](https://getcomposer.org/)
- SQLite (default) or MySQL

---

## Installation

```bash
# Clone the repository
git clone https://github.com/Mierul01/Wedding-Invitation.git
cd Wedding-Invitation

# Install dependencies
composer install

# Environment
cp .env.example .env
php artisan key:generate

# SQLite (default) — create the database file if it does not exist
# Windows
type nul > database\database.sqlite
# macOS / Linux
# touch database/database.sqlite

# Migrate and seed sample data
php artisan migrate --seed

# Link storage for music uploads
php artisan storage:link

# Run locally
php artisan serve
```

Open:

| Page | URL |
|------|-----|
| Invitation | http://127.0.0.1:8000 |
| Admin login | http://127.0.0.1:8000/admin/login |

### Default admin credentials

| Field | Value |
|-------|-------|
| Email | `admin@wedding.test` |
| Password | `password` |

Change these after first login in production.

---

## Usage

### Customize the invitation

1. Log in at `/admin/login`
2. Go to **Invitation Settings**
3. Update couple names, date, venue, contacts, gallery image URLs, music, and guest limit
4. Click **Save Settings**
5. Use **View Invitation** in the sidebar to preview

### RSVP behaviour

- **Confirmed guests** = sum of `guest_count` where `attending = true`
- When confirmed guests reach **max_guests**, the public RSVP form closes for guests
- Admin can still manage RSVPs and adjust the limit from the dashboard
- Remaining seat counts are shown in admin only, not on the public invitation

### Background music

1. Admin → **Invitation Settings** → **Background Music**
2. Upload an MP3/WAV/OGG file **or** paste a direct audio URL
3. Enable **Play background music on invitation page**
4. Guests tap the music icon (top-right) to play/pause

---

## Project structure

```
app/
├── Http/Controllers/
│   ├── InvitationController.php      # Public invitation & RSVP
│   └── Admin/                        # Dashboard, settings, RSVP CRUD
├── Models/
│   ├── WeddingSetting.php            # Single-row invitation config
│   └── Rsvp.php                      # Guest submissions
resources/views/
├── invitation/index.blade.php        # Full guest UI
├── admin/                            # Admin Blade views
└── layouts/                          # invitation + admin layouts
public/
├── css/invitation.css                # All invitation & admin styles
├── js/invitation.js                  # Modals, scroll, music, animations
└── icons/                            # Favicons
database/migrations/                  # wedding_settings, rsvps tables
```

---

## Tech stack

| Layer | Choice |
|-------|--------|
| Backend | Laravel 12 |
| Database | SQLite (default) |
| Frontend | Blade + custom CSS/JS (no Vite build for invitation UI) |
| Auth | Laravel session auth (admin only) |

---

## Deployment

### Important: Vercel is not supported

This is a **Laravel PHP** app (server, database, sessions, file uploads).  
Vercel expects a frontend build output like `dist` (Vite/Next.js), which is why that deploy fails.

Use **Railway** (recommended) or **Render** instead. This repo includes Docker + deploy configs for both.

### Deploy on Railway (recommended)

1. Push this repo to GitHub (already done: [Mierul01/Wedding-Invitation](https://github.com/Mierul01/Wedding-Invitation))
2. Go to [railway.app](https://railway.app) → **Login with GitHub**
3. **New Project** → **Deploy from GitHub repo** → select `Wedding-Invitation`
4. Add a database: **+ New** → **Database** → **PostgreSQL**
5. Open the **web service** → **Variables** and set:

| Variable | Value |
|----------|--------|
| `APP_NAME` | `Kad Perkahwinan` |
| `APP_ENV` | `production` |
| `APP_DEBUG` | `false` |
| `APP_KEY` | run locally: `php artisan key:generate --show` and paste the result |
| `APP_URL` | your Railway public URL (e.g. `https://wedding-invitation-production.up.railway.app`) |
| `LOG_CHANNEL` | `stderr` |
| `SESSION_DRIVER` | `database` |
| `CACHE_STORE` | `database` |
| `QUEUE_CONNECTION` | `database` |
| `DB_CONNECTION` | `pgsql` |

6. Link Postgres to the web service (Railway usually injects `DATABASE_URL` automatically when you connect the database)
7. **Settings** → **Networking** → **Generate Domain**
8. Update `APP_URL` to that domain, then redeploy

After deploy:

- Invitation: `https://YOUR-DOMAIN/`
- Admin: `https://YOUR-DOMAIN/admin/login`
- Login: `admin@wedding.test` / `password`

### Deploy on Render

1. Go to [render.com](https://render.com) → **New** → **Blueprint**
2. Connect the `Wedding-Invitation` GitHub repo (uses `render.yaml`)
3. Add the same env vars as above (`APP_KEY`, `APP_URL`, etc.)
4. Connect the Postgres instance from the blueprint and set `DATABASE_URL`

### Production notes

- Uploaded music files may not persist on free PaaS disks — prefer a **music URL** in admin settings
- Seeder only runs when the database is empty (safe to redeploy)
- Document root is handled by Docker (`php artisan serve` on `$PORT`)
- Do **not** commit `.env`

---

## License

MIT

---

## Author

[Mierul01](https://github.com/Mierul01) — [Wedding-Invitation](https://github.com/Mierul01/Wedding-Invitation)

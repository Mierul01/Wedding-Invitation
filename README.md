# Kad Perkahwinan — Digital Wedding Invitation

Elegant Laravel wedding invitation website with RSVP management and an admin panel.

## Features

- Beautiful single-page digital invitation (hero, countdown, ceremony/reception, gallery, map, RSVP, notes & contacts)
- Guest RSVP form with attendance, guest count, and wishes
- Automatic confirmed guest totals
- Admin panel to manage invitation details, guest limit, RSVP open/close, and RSVP records
- Guest limit enforcement (cannot confirm more guests than remaining seats)

## Requirements

- PHP 8.2+
- Composer
- SQLite (default) or MySQL

## Setup

```bash
composer install
copy .env.example .env   # if needed
php artisan key:generate
# Ensure database/database.sqlite exists when using SQLite
php artisan migrate --seed
php artisan serve
```

Visit:

- Invitation: http://127.0.0.1:8000
- Admin: http://127.0.0.1:8000/admin/login

### Default admin login

- Email: `admin@wedding.test`
- Password: `password`

## Tech notes

- Frontend uses Blade + custom CSS (no Vite build required for the invitation UI)
- Data stored in `wedding_settings` and `rsvps` tables
- Confirmed guests = sum of `guest_count` where `attending = true`

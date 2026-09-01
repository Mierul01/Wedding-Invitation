<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeddingSetting extends Model
{
    protected $fillable = [
        'groom_name',
        'bride_name',
        'groom_parents',
        'bride_parents',
        'wedding_datetime',
        'ceremony_title',
        'ceremony_time',
        'ceremony_venue',
        'ceremony_address',
        'reception_title',
        'reception_time',
        'reception_venue',
        'reception_address',
        'event_schedule',
        'venue_name',
        'venue_address',
        'map_embed_url',
        'map_link',
        'contact_name',
        'contact_phone',
        'contact_email',
        'contact_name_2',
        'contact_phone_2',
        'dress_code',
        'gift_info',
        'additional_notes',
        'welcome_message',
        'gallery_images',
        'background_music',
        'music_enabled',
        'max_guests',
        'rsvp_open',
    ];

    protected function casts(): array
    {
        return [
            'wedding_datetime' => 'datetime',
            'gallery_images' => 'array',
            'event_schedule' => 'array',
            'music_enabled' => 'boolean',
            'rsvp_open' => 'boolean',
            'max_guests' => 'integer',
        ];
    }

    public static function current(): self
    {
        return static::query()->firstOrFail();
    }

    public function coupleNames(): string
    {
        return "{$this->groom_name} & {$this->bride_name}";
    }

    public function backgroundMusicUrl(): ?string
    {
        if (! $this->music_enabled || blank($this->background_music)) {
            return null;
        }

        if (filter_var($this->background_music, FILTER_VALIDATE_URL)) {
            return $this->background_music;
        }

        return asset('storage/'.ltrim($this->background_music, '/'));
    }

    public function hasBackgroundMusic(): bool
    {
        return $this->backgroundMusicUrl() !== null;
    }

    public function navigationQuery(): ?string
    {
        $query = trim($this->venue_address ?: $this->venue_name ?: '');

        return $query !== '' ? $query : null;
    }

    public function googleMapsUrl(): ?string
    {
        if (filled($this->map_link)) {
            return $this->map_link;
        }

        $query = $this->navigationQuery();

        return $query ? 'https://maps.google.com/?q='.urlencode($query) : null;
    }

    public function wazeUrl(): ?string
    {
        $query = $this->navigationQuery();

        return $query ? 'https://www.waze.com/ul?q='.urlencode($query).'&navigate=yes' : null;
    }

    public function phoneTelUrl(?string $phone): ?string
    {
        if (blank($phone)) {
            return null;
        }

        $normalized = preg_replace('/[^\d+]/', '', $phone);

        return filled($normalized) ? 'tel:'.$normalized : null;
    }

    public function phoneWhatsAppUrl(?string $phone): ?string
    {
        if (blank($phone)) {
            return null;
        }

        $digits = preg_replace('/\D/', '', $phone);

        if ($digits === '') {
            return null;
        }

        if (str_starts_with($digits, '0')) {
            $digits = '60'.substr($digits, 1);
        }

        return 'https://wa.me/'.$digits;
    }

    public function confirmedGuests(): int
    {
        return (int) Rsvp::query()
            ->where('attending', true)
            ->sum('guest_count');
    }

    public function remainingSeats(): int
    {
        return max(0, $this->max_guests - $this->confirmedGuests());
    }

    public function isRsvpOpenForGuests(): bool
    {
        return $this->rsvp_open && $this->remainingSeats() > 0;
    }

    public function canAcceptGuests(int $guestCount, ?int $excludeRsvpId = null): bool
    {
        if (! $this->rsvp_open) {
            return false;
        }

        $confirmed = Rsvp::query()
            ->where('attending', true)
            ->when($excludeRsvpId, fn ($q) => $q->where('id', '!=', $excludeRsvpId))
            ->sum('guest_count');

        return ($confirmed + $guestCount) <= $this->max_guests;
    }
}

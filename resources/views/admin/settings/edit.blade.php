@extends('layouts.admin')

@section('title', 'Settings')

@section('content')
<div class="admin-top">
    <div>
        <h1>Invitation Settings</h1>
        <p style="margin:0.35rem 0 0;color:var(--ink-soft);">Update wedding details, guest limit, and RSVP status.</p>
    </div>
    <form method="POST" action="{{ route('admin.settings.toggle-rsvp') }}">
        @csrf
        <button type="submit" class="btn btn-outline btn-sm">
            {{ $wedding->rsvp_open ? 'Close RSVP' : 'Reopen RSVP' }}
        </button>
    </form>
</div>

<div class="panel">
    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <h2>Couple &amp; Date</h2>
        <div class="settings-grid">
            <div class="form-group">
                <label for="groom_name">Groom Name</label>
                <input id="groom_name" type="text" name="groom_name" value="{{ old('groom_name', $wedding->groom_name) }}" required>
            </div>
            <div class="form-group">
                <label for="bride_name">Bride Name</label>
                <input id="bride_name" type="text" name="bride_name" value="{{ old('bride_name', $wedding->bride_name) }}" required>
            </div>
            <div class="form-group">
                <label for="groom_parents">Groom's Parents</label>
                <input id="groom_parents" type="text" name="groom_parents" value="{{ old('groom_parents', $wedding->groom_parents) }}">
            </div>
            <div class="form-group">
                <label for="bride_parents">Bride's Parents</label>
                <input id="bride_parents" type="text" name="bride_parents" value="{{ old('bride_parents', $wedding->bride_parents) }}">
            </div>
            <div class="form-group">
                <label for="wedding_datetime">Wedding Date &amp; Time</label>
                <input id="wedding_datetime" type="datetime-local" name="wedding_datetime" value="{{ old('wedding_datetime', $wedding->wedding_datetime->format('Y-m-d\TH:i')) }}" required>
            </div>
            <div class="form-group full">
                <label for="welcome_message">Welcome Message</label>
                <textarea id="welcome_message" name="welcome_message" rows="3">{{ old('welcome_message', $wedding->welcome_message) }}</textarea>
            </div>
        </div>

        <h2 style="margin-top:1.5rem;">Ceremony</h2>
        <div class="settings-grid">
            <div class="form-group">
                <label for="ceremony_title">Title</label>
                <input id="ceremony_title" type="text" name="ceremony_title" value="{{ old('ceremony_title', $wedding->ceremony_title) }}">
            </div>
            <div class="form-group">
                <label for="ceremony_time">Time</label>
                <input id="ceremony_time" type="text" name="ceremony_time" value="{{ old('ceremony_time', $wedding->ceremony_time) }}">
            </div>
            <div class="form-group">
                <label for="ceremony_venue">Venue</label>
                <input id="ceremony_venue" type="text" name="ceremony_venue" value="{{ old('ceremony_venue', $wedding->ceremony_venue) }}">
            </div>
            <div class="form-group">
                <label for="ceremony_address">Address</label>
                <input id="ceremony_address" type="text" name="ceremony_address" value="{{ old('ceremony_address', $wedding->ceremony_address) }}">
            </div>
        </div>

        <h2 style="margin-top:1.5rem;">Reception</h2>
        <div class="settings-grid">
            <div class="form-group">
                <label for="reception_title">Title</label>
                <input id="reception_title" type="text" name="reception_title" value="{{ old('reception_title', $wedding->reception_title) }}">
            </div>
            <div class="form-group">
                <label for="reception_time">Time</label>
                <input id="reception_time" type="text" name="reception_time" value="{{ old('reception_time', $wedding->reception_time) }}">
            </div>
            <div class="form-group">
                <label for="reception_venue">Venue</label>
                <input id="reception_venue" type="text" name="reception_venue" value="{{ old('reception_venue', $wedding->reception_venue) }}">
            </div>
            <div class="form-group">
                <label for="reception_address">Address</label>
                <input id="reception_address" type="text" name="reception_address" value="{{ old('reception_address', $wedding->reception_address) }}">
            </div>
        </div>

        <h2 style="margin-top:1.5rem;">Location / Map</h2>
        <div class="settings-grid">
            <div class="form-group">
                <label for="venue_name">Main Venue Name</label>
                <input id="venue_name" type="text" name="venue_name" value="{{ old('venue_name', $wedding->venue_name) }}">
            </div>
            <div class="form-group">
                <label for="venue_address">Main Venue Address</label>
                <input id="venue_address" type="text" name="venue_address" value="{{ old('venue_address', $wedding->venue_address) }}">
            </div>
            <div class="form-group full">
                <label for="map_embed_url">Map Embed URL</label>
                <input id="map_embed_url" type="url" name="map_embed_url" value="{{ old('map_embed_url', $wedding->map_embed_url) }}">
            </div>
            <div class="form-group full">
                <label for="map_link">Google Maps Link</label>
                <input id="map_link" type="url" name="map_link" value="{{ old('map_link', $wedding->map_link) }}">
            </div>
        </div>

        <h2 style="margin-top:1.5rem;">Contact</h2>
        <div class="settings-grid">
            <div class="form-group">
                <label for="contact_name">Contact Name 1</label>
                <input id="contact_name" type="text" name="contact_name" value="{{ old('contact_name', $wedding->contact_name) }}">
            </div>
            <div class="form-group">
                <label for="contact_phone">Contact Phone 1</label>
                <input id="contact_phone" type="text" name="contact_phone" value="{{ old('contact_phone', $wedding->contact_phone) }}">
            </div>
            <div class="form-group">
                <label for="contact_email">Contact Email</label>
                <input id="contact_email" type="email" name="contact_email" value="{{ old('contact_email', $wedding->contact_email) }}">
            </div>
            <div class="form-group">
                <label for="contact_name_2">Contact Name 2</label>
                <input id="contact_name_2" type="text" name="contact_name_2" value="{{ old('contact_name_2', $wedding->contact_name_2) }}">
            </div>
            <div class="form-group">
                <label for="contact_phone_2">Contact Phone 2</label>
                <input id="contact_phone_2" type="text" name="contact_phone_2" value="{{ old('contact_phone_2', $wedding->contact_phone_2) }}">
            </div>
        </div>

        <h2 style="margin-top:1.5rem;">Additional Info</h2>
        <div class="settings-grid">
            <div class="form-group full">
                <label for="dress_code">Dress Code</label>
                <input id="dress_code" type="text" name="dress_code" value="{{ old('dress_code', $wedding->dress_code) }}">
            </div>
            <div class="form-group full">
                <label for="gift_info">Gift Information</label>
                <textarea id="gift_info" name="gift_info" rows="3">{{ old('gift_info', $wedding->gift_info) }}</textarea>
            </div>
            <div class="form-group full">
                <label for="additional_notes">Additional Notes</label>
                <textarea id="additional_notes" name="additional_notes" rows="4">{{ old('additional_notes', $wedding->additional_notes) }}</textarea>
            </div>
            <div class="form-group full">
                <label for="gallery_images">Gallery Image URLs (one per line)</label>
                <textarea id="gallery_images" name="gallery_images" rows="6">{{ old('gallery_images', implode("\n", $wedding->gallery_images ?? [])) }}</textarea>
            </div>
        </div>

        <h2 style="margin-top:1.5rem;">Background Music</h2>
        <div class="settings-grid">
            <div class="form-group full">
                <label class="checkbox-row">
                    <input type="checkbox" name="music_enabled" value="1" @checked(old('music_enabled', $wedding->music_enabled))>
                    Play background music on invitation page
                </label>
            </div>
            <div class="form-group full">
                <label for="background_music_file">Upload Music File</label>
                <input id="background_music_file" type="file" name="background_music_file" accept="audio/mpeg,audio/mp3,audio/wav,audio/ogg,audio/mp4,audio/aac,.mp3,.wav,.ogg,.m4a,.aac">
                <small style="color:var(--ink-soft);font-size:0.82rem;">Supported: MP3, WAV, OGG, M4A, AAC (max 15 MB)</small>
            </div>
            <div class="form-group full">
                <label for="background_music_url">Or Music URL</label>
                <input id="background_music_url" type="url" name="background_music_url" value="{{ old('background_music_url', filter_var($wedding->background_music, FILTER_VALIDATE_URL) ? $wedding->background_music : '') }}" placeholder="https://example.com/music.mp3">
                <small style="color:var(--ink-soft);font-size:0.82rem;">Use a direct link if you prefer not to upload a file.</small>
            </div>
            @if($wedding->background_music)
                <div class="form-group full">
                    <label>Current Music</label>
                    @if($wedding->backgroundMusicUrl())
                        <audio controls style="width:100%;max-width:420px;margin-top:0.35rem;">
                            <source src="{{ $wedding->backgroundMusicUrl() }}">
                        </audio>
                    @endif
                    <label class="checkbox-row" style="margin-top:0.65rem;">
                        <input type="checkbox" name="remove_background_music" value="1" @checked(old('remove_background_music'))>
                        Remove current music
                    </label>
                </div>
            @endif
        </div>

        <h2 style="margin-top:1.5rem;">RSVP Controls</h2>
        <div class="settings-grid">
            <div class="form-group">
                <label for="max_guests">Maximum Guest Limit</label>
                <input id="max_guests" type="number" name="max_guests" min="1" value="{{ old('max_guests', $wedding->max_guests) }}" required>
                <small style="color:var(--ink-soft);font-size:0.82rem;">Currently confirmed: {{ $wedding->confirmedGuests() }} guests</small>
            </div>
            <div class="form-group">
                <label>RSVP Status</label>
                <label class="checkbox-row">
                    <input type="checkbox" name="rsvp_open" value="1" @checked(old('rsvp_open', $wedding->rsvp_open))>
                    Accepting RSVP submissions
                </label>
            </div>
        </div>

        <div class="actions" style="margin-top:1.25rem;">
            <button type="submit" class="btn btn-solid">Save Settings</button>
        </div>
    </form>
</div>
@endsection

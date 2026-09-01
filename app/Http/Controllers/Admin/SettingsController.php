<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WeddingSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function edit(): View
    {
        $wedding = WeddingSetting::current();

        return view('admin.settings.edit', compact('wedding'));
    }

    public function update(Request $request): RedirectResponse
    {
        $wedding = WeddingSetting::current();

        $validated = $request->validate([
            'groom_name' => ['required', 'string', 'max:100'],
            'bride_name' => ['required', 'string', 'max:100'],
            'groom_parents' => ['nullable', 'string', 'max:255'],
            'bride_parents' => ['nullable', 'string', 'max:255'],
            'wedding_datetime' => ['required', 'date'],
            'ceremony_title' => ['nullable', 'string', 'max:100'],
            'ceremony_time' => ['nullable', 'string', 'max:50'],
            'ceremony_venue' => ['nullable', 'string', 'max:150'],
            'ceremony_address' => ['nullable', 'string', 'max:500'],
            'reception_title' => ['nullable', 'string', 'max:100'],
            'reception_time' => ['nullable', 'string', 'max:50'],
            'reception_venue' => ['nullable', 'string', 'max:150'],
            'reception_address' => ['nullable', 'string', 'max:500'],
            'venue_name' => ['nullable', 'string', 'max:150'],
            'venue_address' => ['nullable', 'string', 'max:500'],
            'map_embed_url' => ['nullable', 'url', 'max:1000'],
            'map_link' => ['nullable', 'url', 'max:500'],
            'contact_name' => ['nullable', 'string', 'max:100'],
            'contact_phone' => ['nullable', 'string', 'max:30'],
            'contact_email' => ['nullable', 'email', 'max:150'],
            'contact_name_2' => ['nullable', 'string', 'max:100'],
            'contact_phone_2' => ['nullable', 'string', 'max:30'],
            'dress_code' => ['nullable', 'string', 'max:150'],
            'gift_info' => ['nullable', 'string', 'max:1000'],
            'additional_notes' => ['nullable', 'string', 'max:2000'],
            'welcome_message' => ['nullable', 'string', 'max:1000'],
            'gallery_images' => ['nullable', 'string'],
            'background_music_url' => ['nullable', 'url', 'max:1000'],
            'background_music_file' => ['nullable', 'file', 'mimes:mp3,wav,ogg,m4a,aac', 'max:15360'],
            'remove_background_music' => ['nullable', 'boolean'],
            'music_enabled' => ['nullable', 'boolean'],
            'max_guests' => ['required', 'integer', 'min:1', 'max:10000'],
            'rsvp_open' => ['nullable', 'boolean'],
        ]);

        $gallery = collect(preg_split('/\r\n|\r|\n/', $validated['gallery_images'] ?? ''))
            ->map(fn ($line) => trim($line))
            ->filter()
            ->values()
            ->all();

        $confirmed = $wedding->confirmedGuests();
        if ((int) $validated['max_guests'] < $confirmed) {
            return back()
                ->withInput()
                ->withErrors([
                    'max_guests' => "Guest limit cannot be lower than currently confirmed guests ({$confirmed}).",
                ]);
        }

        $backgroundMusic = $wedding->background_music;

        if ($request->boolean('remove_background_music')) {
            $this->deleteStoredMusic($backgroundMusic);
            $backgroundMusic = null;
        } elseif ($request->hasFile('background_music_file')) {
            $this->deleteStoredMusic($backgroundMusic);
            $backgroundMusic = $request->file('background_music_file')->store('wedding-music', 'public');
        } elseif (filled($validated['background_music_url'] ?? null)) {
            $this->deleteStoredMusic($backgroundMusic);
            $backgroundMusic = $validated['background_music_url'];
        }

        $wedding->update([
            ...collect($validated)->except([
                'gallery_images',
                'rsvp_open',
                'background_music_url',
                'background_music_file',
                'remove_background_music',
                'music_enabled',
            ])->all(),
            'gallery_images' => $gallery,
            'background_music' => $backgroundMusic,
            'music_enabled' => $request->boolean('music_enabled'),
            'rsvp_open' => $request->boolean('rsvp_open'),
        ]);

        return redirect()
            ->route('admin.settings.edit')
            ->with('success', 'Wedding invitation details updated successfully.');
    }

    public function toggleRsvp(): RedirectResponse
    {
        $wedding = WeddingSetting::current();
        $wedding->update(['rsvp_open' => ! $wedding->rsvp_open]);

        $status = $wedding->rsvp_open ? 'opened' : 'closed';

        return back()->with('success', "RSVP submissions have been {$status}.");
    }

    private function deleteStoredMusic(?string $path): void
    {
        if (blank($path) || filter_var($path, FILTER_VALIDATE_URL)) {
            return;
        }

        Storage::disk('public')->delete($path);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Rsvp;
use App\Models\WeddingSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class InvitationController extends Controller
{
    public function index(): View
    {
        $wedding = WeddingSetting::current();
        $remainingSeats = $wedding->remainingSeats();

        return view('invitation.index', compact('wedding', 'remainingSeats'));
    }

    public function storeRsvp(Request $request): RedirectResponse
    {
        $wedding = WeddingSetting::current();

        if (! $wedding->isRsvpOpenForGuests()) {
            return back()
                ->withInput()
                ->withErrors(['rsvp' => 'RSVP ditutup buat masa ini. Terima kasih.']);
        }

        $validated = $request->validate([
            'guest_name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:150'],
            'attending' => ['required', 'in:1,0'],
            'guest_count' => ['required', 'integer', 'min:0', 'max:20'],
            'message' => ['nullable', 'string', 'max:1000'],
        ], [
            'guest_name.required' => 'Sila masukkan nama anda.',
            'phone.required' => 'Sila masukkan nombor telefon.',
            'email.email' => 'Sila masukkan emel yang sah.',
            'attending.required' => 'Sila sahkan kehadiran.',
            'guest_count.required' => 'Sila masukkan bilangan tetamu.',
            'guest_count.min' => 'Minimum 1 tetamu diperlukan.',
        ]);

        $attending = (bool) (int) $validated['attending'];
        $guestCount = $attending ? max(1, (int) $validated['guest_count']) : 0;

        if ($attending && $guestCount < 1) {
            return back()
                ->withInput()
                ->withErrors(['guest_count' => 'Sila masukkan sekurang-kurangnya 1 tetamu jika hadir.']);
        }

        if ($attending && ! $wedding->canAcceptGuests($guestCount)) {
            $remaining = $wedding->remainingSeats();

            return back()
                ->withInput()
                ->withErrors([
                    'guest_count' => $remaining === 0
                        ? 'Maaf, semua tempat duduk telah penuh.'
                        : "Hanya {$remaining} tempat duduk lagi. Sila kurangkan bilangan tetamu.",
                ]);
        }

        Rsvp::create([
            'guest_name' => $validated['guest_name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'] ?? null,
            'attending' => $attending,
            'guest_count' => $attending ? $guestCount : 0,
            'message' => $validated['message'] ?? null,
        ]);

        $message = $attending
            ? 'Terima kasih! RSVP anda telah diterima. Kami tidak sabar menunggu hari bahagia bersama anda.'
            : 'Terima kasih kerana memaklumkan kepada kami. Kami akan merindui kehadiran anda.';

        return redirect()
            ->to(url('/').'?rsvp=1')
            ->with('rsvp_success', $message);
    }

    public function calendar(): Response
    {
        $wedding = WeddingSetting::current();
        $start = $wedding->wedding_datetime->copy()->utc();
        $end = $start->copy()->addHours(6);

        $summary = $wedding->coupleNames().' — Walimatulurus';
        $location = $wedding->venue_name.($wedding->venue_address ? ', '.$wedding->venue_address : '');

        $ics = implode("\r\n", [
            'BEGIN:VCALENDAR',
            'VERSION:2.0',
            'PRODID:-//Kad Perkahwinan//EN',
            'CALSCALE:GREGORIAN',
            'METHOD:PUBLISH',
            'BEGIN:VEVENT',
            'UID:walimatulurus-'.$wedding->id.'@kadperkahwinan',
            'DTSTAMP:'.$start->format('Ymd\THis\Z'),
            'DTSTART:'.$start->format('Ymd\THis\Z'),
            'DTEND:'.$end->format('Ymd\THis\Z'),
            'SUMMARY:'.$this->escapeIcs($summary),
            'LOCATION:'.$this->escapeIcs($location),
            'END:VEVENT',
            'END:VCALENDAR',
        ])."\r\n";

        return response($ics, 200, [
            'Content-Type' => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="walimatulurus.ics"',
        ]);
    }

    private function escapeIcs(string $value): string
    {
        return str_replace(["\r", "\n", ',', ';'], ['', '\n', '\,', '\;'], $value);
    }
}

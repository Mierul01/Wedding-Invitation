<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rsvp;
use App\Models\WeddingSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RsvpController extends Controller
{
    public function index(Request $request): View
    {
        $wedding = WeddingSetting::current();
        $filter = $request->query('filter', 'all');

        $query = Rsvp::query()->latest();

        if ($filter === 'attending') {
            $query->where('attending', true);
        } elseif ($filter === 'declined') {
            $query->where('attending', false);
        }

        if ($search = $request->query('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('guest_name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $rsvps = $query->paginate(15)->withQueryString();

        return view('admin.rsvps.index', [
            'wedding' => $wedding,
            'rsvps' => $rsvps,
            'filter' => $filter,
            'search' => $search ?? '',
            'confirmedGuests' => $wedding->confirmedGuests(),
            'remainingSeats' => $wedding->remainingSeats(),
        ]);
    }

    public function edit(Rsvp $rsvp): View
    {
        $wedding = WeddingSetting::current();

        return view('admin.rsvps.edit', compact('rsvp', 'wedding'));
    }

    public function update(Request $request, Rsvp $rsvp): RedirectResponse
    {
        $wedding = WeddingSetting::current();

        $validated = $request->validate([
            'guest_name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:30'],
            'attending' => ['required', 'in:1,0'],
            'guest_count' => ['required', 'integer', 'min:0', 'max:20'],
            'message' => ['nullable', 'string', 'max:1000'],
        ]);

        $attending = (bool) (int) $validated['attending'];
        $guestCount = $attending ? max(1, (int) $validated['guest_count']) : 0;

        if ($attending && ! $wedding->canAcceptGuests($guestCount, $rsvp->id)) {
            $remaining = $wedding->remainingSeats();
            // When editing, remaining seats exclude current record's count already via canAcceptGuests
            $currentContribution = $rsvp->attending ? $rsvp->guest_count : 0;
            $effectiveRemaining = $remaining + $currentContribution;

            return back()
                ->withInput()
                ->withErrors([
                    'guest_count' => "Cannot update: only {$effectiveRemaining} seat(s) available with the current guest limit.",
                ]);
        }

        $rsvp->update([
            'guest_name' => $validated['guest_name'],
            'phone' => $validated['phone'],
            'attending' => $attending,
            'guest_count' => $guestCount,
            'message' => $validated['message'] ?? null,
        ]);

        return redirect()
            ->route('admin.rsvps.index')
            ->with('success', 'RSVP updated successfully.');
    }

    public function destroy(Rsvp $rsvp): RedirectResponse
    {
        $rsvp->delete();

        return redirect()
            ->route('admin.rsvps.index')
            ->with('success', 'RSVP deleted successfully.');
    }
}

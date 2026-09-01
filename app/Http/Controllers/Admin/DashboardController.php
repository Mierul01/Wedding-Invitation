<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rsvp;
use App\Models\WeddingSetting;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $wedding = WeddingSetting::current();
        $confirmedGuests = $wedding->confirmedGuests();
        $remainingSeats = $wedding->remainingSeats();
        $totalSubmissions = Rsvp::count();
        $attendingCount = Rsvp::attending()->count();
        $declinedCount = Rsvp::where('attending', false)->count();
        $recentRsvps = Rsvp::latest()->take(8)->get();

        return view('admin.dashboard', compact(
            'wedding',
            'confirmedGuests',
            'remainingSeats',
            'totalSubmissions',
            'attendingCount',
            'declinedCount',
            'recentRsvps'
        ));
    }
}

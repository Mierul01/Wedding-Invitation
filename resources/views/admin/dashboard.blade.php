@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="admin-top">
    <div>
        <h1>Dashboard</h1>
        <p style="margin:0.35rem 0 0;color:var(--ink-soft);">{{ $wedding->coupleNames() }} · {{ $wedding->wedding_datetime->format('F j, Y') }}</p>
    </div>
    <div class="actions">
        <span class="badge {{ $wedding->rsvp_open ? 'badge-open' : 'badge-closed' }}">
            RSVP {{ $wedding->rsvp_open ? 'Open' : 'Closed' }}
        </span>
        <form method="POST" action="{{ route('admin.settings.toggle-rsvp') }}">
            @csrf
            <button type="submit" class="btn btn-outline btn-sm">
                {{ $wedding->rsvp_open ? 'Close RSVP' : 'Reopen RSVP' }}
            </button>
        </form>
    </div>
</div>

<div class="stat-grid stat-grid--overview">
    <div class="stat-card">
        <span>Confirmed Guests</span>
        <strong>{{ $confirmedGuests }}</strong>
    </div>
    <div class="stat-card">
        <span>Remaining Seats</span>
        <strong>{{ $remainingSeats }}</strong>
    </div>
    <div class="stat-card">
        <span>Guest Limit</span>
        <strong>{{ $wedding->max_guests }}</strong>
    </div>
    <div class="stat-card">
        <span>Total Submissions</span>
        <strong>{{ $totalSubmissions }}</strong>
    </div>
    <div class="stat-card">
        <span>Attending Responses</span>
        <strong>{{ $attendingCount }}</strong>
    </div>
    <div class="stat-card">
        <span>Declined Responses</span>
        <strong>{{ $declinedCount }}</strong>
    </div>
</div>

<div class="panel">
    <div class="admin-top" style="margin-bottom:0.75rem;">
        <h2 style="margin:0;">Recent RSVPs</h2>
        <a href="{{ route('admin.rsvps.index') }}" class="btn btn-outline btn-sm">View All</a>
    </div>
    <div class="table-wrap table-wrap--cards">
        <table class="data">
            <thead>
                <tr>
                    <th>Guest</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <th>Guests</th>
                    <th>Submitted</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentRsvps as $rsvp)
                    <tr>
                        <td data-label="Guest">{{ $rsvp->guest_name }}</td>
                        <td data-label="Phone">{{ $rsvp->phone }}</td>
                        <td data-label="Status">
                            <span class="badge {{ $rsvp->attending ? 'badge-yes' : 'badge-no' }}">
                                {{ $rsvp->attending ? 'Attending' : 'Declined' }}
                            </span>
                        </td>
                        <td data-label="Guests">{{ $rsvp->guest_count }}</td>
                        <td data-label="Submitted">{{ $rsvp->created_at->format('M j, Y g:i A') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">No RSVPs yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

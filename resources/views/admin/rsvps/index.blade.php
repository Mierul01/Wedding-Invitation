@extends('layouts.admin')

@section('title', 'RSVPs')

@section('content')
<div class="admin-top">
    <div>
        <h1>RSVP Submissions</h1>
        <p style="margin:0.35rem 0 0;color:var(--ink-soft);">
            Confirmed guests: <strong>{{ $confirmedGuests }}</strong> ·
            Remaining: <strong>{{ $remainingSeats }}</strong> ·
            Limit: <strong>{{ $wedding->max_guests }}</strong>
        </p>
    </div>
</div>

<div class="panel">
    <form class="toolbar" method="GET" action="{{ route('admin.rsvps.index') }}">
        <input type="search" name="q" value="{{ $search }}" placeholder="Search name or phone...">
        <select name="filter">
            <option value="all" @selected($filter === 'all')>All</option>
            <option value="attending" @selected($filter === 'attending')>Attending</option>
            <option value="declined" @selected($filter === 'declined')>Declined</option>
        </select>
        <button type="submit" class="btn btn-solid btn-sm">Filter</button>
    </form>

    <div class="table-wrap table-wrap--cards">
        <table class="data">
            <thead>
                <tr>
                    <th>Guest Name</th>
                    <th>Phone</th>
                    <th>Attendance</th>
                    <th>Guests</th>
                    <th>Message</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rsvps as $rsvp)
                    <tr>
                        <td data-label="Guest Name">{{ $rsvp->guest_name }}</td>
                        <td data-label="Phone">{{ $rsvp->phone }}</td>
                        <td data-label="Attendance">
                            <span class="badge {{ $rsvp->attending ? 'badge-yes' : 'badge-no' }}">
                                {{ $rsvp->attending ? 'Yes' : 'No' }}
                            </span>
                        </td>
                        <td data-label="Guests">{{ $rsvp->guest_count }}</td>
                        <td data-label="Message">{{ \Illuminate\Support\Str::limit($rsvp->message, 80) }}</td>
                        <td data-label="Date">{{ $rsvp->created_at->format('M j, Y') }}</td>
                        <td data-label="Actions">
                            <div class="actions">
                                <a href="{{ route('admin.rsvps.edit', $rsvp) }}" class="btn btn-outline btn-sm">Edit</a>
                                <form method="POST" action="{{ route('admin.rsvps.destroy', $rsvp) }}" onsubmit="return confirm('Delete this RSVP?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">No RSVP records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination">
        {{ $rsvps->links('pagination::simple-default') }}
    </div>
</div>
@endsection

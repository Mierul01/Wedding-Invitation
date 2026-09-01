@extends('layouts.admin')

@section('title', 'Edit RSVP')

@section('content')
<div class="admin-top">
    <h1>Edit RSVP</h1>
    <a href="{{ route('admin.rsvps.index') }}" class="btn btn-outline btn-sm">Back to list</a>
</div>

<div class="panel" style="max-width:720px;">
    <form method="POST" action="{{ route('admin.rsvps.update', $rsvp) }}">
        @csrf
        @method('PUT')

        <div class="settings-grid">
            <div class="form-group">
                <label for="guest_name">Guest Name</label>
                <input id="guest_name" type="text" name="guest_name" value="{{ old('guest_name', $rsvp->guest_name) }}" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone</label>
                <input id="phone" type="text" name="phone" value="{{ old('phone', $rsvp->phone) }}" required>
            </div>
            <div class="form-group">
                <label for="attending">Attendance</label>
                <select id="attending" name="attending" required>
                    <option value="1" @selected(old('attending', $rsvp->attending ? '1' : '0') === '1')>Yes — Attending</option>
                    <option value="0" @selected(old('attending', $rsvp->attending ? '1' : '0') === '0')>No — Declined</option>
                </select>
            </div>
            <div class="form-group">
                <label for="guest_count">Number of Guests</label>
                <input id="guest_count" type="number" name="guest_count" min="0" max="20" value="{{ old('guest_count', $rsvp->guest_count) }}" required>
                <small style="color:var(--ink-soft);font-size:0.82rem;">Remaining seats (excluding this record): {{ $wedding->remainingSeats() + ($rsvp->attending ? $rsvp->guest_count : 0) }}</small>
            </div>
            <div class="form-group full">
                <label for="message">Message</label>
                <textarea id="message" name="message" rows="4">{{ old('message', $rsvp->message) }}</textarea>
            </div>
        </div>

        <div class="actions" style="margin-top:1rem;">
            <button type="submit" class="btn btn-solid">Save Changes</button>
            <a href="{{ route('admin.rsvps.index') }}" class="btn btn-outline">Cancel</a>
        </div>
    </form>
</div>
@endsection

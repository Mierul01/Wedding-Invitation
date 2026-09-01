<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rsvp extends Model
{
    protected $fillable = [
        'guest_name',
        'phone',
        'email',
        'attending',
        'guest_count',
        'message',
    ];

    protected function casts(): array
    {
        return [
            'attending' => 'boolean',
            'guest_count' => 'integer',
        ];
    }

    public function scopeAttending($query)
    {
        return $query->where('attending', true);
    }

    public static function totalConfirmedGuests(): int
    {
        return (int) static::attending()->sum('guest_count');
    }
}

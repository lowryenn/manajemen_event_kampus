<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'organizer_id',
        'name',
        'description',
        'type',
        'location',
        'date',
        'price',
        'quota',
    ];

    protected $casts = [
        'date' => 'datetime',
        'price' => 'integer',
        'quota' => 'integer',
    ];

    /**
     * Get the organizer of the event.
     */
    public function organizer()
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    /**
     * Get registrations for this event.
     */
    public function registrations()
    {
        return $this->hasMany(Registration::class, 'event_id');
    }
}

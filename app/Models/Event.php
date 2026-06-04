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

    /**
     * Get active (non-cancelled) registration count.
     */
    public function getRegisteredCountAttribute(): int
    {
        return $this->registrations()->where('status', '!=', 'cancelled')->count();
    }

    /**
     * Calculate remaining quota dynamically.
     */
    public function getRemainingQuotaAttribute(): int
    {
        return max(0, $this->quota - $this->registered_count);
    }

    /**
     * Check if event is fully booked.
     */
    public function getIsFullAttribute(): bool
    {
        return $this->remaining_quota <= 0;
    }

    /**
     * Get ticket type label (FREE or PAID).
     */
    public function getTicketTypeAttribute(): string
    {
        return $this->price == 0 ? 'FREE' : 'PAID';
    }

    /**
     * Auto-detect category from event name keywords.
     * Since we cannot modify DB schema, category is derived.
     */
    public function getCategoryAttribute(): string
    {
        $name = strtolower($this->name ?? '');
        $map = [
            'seminar'    => 'Seminar',
            'workshop'   => 'Workshop',
            'hackathon'  => 'Hackathon',
            'webinar'    => 'Webinar',
            'kompetisi'  => 'Competition',
            'competition'=> 'Competition',
            'konferensi' => 'Conference',
            'conference' => 'Conference',
            'bootcamp'   => 'Bootcamp',
            'talk'       => 'Tech Talk',
            'meetup'     => 'Meetup',
            'festival'   => 'Festival',
            'lomba'      => 'Competition',
        ];

        foreach ($map as $keyword => $category) {
            if (str_contains($name, $keyword)) {
                return $category;
            }
        }

        return 'General';
    }

    /**
     * Derive event status from date context.
     * Cannot store in DB, so computed from date.
     */
    public function getStatusLabelAttribute(): string
    {
        if ($this->date && $this->date->isPast()) {
            return 'completed';
        }
        return 'published';
    }

    /**
     * Get category icon emoji.
     */
    public function getCategoryIconAttribute(): string
    {
        $icons = [
            'Seminar'     => '🎤',
            'Workshop'    => '🛠',
            'Hackathon'   => '💻',
            'Webinar'     => '🌐',
            'Competition' => '🏆',
            'Conference'  => '📋',
            'Bootcamp'    => '🚀',
            'Tech Talk'   => '💡',
            'Meetup'      => '🤝',
            'Festival'    => '🎉',
            'General'     => '📅',
        ];

        return $icons[$this->category] ?? '📅';
    }
}

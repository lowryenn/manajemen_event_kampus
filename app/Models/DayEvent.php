<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DayEvent extends Model
{
    use HasFactory;

    protected $table = 'day_events';

    protected $fillable = [
        'event_id',
        'event_date',
        'start_time',
        'end_time',
    ];

    protected $casts = [
        'event_date' => 'date',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'day_event_id');
    }
}

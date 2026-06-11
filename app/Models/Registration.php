<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;

    protected $table = 'registrations';

    protected $fillable = [
        'user_id',
        'event_id',
        'status',
        'payment_method',
    ];

    /**
     * Get the user who registered.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the event registered for.
     */
    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    /**
     * Get the automatically generated ticket code.
     */
    public function getTicketCodeAttribute(): string
    {
        return 'TCK-' . strtoupper(substr(md5($this->id . $this->event_id), 0, 8));
    }
}

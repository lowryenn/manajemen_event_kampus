<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $table = 'tickets';

    protected $fillable = [
        'order_item_id',
        'day_event_id',
        'ticket_code',
        'qr_code',
        'status',
        'used_at',
    ];

    protected $casts = [
        'used_at' => 'datetime',
    ];

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class, 'order_item_id');
    }

    public function dayEvent()
    {
        return $this->belongsTo(DayEvent::class, 'day_event_id');
    }

    // --- COMPATIBILITY GETTERS (acting as shortcuts to behave like the legacy Registration model) ---

    public function getUserAttribute()
    {
        if ($this->relationLoaded('user')) {
            return $this->getRelation('user');
        }
        return $this->orderItem->order->user ?? null;
    }

    public function getEventAttribute()
    {
        if ($this->relationLoaded('event')) {
            return $this->getRelation('event');
        }
        return $this->dayEvent->event ?? null;
    }

    public function getUserIdAttribute()
    {
        if ($this->relationLoaded('user')) {
            return $this->getRelation('user')->id ?? null;
        }
        return $this->orderItem->order->user_id ?? null;
    }
}

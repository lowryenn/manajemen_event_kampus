<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    /**
     * Show all events listing page (User Side).
     */
    public function index()
    {
        $events = Event::orderBy('date', 'asc')->get();

        return view('user.home', compact('events'));
    }

    /**
     * Show event detail page (User Side).
     */
    public function show($id)
    {
        $event = Event::findOrFail($id);

        // Count active registered users (status != cancelled)
        $registeredCount = $event->registrations()->where('status', '!=', 'cancelled')->count();
        
        // Calculate remaining quota
        $remainingCount = max(0, $event->quota - $registeredCount);
        $isFull = $remainingCount <= 0;

        return view('user.detail', compact(
            'event',
            'registeredCount',
            'remainingCount',
            'isFull'
        ));
    }
}

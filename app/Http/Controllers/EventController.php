<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    /**
     * Show event listings.
     */
    public function index()
    {
        $events = Event::orderBy('date', 'asc')->get();

        return view('user.home', compact('events'));
    }

    /**
     * Show event detail page.
     */
    public function show($id)
    {
        $event = Event::findOrFail($id);
        
        $isRegistered = false;
        if (Auth::check()) {
            $isRegistered = \App\Models\Registration::where('user_id', Auth::id())
                ->where('event_id', $event->id)
                ->where('status', 'registered')
                ->exists();
        }

        // Calculate quota statistics
        $participantsCount = $event->registrations()->where('status', 'registered')->count();
        $remainingTickets = max(0, $event->quota - $participantsCount);
        $isFull = $participantsCount >= $event->quota;

        return view('user.detail', compact(
            'event', 
            'isRegistered', 
            'participantsCount', 
            'remainingTickets', 
            'isFull'
        ));
    }

    /**
     * Store new event (Admin only).
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'description' => 'required|string',
            'type' => 'required|in:online,offline',
            'location' => 'required|string|max:255',
            'date' => 'required|date',
            'price' => 'required|integer|min:0',
            'quota' => 'required|integer|min:1',
        ]);

        Event::create([
            'organizer_id' => Auth::id(),
            'name' => $request->name,
            'description' => $request->description,
            'type' => $request->type,
            'location' => $request->location,
            'date' => $request->date,
            'price' => $request->price,
            'quota' => $request->quota,
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Event successfully created!');
    }

    /**
     * Update an event (Admin only).
     */
    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:150',
            'description' => 'required|string',
            'type' => 'required|in:online,offline',
            'location' => 'required|string|max:255',
            'date' => 'required|date',
            'price' => 'required|integer|min:0',
            'quota' => 'required|integer|min:1',
        ]);

        $event->update([
            'name' => $request->name,
            'description' => $request->description,
            'type' => $request->type,
            'location' => $request->location,
            'date' => $request->date,
            'price' => $request->price,
            'quota' => $request->quota,
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Event successfully updated!');
    }
}

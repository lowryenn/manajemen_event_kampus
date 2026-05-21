<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Factories\EventFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    /**
     * Show event listings.
     */
    public function index()
    {
        // Get published events for general listings
        $events = Event::where('status', 'published')
            ->orderBy('event_date', 'asc')
            ->get();

        return view('user.home', compact('events'));
    }

    /**
     * Show event detail page.
     */
    public function show($id)
    {
        $event = Event::findOrFail($id);
        
        // Use factory class internally to demonstrate returning details dynamically
        $eventFactoryObj = EventFactory::create(
            $event->location && (str_starts_with($event->location, 'http') || str_contains($event->location, 'zoom')) ? 'online' : 'offline',
            $event->title,
            $event->description ?? '',
            $event->location
        );
        $factoryDetails = $eventFactoryObj->getDetails();

        // Check if user is already registered for this event
        $isRegistered = false;
        if (Auth::check()) {
            $isRegistered = Auth::user()->registrations()
                ->where('event_id', $event->id)
                ->exists();
        }

        return view('user.detail', compact('event', 'factoryDetails', 'isRegistered'));
    }

    /**
     * Store new event (Admin only).
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:150',
            'description' => 'required|string',
            'type' => 'required|in:online,offline',
            'location' => 'required|string|max:255',
            'event_date' => 'required|date',
            'price' => 'required|numeric|min:0',
            'quota' => 'required|integer|min:1',
            'banner' => 'nullable|image|max:2048'
        ]);

        // Demonstrate Factory Pattern usage to instantiate the event logic
        $eventObj = EventFactory::create(
            $request->type,
            $request->title,
            $request->description,
            $request->location
        );

        $details = $eventObj->getDetails();

        // Handle file upload if banner exists
        $bannerPath = null;
        if ($request->hasFile('banner')) {
            $bannerPath = $request->file('banner')->store('banners', 'public');
        }

        // Save event to Database using organizer_id as the logged in admin
        Event::create([
            'organizer_id' => Auth::id(),
            'title' => $details['title'],
            'description' => $details['description'],
            'location' => $details['location'],
            'banner' => $bannerPath,
            'status' => 'published', // set direct published for testing
            'event_date' => $request->event_date,
            'price' => $request->price,
            'quota' => $request->quota,
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Event successfully created via EventFactory!');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Http\Request;
use App\Factories\EventFactory;

class EventController extends Controller
{
    /**
     * Show admin dashboard with enhanced statistics.
     */
    public function index()
    {
        $events = Event::withCount(['registrations' => function ($query) {
                $query->where('status', '!=', 'cancelled');
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate full events (where registrations >= quota)
        $fullEvents = $events->filter(function ($event) {
            return $event->registrations_count >= $event->quota;
        })->count();

        // Active events (future date)
        $activeEvents = Event::where('date', '>=', now())->count();

        $stats = [
            'total_events' => Event::count(),
            'total_users' => User::where('role', 'user')->count(),
            'active_events' => $activeEvents,
            'full_events' => $fullEvents,
            'total_registrations' => Registration::where('status', '!=', 'cancelled')->count(),
        ];

        return view('admin.dashboard', compact('events', 'stats'));
    }

    /**
     * Show create event form.
     */
    public function create()
    {
        return view('admin.create-event');
    }

    /**
     * Store new event using Factory pattern.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'description' => 'required|string',
            'type' => 'required|in:online,offline',
            'location' => 'required|string|max:255',
            'date' => 'required|date',
            'quota' => 'required|integer|min:1',
            'price' => 'required|integer|min:0',
        ]);

        // Factory Pattern: Validate event type via factory
        try {
            $eventObj = EventFactory::create(
                $request->type,
                $request->name,
                $request->description,
                $request->location
            );
            \Illuminate\Support\Facades\Log::info('[Factory Pattern] Created ' . $eventObj->getType() . ' event object', $eventObj->getDetails());
        } catch (\InvalidArgumentException $e) {
            return back()->withErrors(['type' => 'Invalid event type selected.'])->withInput();
        }

        Event::create([
            'name' => $request->name,
            'description' => $request->description,
            'type' => $request->type,
            'location' => $request->location,
            'date' => $request->date,
            'quota' => $request->quota,
            'price' => $request->price,
            'organizer_id' => auth()->id() ?? 1,
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Event published successfully!');
    }

    /**
     * Show edit form.
     */
    public function edit($id)
    {
        $event = Event::findOrFail($id);
        return view('admin.edit-event', compact('event'));
    }

    /**
     * Update event.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'description' => 'required|string',
            'type' => 'required|in:online,offline',
            'location' => 'required|string|max:255',
            'date' => 'required|date',
            'quota' => 'required|integer|min:1',
            'price' => 'required|integer|min:0',
        ]);

        $event = Event::findOrFail($id);
        $event->update([
            'name' => $request->name,
            'description' => $request->description,
            'type' => $request->type,
            'location' => $request->location,
            'date' => $request->date,
            'quota' => $request->quota,
            'price' => $request->price,
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Event updated successfully!');
    }

    /**
     * Delete event.
     */
    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        $event->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Event deleted successfully!');
    }

    /**
     * Show all registrations / participants.
     */
    public function participants()
    {
        $registrations = Registration::with(['user', 'event'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.participants', compact('registrations'));
    }
}

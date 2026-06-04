<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\Config\SystemConfigService;

class EventController extends Controller
{
    /**
     * Show all events listing page with filtering (User Side).
     * Uses Singleton pattern for system configuration.
     */
    public function index(Request $request)
    {
        // Singleton Pattern: Access system config
        $config = SystemConfigService::getInstance();

        $query = Event::query();

        // Filter by ticket type (free/paid)
        if ($request->filled('ticket_type')) {
            if ($request->ticket_type === 'free') {
                $query->where('price', 0);
            } elseif ($request->ticket_type === 'paid') {
                $query->where('price', '>', 0);
            }
        }

        // Filter by event type (online/offline)
        if ($request->filled('event_type')) {
            $query->where('type', $request->event_type);
        }

        // Search by name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $events = $query->orderBy('date', 'asc')->get();

        return view('user.home', compact('events', 'config'));
    }

    /**
     * Show event detail page (User Side).
     * Uses Factory pattern to create typed event object.
     */
    public function show($id)
    {
        $event = Event::findOrFail($id);

        // Factory Pattern: Create typed event for display details
        $eventFactory = \App\Factories\EventFactory::create(
            $event->type,
            $event->name,
            $event->description ?? '',
            $event->location
        );
        $eventDetails = $eventFactory->getDetails();

        // Quota calculations via model accessors
        $registeredCount = $event->registered_count;
        $remainingCount = $event->remaining_quota;
        $isFull = $event->is_full;

        // Check if current user already registered
        $alreadyRegistered = false;
        if (Auth::check()) {
            $alreadyRegistered = $event->registrations()
                ->where('user_id', Auth::id())
                ->where('status', '!=', 'cancelled')
                ->exists();
        }

        return view('user.detail', compact(
            'event',
            'eventDetails',
            'registeredCount',
            'remainingCount',
            'isFull',
            'alreadyRegistered'
        ));
    }
}

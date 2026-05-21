<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Show admin dashboard.
     */
    public function dashboard()
    {
        $events = Event::withCount(['registrations' => function ($query) {
                $query->where('status', 'registered');
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        $stats = [
            'total_events' => Event::count(),
            'total_users' => User::where('role', 'user')->count(),
            'total_registrations' => Registration::where('status', 'registered')->count()
        ];

        return view('admin.dashboard', compact('events', 'stats'));
    }

    /**
     * Show create event form.
     */
    public function showCreateEventForm()
    {
        return view('admin.create-event');
    }

    /**
     * Show edit event form.
     */
    public function showEditEventForm($id)
    {
        $event = Event::findOrFail($id);
        return view('admin.edit-event', compact('event'));
    }

    /**
     * Delete an event.
     */
    public function destroyEvent($id)
    {
        $event = Event::findOrFail($id);
        $event->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Event successfully deleted.');
    }

    /**
     * Show list of participants.
     */
    public function participants()
    {
        $registrations = Registration::with(['user', 'event'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.participants', compact('registrations'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegistrationController extends Controller
{
    /**
     * Show list of user's registrations (Riwayat Event).
     */
    public function index()
    {
        $registrations = Registration::where('user_id', Auth::id())
            ->with('event')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.registrations', compact('registrations'));
    }

    /**
     * Handle registering a user to an event.
     */
    public function register(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'payment_method' => 'nullable|string|in:bank_transfer,ewallet',
        ]);

        $event = Event::findOrFail($request->event_id);
        $user = Auth::user();

        // 1. Check if already registered (even if status is pending or registered)
        $alreadyRegistered = Registration::where('user_id', $user->id)
            ->where('event_id', $event->id)
            ->exists();

        if ($alreadyRegistered) {
            return back()->withErrors(['event_id' => 'Anda sudah terdaftar atau memiliki pendaftaran tertunda untuk event ini.']);
        }

        // 2. Check quota
        $currentRegistrations = Registration::where('event_id', $event->id)
            ->where('status', 'registered')
            ->count();

        if ($currentRegistrations >= $event->quota) {
            return back()->withErrors(['event_id' => 'Event is full']);
        }

        // Determine status: If GRATIS (price = 0) -> registered. If paid -> pending.
        $status = $event->price == 0 ? 'registered' : 'pending';

        $registration = Registration::create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'status' => $status,
        ]);

        if ($event->price > 0) {
            $methodName = $request->payment_method === 'bank_transfer' ? 'Transfer Bank' : 'E-Wallet';
            return redirect()->route('user.registrations')->with('success', "Pendaftaran berhasil dibuat dengan status PENDING via {$methodName}. Silakan lakukan pembayaran.");
        }

        return redirect()->route('user.registrations')->with('success', 'Pendaftaran GRATIS berhasil dilakukan! Status Anda langsung AKTIF (registered).');
    }

    /**
     * Simulate payment for pending registrations.
     */
    public function pay($id)
    {
        $registration = Registration::where('user_id', Auth::id())->findOrFail($id);
        
        // Ensure quota is still available
        $currentRegistrations = Registration::where('event_id', $registration->event_id)
            ->where('status', 'registered')
            ->count();

        if ($currentRegistrations >= $registration->event->quota) {
            return back()->withErrors(['error' => 'Maaf, kuota event sudah penuh. Tidak bisa memproses pembayaran.']);
        }

        $registration->update(['status' => 'registered']);

        return back()->with('success', 'Pembayaran berhasil disimulasikan! Status pendaftaran Anda kini "registered".');
    }
}

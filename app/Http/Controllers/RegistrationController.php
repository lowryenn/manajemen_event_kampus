<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegistrationController extends Controller
{
    /**
     * Show registrations history (Riwayat Event).
     */
    public function index()
    {
        // Auth fallback logic
        $userId = Auth::check() ? Auth::id() : 1;

        // Ensure user exists to avoid errors
        if ($userId === 1 && !User::where('id', 1)->exists()) {
            User::create([
                'id' => 1,
                'name' => 'Default Fallback User',
                'email' => 'fallback@test.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'is_active' => true,
            ]);
        }

        $registrations = Registration::where('user_id', $userId)
            ->with('event')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.registrations', compact('registrations'));
    }

    /**
     * Handle event registration.
     */
    public function register(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'payment_method' => 'nullable|string',
            'payment_action' => 'nullable|string|in:pay_now,pay_later',
        ]);

        $event = Event::findOrFail($request->event_id);

        // Auth fallback logic
        $userId = Auth::check() ? Auth::id() : 1;

        // Ensure fallback user exists in database
        if ($userId === 1 && !User::where('id', 1)->exists()) {
            User::create([
                'id' => 1,
                'name' => 'Default Fallback User',
                'email' => 'fallback@test.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'is_active' => true,
            ]);
        }

        // 1. Prevent duplicate registration (active only)
        $alreadyRegistered = Registration::where('user_id', $userId)
            ->where('event_id', $event->id)
            ->where('status', '!=', 'cancelled')
            ->exists();

        if ($alreadyRegistered) {
            return back()->withErrors(['error' => 'Anda sudah terdaftar atau memiliki pendaftaran tertunda untuk event ini.']);
        }

        // 2. Prevent registration if event quota is full
        $registeredCount = $event->registrations()->where('status', '!=', 'cancelled')->count();
        if ($registeredCount >= $event->quota) {
            return back()->withErrors(['error' => 'Maaf, kuota event sudah penuh.']);
        }

        // 3. Payment logic
        if ($event->price == 0) {
            // Free event -> automatically registered
            $status = 'registered';
            $paymentMethod = null;
        } else {
            // Paid event -> require payment method
            if (!$request->payment_method) {
                return back()->withErrors(['error' => 'Silakan pilih metode pembayaran untuk event berbayar ini.']);
            }
            $paymentMethod = $request->payment_method;
            
            // If action is pay_now -> status is registered, else pending
            $status = $request->payment_action === 'pay_now' ? 'registered' : 'pending';
        }

        Registration::create([
            'user_id' => $userId,
            'event_id' => $event->id,
            'status' => $status,
            'payment_method' => $paymentMethod,
        ]);

        if ($event->price > 0) {
            $msg = $status === 'registered' 
                ? 'Pembayaran berhasil! Pendaftaran langsung AKTIF (registered).'
                : 'Pendaftaran sukses! Status pendaftaran PENDING. Silakan lakukan pembayaran menggunakan ' . $paymentMethod;
            return redirect()->route('user.registrations')->with('success', $msg);
        }

        return redirect()->route('user.registrations')->with('success', 'Pendaftaran GRATIS berhasil! Status pendaftaran langsung AKTIF (registered).');
    }

    /**
     * Cancel registration.
     */
    public function cancel($id)
    {
        $userId = Auth::check() ? Auth::id() : 1;
        
        $registration = Registration::where('user_id', $userId)->findOrFail($id);
        $registration->update(['status' => 'cancelled']);

        return back()->with('success', 'Pendaftaran event berhasil dibatalkan.');
    }
}

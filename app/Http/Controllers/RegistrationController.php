<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Registration;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Import Observers
use App\Services\Observers\RegistrationPublisher;
use App\Services\Observers\EmailNotificationService;
use App\Services\Observers\TicketService;

// Import Strategy
use App\Services\Payments\PaymentService;
use App\Services\Payments\BankTransferStrategy;
use App\Services\Payments\EWalletStrategy;

// Import Adapters
use App\Services\Adapters\MidtransSDK;
use App\Services\Adapters\MidtransAdapter;

class RegistrationController extends Controller
{
    /**
     * Show list of user's registrations (Riwayat Event).
     */
    public function index()
    {
        $registrations = Auth::user()->registrations()
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
            'payment_method' => 'required|in:bank_transfer,ewallet',
            'payment_provider' => 'required|string'
        ]);

        $event = Event::findOrFail($request->event_id);
        $user = Auth::user();

        // 1. Check if already registered
        $alreadyRegistered = Registration::where('user_id', $user->id)
            ->where('event_id', $event->id)
            ->exists();

        if ($alreadyRegistered) {
            return back()->withErrors(['event_id' => 'You are already registered for this event.']);
        }

        // 2. Check quota
        $currentRegistrations = Registration::where('event_id', $event->id)->count();
        if ($currentRegistrations >= $event->quota) {
            return back()->withErrors(['event_id' => 'This event is full.']);
        }

        // Clear previous simulation logs from session
        session()->forget('design_patterns_logs');

        // 3. Create Event Registration record
        $registration = Registration::create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'status' => 'registered' // default status: registered
        ]);

        // 4. TRIGGER OBSERVER PATTERN
        $publisher = new RegistrationPublisher();
        $publisher->attach(new EmailNotificationService());
        $publisher->attach(new TicketService());
        $publisher->notify($registration);

        // 5. TRIGGER STRATEGY PATTERN (Payment Setup)
        $paymentService = new PaymentService();
        if ($request->payment_method === 'bank_transfer') {
            $paymentService->setStrategy(new BankTransferStrategy($request->payment_provider));
        } else {
            $paymentService->setStrategy(new EWalletStrategy($request->payment_provider));
        }

        // Execute payment strategy to get instructions & details
        $paymentDetails = $paymentService->process((float)$event->price);

        // 6. TRIGGER ADAPTER PATTERN (Midtrans Gateway)
        $midtransSdk = new MidtransSDK();
        $midtransAdapter = new MidtransAdapter($midtransSdk);
        // Process standard payment through Adapter
        $gatewayResult = $midtransAdapter->processPayment((int)$registration->id, (float)$event->price);

        // 7. Save Payment details in database
        Payment::create([
            'order_id' => $registration->id,
            'payment_code' => $paymentDetails['payment_code'],
            'amount' => $event->price,
            'status' => 'pending',
            'payment_proof' => null,
            'paid_at' => null
        ]);

        // Log strategy, adapter, and observer info to display on details page
        session()->put('payment_simulation', [
            'method' => $paymentDetails['method'],
            'instructions' => $paymentDetails['instructions'],
            'payment_code' => $paymentDetails['payment_code'],
            'gateway_token' => $gatewayResult['transaction_id'],
            'gateway_url' => $gatewayResult['redirect_url'],
            'gateway_status' => $gatewayResult['status']
        ]);

        return redirect()->route('user.registrations')->with('success', 'Successfully registered for event! Observer notifications and Payment Strategies triggered.');
    }
}

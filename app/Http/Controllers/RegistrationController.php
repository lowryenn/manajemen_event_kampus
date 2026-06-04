<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Payment;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Services\Config\SystemConfigService;
use App\Services\Payments\PaymentService;
use App\Services\Payments\BankTransferStrategy;
use App\Services\Payments\EWalletStrategy;
use App\Services\Adapters\MidtransAdapter;
use App\Services\Adapters\MidtransSDK;
use App\Services\Observers\RegistrationPublisher;
use App\Services\Observers\EmailNotificationService;
use App\Services\Observers\TicketService;
use App\Services\Notifications\EmailNotifier;
use App\Services\Notifications\WhatsAppNotifierDecorator;
use App\Services\Notifications\SMSNotifierDecorator;

class RegistrationController extends Controller
{
    /**
     * Show registrations history (My Events).
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('info', 'Please login to view your registrations.');
        }

        $registrations = Registration::where('user_id', Auth::id())
            ->with('event')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.registrations', compact('registrations'));
    }

    /**
     * Handle event registration.
     * Integrates: Singleton, Strategy, Adapter, Observer, Decorator patterns.
     */
    public function register(Request $request)
    {
        // Require authentication
        if (!Auth::check()) {
            return redirect()->route('login')->with('info', 'Please login to register for events.');
        }

        $request->validate([
            'event_id' => 'required|exists:events,id',
            'payment_method' => 'nullable|string',
            'payment_action' => 'nullable|string|in:pay_now,pay_later',
        ]);

        $event = Event::findOrFail($request->event_id);
        $userId = Auth::id();

        // Singleton Pattern: Check system config
        $config = SystemConfigService::getInstance();
        if ($config->get('maintenance_mode')) {
            return back()->withErrors(['error' => 'Registration is temporarily disabled for system maintenance.']);
        }

        // 1. Prevent duplicate registration (active only)
        $alreadyRegistered = Registration::where('user_id', $userId)
            ->where('event_id', $event->id)
            ->where('status', '!=', 'cancelled')
            ->exists();

        if ($alreadyRegistered) {
            return back()->withErrors(['error' => 'You are already registered for this event.']);
        }

        // 2. Prevent registration if event quota is full
        if ($event->is_full) {
            return back()->withErrors(['error' => 'Sorry, this event is fully booked.']);
        }

        // 3. Payment logic with Strategy & Adapter patterns
        $paymentMethod = null;
        $paymentResult = null;
        $gatewayResult = null;

        if ($event->price == 0) {
            // Free event -> automatically registered
            $status = 'registered';
        } else {
            // Paid event -> require payment method
            if (!$request->payment_method) {
                return back()->withErrors(['error' => 'Please select a payment method for this paid event.']);
            }

            $paymentMethod = $request->payment_method;

            // Strategy Pattern: Select and execute payment strategy
            $paymentService = new PaymentService();

            if (str_contains(strtolower($paymentMethod), 'bank') || str_contains(strtolower($paymentMethod), 'transfer')) {
                $paymentService->setStrategy(new BankTransferStrategy());
            } else {
                $paymentService->setStrategy(new EWalletStrategy());
            }

            $paymentResult = $paymentService->process($event->price);
            Log::info('[Strategy Pattern] Payment processed', $paymentResult);

            // Adapter Pattern: Process via Midtrans gateway
            $midtransAdapter = new MidtransAdapter(new MidtransSDK());
            $gatewayResult = $midtransAdapter->processPayment(time(), $event->price);
            Log::info('[Adapter Pattern] Gateway processed', $gatewayResult);

            // Determine status based on payment action
            $status = $request->payment_action === 'pay_now' ? 'registered' : 'pending';
        }

        // Create registration record
        $registration = Registration::create([
            'user_id' => $userId,
            'event_id' => $event->id,
            'status' => $status,
            'payment_method' => $paymentMethod,
        ]);

        // Create payment record for paid events
        if ($event->price > 0 && $paymentResult) {
            Payment::create([
                'order_id' => $registration->id,
                'payment_code' => $paymentResult['payment_code'] ?? null,
                'amount' => $event->price,
                'status' => $status === 'registered' ? 'success' : 'pending',
                'paid_at' => $status === 'registered' ? now() : null,
            ]);
        }

        // Observer Pattern: Notify observers after registration
        try {
            $publisher = new RegistrationPublisher();
            $publisher->attach(new EmailNotificationService());
            $publisher->attach(new TicketService());
            $publisher->notify($registration->load(['user', 'event']));
            Log::info('[Observer Pattern] Registration observers notified for Registration #' . $registration->id);
        } catch (\Exception $e) {
            Log::warning('[Observer Pattern] Observer notification failed: ' . $e->getMessage());
        }

        // Decorator Pattern: Send layered notifications
        try {
            $notifier = new EmailNotifier();
            $notifier = new WhatsAppNotifierDecorator($notifier);
            $notifier = new SMSNotifierDecorator($notifier);
            $notificationLog = $notifier->send("Registration confirmed for: {$event->name}");
            Log::info('[Decorator Pattern] ' . $notificationLog);
        } catch (\Exception $e) {
            Log::warning('[Decorator Pattern] Notification failed: ' . $e->getMessage());
        }

        // Build success message
        if ($event->price > 0) {
            $code = $paymentResult['payment_code'] ?? 'N/A';
            if ($status === 'registered') {
                $msg = "Payment successful! Your registration is confirmed. Code: {$code}";
            } else {
                $instructions = $paymentResult['instructions'] ?? '';
                $msg = "Registration submitted! Status: Pending Payment. Code: {$code}. {$instructions}";
            }
            return redirect()->route('user.registrations')->with('success', $msg);
        }

        return redirect()->route('user.registrations')->with('success', 'Registration successful! Your free ticket is confirmed.');
    }

    /**
     * Cancel registration.
     */
    public function cancel($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $registration = Registration::where('user_id', Auth::id())->findOrFail($id);
        $registration->update(['status' => 'cancelled']);

        return back()->with('success', 'Registration cancelled successfully.');
    }
}

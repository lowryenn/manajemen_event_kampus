<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// 1. Singleton
use App\Services\Config\SystemConfigService;

// 2. Factory
use App\Factories\EventFactory;

// 3. Strategy
use App\Services\Payments\PaymentService;
use App\Services\Payments\BankTransferStrategy;
use App\Services\Payments\EWalletStrategy;

// 4. Adapter
use App\Services\Adapters\MidtransSDK;
use App\Services\Adapters\MidtransAdapter;

// 5. Observer
use App\Services\Observers\RegistrationPublisher;
use App\Services\Observers\EmailNotificationService;
use App\Services\Observers\TicketService;
use App\Models\Registration;
use App\Models\User;
use App\Models\Event;

// 6. Decorator
use App\Services\Notifications\EmailNotifier;
use App\Services\Notifications\WhatsAppNotifierDecorator;
use App\Services\Notifications\SMSNotifierDecorator;

class DesignPatternsDemoController extends Controller
{
    public function index()
    {
        $logs = [];

        // --- 1. SINGLETON PATTERN ---
        $logs[] = "<h3>1. Singleton Pattern (System Configuration)</h3>";
        $config1 = SystemConfigService::getInstance();
        $config2 = SystemConfigService::getInstance();
        
        $config1->set('app_version', '1.2.0-stable');
        $logs[] = "Instance 1 sets 'app_version' = '1.2.0-stable'";
        $logs[] = "Instance 2 gets 'app_version' = '" . $config2->get('app_version') . "'";
        $logs[] = "Are Instance 1 and Instance 2 the exact same object? " . ($config1 === $config2 ? "<strong style='color:#10b981;'>YES (Same Object Instance)</strong>" : "NO");

        // --- 2. FACTORY PATTERN ---
        $logs[] = "<h3>2. Factory Pattern (Event Instantiation)</h3>";
        try {
            $onlineEvent = EventFactory::create('online', 'Virtual Guest Lecture', 'Webinar on AI', 'https://zoom.us/j/999888777');
            $offlineEvent = EventFactory::create('offline', 'Campus Expo 2026', 'Annual campus cultural festival', 'Auditorium Gd. A Lt. 3');

            $logs[] = "Factory created Online Event: Type = <strong>" . $onlineEvent->getType() . "</strong>";
            $logs[] = "Online Event Details: <pre>" . json_encode($onlineEvent->getDetails(), JSON_PRETTY_PRINT) . "</pre>";
            
            $logs[] = "Factory created Offline Event: Type = <strong>" . $offlineEvent->getType() . "</strong>";
            $logs[] = "Offline Event Details: <pre>" . json_encode($offlineEvent->getDetails(), JSON_PRETTY_PRINT) . "</pre>";
        } catch (\Exception $e) {
            $logs[] = "Error in Factory: " . $e->getMessage();
        }

        // --- 3. STRATEGY PATTERN ---
        $logs[] = "<h3>3. Strategy Pattern (Payment Methods)</h3>";
        $paymentService = new PaymentService();
        
        // Choose Bank Transfer
        $paymentService->setStrategy(new BankTransferStrategy('BCA', '987-654-321'));
        $btResult = $paymentService->process(250000.00);
        $logs[] = "Strategy: Bank Transfer chosen. Processing payment... Output: <pre>" . json_encode($btResult, JSON_PRETTY_PRINT) . "</pre>";

        // Choose E-Wallet
        $paymentService->setStrategy(new EWalletStrategy('OVO'));
        $ewResult = $paymentService->process(250000.00);
        $logs[] = "Strategy: E-Wallet chosen. Processing payment... Output: <pre>" . json_encode($ewResult, JSON_PRETTY_PRINT) . "</pre>";

        // --- 4. ADAPTER PATTERN ---
        $logs[] = "<h3>4. Adapter Pattern (Payment Gateway Mock Integration)</h3>";
        $midtransSdk = new MidtransSDK('production-key-xyz');
        $adapter = new MidtransAdapter($midtransSdk);
        
        $logs[] = "Sending payment to Adapter... standard processPayment(orderId = 15, amount = 150000.00)";
        $adapterResult = $adapter->processPayment(15, 150000.00);
        $logs[] = "Adapter formatted result: <pre>" . json_encode($adapterResult, JSON_PRETTY_PRINT) . "</pre>";

        // --- 5. OBSERVER PATTERN ---
        $logs[] = "<h3>5. Observer Pattern (Registration Handlers)</h3>";
        // Create dummy objects to trigger event notify
        $user = User::first() ?? new User(['name' => 'Demo User', 'email' => 'demo@kampus.id']);
        $event = Event::first() ?? new Event(['title' => 'Laravel Deep Dive Seminar', 'location' => 'Gd. B Lab 2']);
        
        // Create mock registration object
        $registration = new Registration([
            'id' => 99,
            'user_id' => $user->id ?? 1,
            'event_id' => $event->id ?? 1,
            'status' => 'registered'
        ]);
        $registration->setRelation('user', $user);
        $registration->setRelation('event', $event);

        $publisher = new RegistrationPublisher();
        $logs[] = "Attaching observers: EmailNotificationService, TicketService...";
        $publisher->attach(new EmailNotificationService());
        $publisher->attach(new TicketService());

        $logs[] = "Triggering publisher notify()...";
        
        // Temporarily intercept output logs
        session()->forget('design_patterns_logs');
        $publisher->notify($registration);
        
        $observerLogs = session()->get('design_patterns_logs', []);
        foreach ($observerLogs as $obsLog) {
            $logs[] = "<span style='color:#3b82f6;'>[Observer Notify]</span> " . $obsLog;
        }

        // --- 6. DECORATOR PATTERN ---
        $logs[] = "<h3>6. Decorator Pattern (Notification Alert Chain)</h3>";
        $message = "Registration for 'Campus Hackathon' is successful!";
        
        $logs[] = "Base Notifier: Email Notifier only";
        $notifier = new EmailNotifier();
        $logs[] = "Output: <pre>" . $notifier->send($message) . "</pre>";

        $logs[] = "Decorating: Adding WhatsApp capabilities...";
        $decorated1 = new WhatsAppNotifierDecorator($notifier);
        $logs[] = "Output: <pre>" . $decorated1->send($message) . "</pre>";

        $logs[] = "Decorating again: Adding SMS capabilities on top of Email + WhatsApp...";
        $decorated2 = new SMSNotifierDecorator($decorated1);
        $logs[] = "Output: <pre>" . $decorated2->send($message) . "</pre>";

        return view('demo.patterns', compact('logs'));
    }
}

@extends('layouts.app')

@section('title', $event->name . ' - EventKampus')

@section('styles')
<style>
    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        font-size: 0.875rem;
        color: var(--text-muted);
        margin-bottom: 1.5rem;
        transition: color var(--transition-fast);
    }

    .back-link:hover { color: var(--primary); }

    /* ============ HERO BANNER ============ */
    .detail-hero {
        width: 100%;
        height: 320px;
        background: linear-gradient(135deg, rgba(99,102,241,0.12), rgba(168,85,247,0.12));
        border-radius: var(--radius-xl);
        overflow: hidden;
        border: 1px solid var(--border-card);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        margin-bottom: 2rem;
    }

    .detail-hero-icon {
        font-size: 6rem;
        opacity: 0.3;
    }

    .detail-hero-overlay {
        position: absolute;
        bottom: 0; left: 0; right: 0;
        padding: 2rem;
        background: linear-gradient(to top, rgba(6,8,15,0.9) 0%, transparent 100%);
    }

    .detail-hero-badges {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 0.75rem;
    }

    .detail-hero-title {
        font-size: 2rem;
        font-weight: 800;
    }

    @media (max-width: 768px) {
        .detail-hero { height: 220px; }
        .detail-hero-title { font-size: 1.4rem; }
    }

    /* ============ LAYOUT ============ */
    .detail-grid {
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 2rem;
    }

    @media (max-width: 992px) {
        .detail-grid { grid-template-columns: 1fr; }
    }

    /* ============ INFO SECTION ============ */
    .detail-section {
        margin-bottom: 2rem;
    }

    .detail-section-title {
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .detail-meta-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 2rem;
    }

    @media (max-width: 480px) {
        .detail-meta-grid { grid-template-columns: 1fr; }
    }

    .detail-meta-item {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        padding: 1rem;
        background: rgba(255,255,255,0.02);
        border: 1px solid var(--border-subtle);
        border-radius: var(--radius-md);
    }

    .detail-meta-icon {
        width: 36px; height: 36px;
        background: var(--primary-glow);
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
    }

    .detail-meta-label {
        font-size: 0.75rem;
        color: var(--text-faint);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.15rem;
    }

    .detail-meta-value {
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--text-primary);
    }

    .detail-description {
        color: var(--text-secondary);
        line-height: 1.8;
        font-size: 0.95rem;
    }

    .detail-description p { margin-bottom: 1rem; }

    /* ============ SIDEBAR ============ */
    .sidebar-sticky {
        position: sticky;
        top: 80px;
    }

    .ticket-card .card-body { padding: 1.75rem; }

    .ticket-card-header {
        text-align: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1.25rem;
        border-bottom: 1px solid var(--border-subtle);
    }

    .ticket-label {
        font-size: 0.75rem;
        color: var(--text-faint);
        text-transform: uppercase;
        letter-spacing: 0.06em;
        margin-bottom: 0.35rem;
    }

    .ticket-price {
        font-family: var(--font-display);
        font-size: 2.25rem;
        font-weight: 900;
    }

    .ticket-price.free { color: var(--success); }
    .ticket-price.paid { color: var(--text-primary); }

    /* Quota Box */
    .quota-detail-box {
        background: rgba(255,255,255,0.02);
        border: 1px solid var(--border-subtle);
        border-radius: var(--radius-md);
        padding: 1rem;
        margin-bottom: 1.5rem;
    }

    .quota-detail-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 0;
    }

    .quota-detail-row + .quota-detail-row {
        border-top: 1px solid var(--border-subtle);
    }

    .quota-detail-label {
        font-size: 0.85rem;
        color: var(--text-muted);
    }

    .quota-detail-value {
        font-size: 0.9rem;
        font-weight: 700;
    }

    .quota-detail-value.success { color: var(--success); }
    .quota-detail-value.danger { color: var(--danger); }
    .quota-detail-value.accent { color: var(--accent); }

    /* Full badge */
    .full-banner {
        background: var(--danger-bg);
        border: 1px solid var(--danger-border);
        color: var(--danger);
        border-radius: var(--radius-md);
        padding: 0.75rem;
        text-align: center;
        font-weight: 700;
        font-size: 0.9rem;
        margin-bottom: 1.25rem;
    }

    .already-registered-banner {
        background: var(--info-bg);
        border: 1px solid rgba(88,166,255,0.25);
        color: var(--info);
        border-radius: var(--radius-md);
        padding: 0.75rem;
        text-align: center;
        font-weight: 600;
        font-size: 0.85rem;
        margin-bottom: 1.25rem;
    }

    /* Payment form */
    .payment-section { margin-bottom: 1.25rem; }

    .payment-option {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        padding: 0.65rem 0.85rem;
        background: rgba(255,255,255,0.02);
        border: 1px solid var(--border-card);
        border-radius: var(--radius-md);
        cursor: pointer;
        transition: all var(--transition-fast);
        margin-bottom: 0.5rem;
        font-size: 0.85rem;
        color: var(--text-secondary);
    }

    .payment-option:hover {
        border-color: var(--border-focus);
        background: var(--primary-glow);
    }

    .payment-option input[type="radio"] {
        accent-color: var(--primary-deep);
    }

    .payment-action-group {
        display: flex;
        gap: 0.5rem;
        margin-top: 0.75rem;
    }

    .payment-action-btn {
        flex: 1;
        padding: 0.55rem;
        background: rgba(255,255,255,0.03);
        border: 1px solid var(--border-card);
        border-radius: var(--radius-md);
        cursor: pointer;
        font-size: 0.8rem;
        color: var(--text-muted);
        font-weight: 500;
        text-align: center;
        transition: all var(--transition-fast);
    }

    .payment-action-btn:hover, .payment-action-btn.selected {
        border-color: var(--primary);
        background: var(--primary-glow);
        color: var(--primary);
    }

    /* Cost breakdown */
    .cost-breakdown {
        background: rgba(255,255,255,0.02);
        border: 1px solid var(--border-subtle);
        border-radius: var(--radius-md);
        padding: 1rem;
        margin-bottom: 1.25rem;
    }

    .cost-row {
        display: flex;
        justify-content: space-between;
        font-size: 0.85rem;
        padding: 0.35rem 0;
        color: var(--text-muted);
    }

    .cost-row.total {
        border-top: 1px solid var(--border-subtle);
        padding-top: 0.65rem;
        margin-top: 0.35rem;
        font-weight: 700;
        color: var(--text-primary);
        font-size: 0.95rem;
    }

    .event-instructions {
        font-size: 0.8rem;
        color: var(--text-faint);
        padding: 0.75rem;
        background: rgba(255,255,255,0.02);
        border-radius: var(--radius-sm);
        border-left: 3px solid var(--primary-deep);
        margin-bottom: 1.25rem;
    }
</style>
@endsection

@section('content')
<a href="{{ route('user.home') }}" class="back-link">← Back to Events</a>

@if($errors->any())
    <div class="alert alert-danger">
        <span>✕</span> {{ $errors->first() }}
    </div>
@endif

{{-- HERO BANNER --}}
<div class="detail-hero animate-in">
    <span class="detail-hero-icon">{{ $event->category_icon }}</span>
    <div class="detail-hero-overlay">
        <div class="detail-hero-badges">
            <span class="badge badge-category">{{ $event->category }}</span>
            <span class="badge badge-{{ $event->type }}">{{ ucfirst($event->type) }}</span>
            <span class="badge badge-{{ $event->ticket_type === 'FREE' ? 'free' : 'paid' }}">{{ $event->ticket_type }}</span>
            @if($isFull)
                <span class="badge badge-full">FULLY BOOKED</span>
            @endif
        </div>
        <h1 class="detail-hero-title">{{ $event->name }}</h1>
    </div>
</div>

{{-- DETAIL GRID --}}
<div class="detail-grid">
    {{-- LEFT COLUMN --}}
    <div>
        {{-- Meta Grid --}}
        <div class="detail-meta-grid animate-in animate-delay-1">
            <div class="detail-meta-item">
                <div class="detail-meta-icon">📅</div>
                <div>
                    <div class="detail-meta-label">Date & Time</div>
                    <div class="detail-meta-value">{{ $event->date ? $event->date->format('d M Y, H:i') : 'TBA' }}</div>
                </div>
            </div>
            <div class="detail-meta-item">
                <div class="detail-meta-icon">📍</div>
                <div>
                    <div class="detail-meta-label">Location</div>
                    <div class="detail-meta-value">{{ $event->location }}</div>
                </div>
            </div>
            <div class="detail-meta-item">
                <div class="detail-meta-icon">🏷</div>
                <div>
                    <div class="detail-meta-label">Category</div>
                    <div class="detail-meta-value">{{ $event->category }}</div>
                </div>
            </div>
            <div class="detail-meta-item">
                <div class="detail-meta-icon">{{ $event->type === 'online' ? '🌐' : '🏛' }}</div>
                <div>
                    <div class="detail-meta-label">Event Type</div>
                    <div class="detail-meta-value">{{ ucfirst($event->type) }} Event</div>
                </div>
            </div>
        </div>

        {{-- Event Instructions from Factory --}}
        @if(isset($eventDetails['instructions']))
            <div class="event-instructions animate-in animate-delay-2">
                💡 {{ $eventDetails['instructions'] }}
            </div>
        @endif

        {{-- Description --}}
        <div class="detail-section animate-in animate-delay-2">
            <div class="card">
                <div class="card-body">
                    <h2 class="detail-section-title">📝 About This Event</h2>
                    <div class="detail-description">
                        {!! nl2br(e($event->description)) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- RIGHT COLUMN - TICKET SIDEBAR --}}
    <div>
        <div class="sidebar-sticky">
            <div class="card ticket-card animate-in animate-delay-2">
                <div class="card-body">
                    <div class="ticket-card-header">
                        <div class="ticket-label">Ticket Price</div>
                        <div class="ticket-price {{ $event->price == 0 ? 'free' : 'paid' }}">
                            {{ $event->price == 0 ? 'FREE' : 'Rp ' . number_format($event->price, 0, ',', '.') }}
                        </div>
                    </div>

                    {{-- Quota Box --}}
                    @php
                        $percentage = $event->quota > 0 ? round(($registeredCount / $event->quota) * 100) : 0;
                        $barClass = $percentage >= 90 ? 'high' : ($percentage >= 60 ? 'medium' : 'low');
                    @endphp
                    <div class="quota-detail-box">
                        <div class="quota-detail-row">
                            <span class="quota-detail-label">Total Quota</span>
                            <span class="quota-detail-value">{{ $event->quota }}</span>
                        </div>
                        <div class="quota-detail-row">
                            <span class="quota-detail-label">Registered</span>
                            <span class="quota-detail-value accent">{{ $registeredCount }}</span>
                        </div>
                        <div class="quota-detail-row">
                            <span class="quota-detail-label">Remaining</span>
                            <span class="quota-detail-value {{ $isFull ? 'danger' : 'success' }}">{{ $remainingCount }}</span>
                        </div>
                        <div style="margin-top:0.75rem;">
                            <div class="quota-bar">
                                <div class="quota-bar-fill {{ $barClass }}" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    </div>

                    @if($isFull)
                        <div class="full-banner">⚠️ SOLD OUT — No seats available</div>
                    @endif

                    @if($alreadyRegistered)
                        <div class="already-registered-banner">✓ You are already registered for this event</div>
                    @endif

                    {{-- Registration Form --}}
                    <form action="{{ route('events.register') }}" method="POST">
                        @csrf
                        <input type="hidden" name="event_id" value="{{ $event->id }}">

                        @if($event->price > 0 && !$isFull && !$alreadyRegistered)
                            {{-- Payment Method Selection --}}
                            <div class="payment-section">
                                <div class="form-label" style="margin-bottom:0.5rem;">Payment Method</div>
                                <label class="payment-option">
                                    <input type="radio" name="payment_method" value="Bank Transfer (Virtual Account)" required>
                                    <span>🏦 Bank Transfer (Virtual Account)</span>
                                </label>
                                <label class="payment-option">
                                    <input type="radio" name="payment_method" value="GoPay / E-Wallet">
                                    <span>📱 GoPay / E-Wallet</span>
                                </label>
                            </div>

                            {{-- Payment Action --}}
                            <div class="payment-section">
                                <div class="form-label" style="margin-bottom:0.5rem;">Payment Action</div>
                                <div class="payment-action-group">
                                    <label class="payment-action-btn selected" id="pn-label">
                                        <input type="radio" name="payment_action" value="pay_now" checked style="display:none;">
                                        💳 Pay Now
                                    </label>
                                    <label class="payment-action-btn" id="pl-label">
                                        <input type="radio" name="payment_action" value="pay_later" style="display:none;">
                                        🕐 Pay Later
                                    </label>
                                </div>
                            </div>

                            {{-- Cost Breakdown --}}
                            <div class="cost-breakdown">
                                <div class="cost-row">
                                    <span>Ticket (1x)</span>
                                    <span>Rp {{ number_format($event->price, 0, ',', '.') }}</span>
                                </div>
                                <div class="cost-row">
                                    <span>Service Fee</span>
                                    <span>Rp 0</span>
                                </div>
                                <div class="cost-row total">
                                    <span>Total</span>
                                    <span>Rp {{ number_format($event->price, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        @endif

                        @if($isFull)
                            <button type="button" class="btn btn-primary btn-block btn-lg disabled" disabled>
                                Sold Out
                            </button>
                        @elseif($alreadyRegistered)
                            <button type="button" class="btn btn-primary btn-block btn-lg disabled" disabled>
                                Already Registered
                            </button>
                        @else
                            @auth
                                <button type="submit" class="btn btn-primary btn-block btn-lg">
                                    {{ $event->price == 0 ? 'Register Now — Free' : 'Register & Pay' }}
                                </button>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-primary btn-block btn-lg">
                                    Sign In to Register
                                </a>
                            @endauth
                        @endif
                    </form>

                    <p style="font-size:0.75rem; color:var(--text-faint); text-align:center; margin-top:1rem; line-height:1.5;">
                        Registration is instant for free events.<br>
                        Paid events require payment confirmation.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Payment action toggle
    document.querySelectorAll('.payment-action-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.payment-action-btn').forEach(b => b.classList.remove('selected'));
            this.classList.add('selected');
        });
    });
</script>
@endsection

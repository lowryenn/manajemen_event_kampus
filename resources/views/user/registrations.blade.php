@extends('layouts.app')

@section('title', 'My Tickets - EventKampus')

@section('styles')
<style>
    .page-header {
        margin-bottom: 2rem;
    }

    .page-header h1 {
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: 0.35rem;
    }

    .page-header p {
        color: var(--text-muted);
        font-size: 0.95rem;
    }

    .ticket-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .ticket-row {
        display: grid;
        grid-template-columns: auto 1fr auto;
        gap: 1.5rem;
        align-items: center;
        padding: 1.25rem 1.5rem;
    }

    @media (max-width: 768px) {
        .ticket-row {
            grid-template-columns: 1fr;
            text-align: center;
            gap: 1rem;
        }
    }

    .ticket-icon-box {
        width: 56px; height: 56px;
        background: linear-gradient(135deg, var(--primary-glow), var(--accent-glow));
        border: 1px solid var(--border-card);
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .ticket-info { display: flex; flex-direction: column; gap: 0.3rem; }

    .ticket-info-header {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    @media (max-width: 768px) {
        .ticket-info-header { justify-content: center; }
    }

    .ticket-event-name {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text-primary);
    }

    .ticket-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        font-size: 0.8rem;
        color: var(--text-muted);
    }

    @media (max-width: 768px) {
        .ticket-meta { justify-content: center; }
    }

    .ticket-meta-item {
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }

    .ticket-actions {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 0.5rem;
    }

    @media (max-width: 768px) {
        .ticket-actions { align-items: center; }
    }

    .btn-cancel-ticket {
        background: transparent;
        border: 1px solid var(--danger-border);
        color: var(--danger);
        padding: 0.35rem 0.75rem;
        border-radius: var(--radius-sm);
        font-size: 0.78rem;
        cursor: pointer;
        font-weight: 600;
        transition: all var(--transition-fast);
        font-family: var(--font-body);
    }

    .btn-cancel-ticket:hover {
        background: var(--danger);
        color: #fff;
        border-color: var(--danger);
    }

    .cancelled-label {
        font-size: 0.8rem;
        color: var(--text-faint);
        font-style: italic;
    }

    .payment-code {
        font-size: 0.75rem;
        color: var(--text-faint);
        font-family: monospace;
        background: rgba(255,255,255,0.03);
        padding: 0.2rem 0.5rem;
        border-radius: var(--radius-sm);
    }

    /* Ticket Modal Styles */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0, 0, 0, 0.75);
        backdrop-filter: blur(8px);
        z-index: 1000;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .modal-overlay.active {
        display: flex;
        opacity: 1;
    }
    .ticket-container {
        background: var(--bg-card);
        border: 1px solid var(--border-card);
        border-radius: 20px;
        width: 90%;
        max-width: 580px;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(0,0,0,0.5);
        position: relative;
        animation: scaleIn 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    @keyframes scaleIn {
        from { transform: scale(0.9); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }
    .ticket-header {
        background: linear-gradient(135deg, var(--primary-deep), var(--accent-deep));
        padding: 1.5rem;
        color: white;
        text-align: center;
        position: relative;
    }
    .ticket-header h2 { margin: 0; font-size: 1.5rem; font-weight: 800; }
    .ticket-body {
        padding: 2rem;
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
        border-bottom: 2px dashed var(--border-subtle);
        position: relative;
    }
    @media (max-width: 576px) {
        .ticket-body { grid-template-columns: 1fr; gap: 1.5rem; text-align: center; }
    }
    /* Ticket decorative cutouts */
    .ticket-body::before, .ticket-body::after {
        content: '';
        position: absolute;
        bottom: -12px;
        width: 24px;
        height: 24px;
        background: var(--bg-body);
        border-radius: 50%;
        border: 1px solid var(--border-card);
        z-index: 10;
    }
    .ticket-body::before { left: -12px; }
    .ticket-body::after { right: -12px; }
    
    .ticket-details { display: flex; flex-direction: column; gap: 1.2rem; }
    .ticket-detail-item { display: flex; flex-direction: column; gap: 0.25rem; }
    .ticket-label { font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; }
    .ticket-val { font-size: 1rem; font-weight: 700; color: var(--text-primary); }
    
    .ticket-qr-section {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
    }
    .qr-box {
        width: 120px;
        height: 120px;
        background: white;
        padding: 8px;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .qr-box svg { width: 100%; height: 100%; }
    .ticket-code-text {
        font-family: monospace;
        font-weight: 800;
        font-size: 0.9rem;
        color: var(--primary);
        background: var(--primary-glow);
        padding: 0.25rem 0.75rem;
        border-radius: 5px;
        border: 1px dashed var(--primary);
    }
    .ticket-footer {
        padding: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: rgba(255,255,255,0.01);
    }
    .btn-close-ticket {
        background: var(--border-card);
        border: 1px solid var(--border-card);
        color: var(--text-primary);
        padding: 0.5rem 1.25rem;
        border-radius: var(--radius-md);
        font-weight: 600;
        cursor: pointer;
        transition: all var(--transition-fast);
    }
    .btn-close-ticket:hover {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }
</style>
@endsection

@section('content')
<div class="page-header animate-in">
    <h1>My Tickets</h1>
    <p>View and manage your event registrations</p>
</div>

@if(!Auth::check())
    <div class="alert alert-warning" style="margin-bottom:2rem;">
        <span>🔒</span>
        <span>Please <a href="{{ route('login') }}" style="color:var(--warning); font-weight:700; text-decoration:underline;">sign in</a> to view your registrations.</span>
    </div>
@endif

@if(session('success'))
    <div class="alert alert-success" style="margin-bottom:2rem;">
        <span>✓</span> {{ session('success') }}
    </div>
@endif

@if(!isset($registrations) || $registrations->isEmpty())
    <div class="card animate-in animate-delay-1">
        <div class="empty-state">
            <div class="empty-state-icon">🎫</div>
            <h3>No Registrations Yet</h3>
            <p>Explore campus events and register to get your tickets here.</p>
            <a href="{{ route('user.home') }}" class="btn btn-primary">Explore Events</a>
        </div>
    </div>
@else
    <div class="ticket-list">
        @foreach($registrations as $index => $reg)
            <div class="card ticket-row animate-in animate-delay-{{ min($index + 1, 4) }}">
                <div class="ticket-icon-box">
                    {{ $reg->event->category_icon ?? '📅' }}
                </div>

                <div class="ticket-info">
                    <div class="ticket-info-header">
                        <span class="badge badge-{{ $reg->status }}">{{ strtoupper($reg->status) }}</span>
                        <span class="ticket-event-name">{{ $reg->event->name }}</span>
                    </div>

                    <div class="ticket-meta">
                        <span class="ticket-meta-item">
                            <span>📅</span>
                            {{ $reg->event->date ? $reg->event->date->format('d M Y, H:i') : 'TBA' }}
                        </span>
                        <span class="ticket-meta-item">
                            <span>📍</span>
                            {{ Str::limit($reg->event->location, 30) }}
                        </span>
                        <span class="ticket-meta-item">
                            <span>💰</span>
                            {{ $reg->event->price == 0 ? 'FREE' : 'Rp ' . number_format($reg->event->price, 0, ',', '.') }}
                        </span>
                        @if($reg->payment_method)
                            <span class="ticket-meta-item">
                                <span>💳</span>
                                {{ $reg->payment_method }}
                            </span>
                        @endif
                        <span class="ticket-meta-item">
                            <span>🕐</span>
                            {{ $reg->created_at->format('d M Y') }}
                        </span>
                        @if($reg->status === 'registered')
                            <span class="ticket-meta-item" style="color: var(--primary); font-weight: 600;">
                                <span>🎫</span>
                                {{ $reg->ticket_code }}
                            </span>
                        @endif
                    </div>
                </div>

                <div class="ticket-actions">
                    @if($reg->status === 'registered')
                        <button type="button" class="btn btn-primary btn-sm" style="margin-bottom: 0.5rem; width: 100%; text-align: center;" onclick="showTicketModal('{{ addslashes($reg->event->name) }}', '{{ $reg->event->date ? $reg->event->date->format('d M Y, H:i') : 'TBA' }}', '{{ addslashes($reg->event->location) }}', '{{ $reg->ticket_code }}', '{{ addslashes(Auth::user()->name) }}')">View Ticket</button>
                    @endif
                    @if($reg->status !== 'cancelled')
                        <form action="{{ route('registrations.cancel', $reg->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this registration?')" style="width: 100%; text-align: right;">
                            @csrf
                            <button type="submit" class="btn-cancel-ticket" style="width: 100%;">Cancel</button>
                        </form>
                    @else
                        <span class="cancelled-label">Cancelled</span>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <!-- Ticket Modal HTML -->
    <div id="ticket-modal" class="modal-overlay" onclick="closeTicketModal(event)">
        <div class="ticket-container" onclick="event.stopPropagation()">
            <div class="ticket-header">
                <h2>OFFICIAL EVENT PASS</h2>
                <div style="font-size: 0.8rem; opacity: 0.8; margin-top: 0.25rem;">EventKampus Community</div>
            </div>
            <div class="ticket-body">
                <div class="ticket-details">
                    <div class="ticket-detail-item">
                        <span class="ticket-label">Event Name</span>
                        <span id="modal-event-name" class="ticket-val">-</span>
                    </div>
                    <div class="ticket-detail-item">
                        <span class="ticket-label">Date & Time</span>
                        <span id="modal-event-date" class="ticket-val">-</span>
                    </div>
                    <div class="ticket-detail-item">
                        <span class="ticket-label">Location</span>
                        <span id="modal-event-location" class="ticket-val">-</span>
                    </div>
                    <div class="ticket-detail-item">
                        <span class="ticket-label">Attendee</span>
                        <span id="modal-user-name" class="ticket-val">-</span>
                    </div>
                </div>
                <div class="ticket-qr-section">
                    <div class="qr-box">
                        <svg viewBox="0 0 100 100" fill="currentColor">
                            <path d="M0 0h25v25H0zm5 5h15v15H5zm20 0H20v5h5zm0 15h-5v5h5zm0-10h-5v5h5zM0 75h25v25H0zm5 5h15v15H5zm70-80h25v25H75zm5 5h15v15H75z" fill="#111827"/>
                            <rect x="35" y="5" width="5" height="5" fill="#111827"/>
                            <rect x="45" y="5" width="10" height="5" fill="#111827"/>
                            <rect x="60" y="5" width="5" height="5" fill="#111827"/>
                            <rect x="65" y="10" width="5" height="5" fill="#111827"/>
                            <rect x="35" y="15" width="5" height="15" fill="#111827"/>
                            <rect x="45" y="15" width="5" height="5" fill="#111827"/>
                            <rect x="55" y="15" width="10" height="5" fill="#111827"/>
                            <rect x="35" y="35" width="15" height="5" fill="#111827"/>
                            <rect x="60" y="35" width="5" height="5" fill="#111827"/>
                            <rect x="5" y="35" width="10" height="5" fill="#111827"/>
                            <rect x="20" y="45" width="5" height="10" fill="#111827"/>
                            <rect x="30" y="45" width="10" height="5" fill="#111827"/>
                            <rect x="45" y="45" width="5" height="5" fill="#111827"/>
                            <rect x="55" y="45" width="15" height="5" fill="#111827"/>
                            <rect x="75" y="35" width="10" height="5" fill="#111827"/>
                            <rect x="75" y="45" width="5" height="10" fill="#111827"/>
                            <rect x="85" y="45" width="15" height="5" fill="#111827"/>
                            <rect x="5" y="55" width="5" height="5" fill="#111827"/>
                            <rect x="15" y="60" width="5" height="10" fill="#111827"/>
                            <rect x="35" y="55" width="5" height="5" fill="#111827"/>
                            <rect x="45" y="60" width="10" height="5" fill="#111827"/>
                            <rect x="60" y="55" width="5" height="15" fill="#111827"/>
                            <rect x="70" y="60" width="5" height="5" fill="#111827"/>
                            <rect x="80" y="55" width="5" height="5" fill="#111827"/>
                            <rect x="90" y="60" width="5" height="10" fill="#111827"/>
                            <rect x="35" y="75" width="10" height="5" fill="#111827"/>
                            <rect x="35" y="85" width="5" height="10" fill="#111827"/>
                            <rect x="50" y="75" width="5" height="5" fill="#111827"/>
                            <rect x="50" y="85" width="15" height="5" fill="#111827"/>
                            <rect x="60" y="75" width="5" height="5" fill="#111827"/>
                            <rect x="70" y="80" width="5" height="15" fill="#111827"/>
                        </svg>
                    </div>
                    <div id="modal-ticket-code" class="ticket-code-text">-</div>
                </div>
            </div>
            <div class="ticket-footer">
                <span style="font-size: 0.8rem; color: var(--text-muted);">Please present this pass at the entrance.</span>
                <button type="button" class="btn-close-ticket" onclick="closeTicketModal()">Close</button>
            </div>
        </div>
    </div>

    <script>
        function showTicketModal(eventName, eventDate, eventLocation, ticketCode, userName) {
            document.getElementById('modal-event-name').innerText = eventName;
            document.getElementById('modal-event-date').innerText = eventDate;
            document.getElementById('modal-event-location').innerText = eventLocation;
            document.getElementById('modal-ticket-code').innerText = ticketCode;
            document.getElementById('modal-user-name').innerText = userName;
            
            const modal = document.getElementById('ticket-modal');
            modal.classList.add('active');
        }

        function closeTicketModal(event) {
            const modal = document.getElementById('ticket-modal');
            modal.classList.remove('active');
        }
    </script>
@endif
@endsection

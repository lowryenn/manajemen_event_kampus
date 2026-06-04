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
                    </div>
                </div>

                <div class="ticket-actions">
                    @if($reg->status !== 'cancelled')
                        <form action="{{ route('registrations.cancel', $reg->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this registration?')">
                            @csrf
                            <button type="submit" class="btn-cancel-ticket">Cancel</button>
                        </form>
                    @else
                        <span class="cancelled-label">Cancelled</span>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection

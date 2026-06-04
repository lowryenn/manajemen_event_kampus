@extends('layouts.app')

@section('title', 'Explore Events - EventKampus')

@section('styles')
<style>
    /* ============ HERO ============ */
    .hero {
        text-align: center;
        padding: 3rem 0 2.5rem;
        position: relative;
    }

    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.3rem 0.85rem;
        background: var(--primary-glow);
        border: 1px solid rgba(99,102,241,0.2);
        border-radius: var(--radius-full);
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--primary);
        margin-bottom: 1.25rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .hero h1 {
        font-size: 2.75rem;
        font-weight: 900;
        margin-bottom: 0.75rem;
        background: linear-gradient(135deg, var(--text-primary) 0%, var(--text-muted) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .hero p {
        color: var(--text-muted);
        font-size: 1.05rem;
        max-width: 520px;
        margin: 0 auto;
        line-height: 1.7;
    }

    @media (max-width: 640px) {
        .hero h1 { font-size: 1.85rem; }
    }

    /* ============ FILTERS ============ */
    .filter-bar {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        align-items: center;
        margin-bottom: 2.5rem;
        padding: 1rem 1.25rem;
        background: var(--bg-card);
        border: 1px solid var(--border-card);
        border-radius: var(--radius-lg);
        backdrop-filter: blur(12px);
    }

    .filter-bar .search-box {
        flex: 1;
        min-width: 200px;
        position: relative;
    }

    .filter-bar .search-box input {
        width: 100%;
        background: rgba(255,255,255,0.03);
        border: 1px solid var(--border-card);
        padding: 0.55rem 0.85rem 0.55rem 2.25rem;
        border-radius: var(--radius-md);
        color: var(--text-primary);
        font-size: 0.85rem;
        font-family: var(--font-body);
    }

    .filter-bar .search-box input:focus {
        outline: none;
        border-color: var(--border-focus);
    }

    .filter-bar .search-box::before {
        content: '🔍';
        position: absolute;
        left: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        font-size: 0.8rem;
        pointer-events: none;
    }

    .filter-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.45rem 0.85rem;
        background: rgba(255,255,255,0.03);
        border: 1px solid var(--border-card);
        border-radius: var(--radius-full);
        font-size: 0.8rem;
        font-weight: 500;
        color: var(--text-muted);
        cursor: pointer;
        transition: all var(--transition-fast);
        text-decoration: none;
    }

    .filter-pill:hover, .filter-pill.active {
        background: var(--primary-glow);
        border-color: rgba(99,102,241,0.3);
        color: var(--primary);
    }

    .filter-divider {
        width: 1px;
        height: 24px;
        background: var(--border-card);
    }

    /* ============ RESULTS HEADER ============ */
    .results-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .results-count {
        font-size: 0.9rem;
        color: var(--text-muted);
    }

    .results-count strong { color: var(--text-primary); }

    /* ============ EVENT GRID ============ */
    .event-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
        gap: 1.5rem;
    }

    @media (max-width: 640px) {
        .event-grid { grid-template-columns: 1fr; }
    }

    /* ============ EVENT CARD ============ */
    .event-card {
        display: flex;
        flex-direction: column;
        overflow: hidden;
        padding: 0;
        position: relative;
    }

    .event-card:hover { transform: translateY(-3px); }

    .event-card-banner {
        height: 170px;
        position: relative;
        background: linear-gradient(135deg, rgba(99,102,241,0.12) 0%, rgba(168,85,247,0.12) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .event-card-banner::after {
        content: '';
        position: absolute;
        bottom: 0; left: 0; right: 0;
        height: 50%;
        background: linear-gradient(to top, var(--bg-card), transparent);
    }

    .event-card-banner-icon {
        font-size: 3.5rem;
        opacity: 0.4;
    }

    .event-card-badges {
        position: absolute;
        top: 0.75rem;
        left: 0.75rem;
        right: 0.75rem;
        display: flex;
        justify-content: space-between;
        z-index: 2;
    }

    .event-card-content {
        padding: 1.25rem 1.5rem 1.5rem;
        display: flex;
        flex-direction: column;
        flex: 1;
    }

    .event-card-category {
        font-size: 0.7rem;
        font-weight: 700;
        color: var(--accent);
        text-transform: uppercase;
        letter-spacing: 0.06em;
        margin-bottom: 0.4rem;
    }

    .event-card-title {
        font-size: 1.15rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        line-height: 1.35;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .event-card-desc {
        font-size: 0.85rem;
        color: var(--text-muted);
        line-height: 1.5;
        margin-bottom: 1rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .event-card-meta {
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
        margin-bottom: 1rem;
        font-size: 0.8rem;
        color: var(--text-muted);
    }

    .event-card-meta-item {
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    .event-card-meta-item .icon { width: 16px; text-align: center; font-size: 0.75rem; }

    /* Quota display */
    .event-card-quota {
        margin-bottom: 1.25rem;
    }

    .quota-text {
        display: flex;
        justify-content: space-between;
        font-size: 0.78rem;
        margin-bottom: 0.35rem;
    }

    .quota-text .label { color: var(--text-muted); }
    .quota-text .value { font-weight: 700; }
    .quota-text .value.available { color: var(--success); }
    .quota-text .value.full { color: var(--danger); }

    /* Footer */
    .event-card-footer {
        margin-top: auto;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 1rem;
        border-top: 1px solid var(--border-subtle);
    }

    .event-card-price {
        font-family: var(--font-display);
        font-size: 1.15rem;
        font-weight: 800;
    }

    .event-card-price.free { color: var(--success); }
    .event-card-price.paid { color: var(--text-primary); }
</style>
@endsection

@section('content')
{{-- HERO SECTION --}}
<div class="hero animate-in">
    <div class="hero-badge">🎯 Campus Events Platform</div>
    <h1>Explore Campus Events</h1>
    <p>Discover seminars, workshops, hackathons, and more. Find events that match your interests and register instantly.</p>
</div>

{{-- FILTER BAR --}}
<form method="GET" action="{{ route('user.home') }}" class="filter-bar animate-in animate-delay-1">
    <div class="search-box">
        <input type="text" name="search" placeholder="Search events..." value="{{ request('search') }}">
    </div>

    <div class="filter-divider"></div>

    <a href="{{ route('user.home') }}"
       class="filter-pill {{ !request('ticket_type') && !request('event_type') ? 'active' : '' }}">All</a>

    <a href="{{ route('user.home', ['ticket_type' => 'free', 'event_type' => request('event_type'), 'search' => request('search')]) }}"
       class="filter-pill {{ request('ticket_type') === 'free' ? 'active' : '' }}">🎫 Free</a>

    <a href="{{ route('user.home', ['ticket_type' => 'paid', 'event_type' => request('event_type'), 'search' => request('search')]) }}"
       class="filter-pill {{ request('ticket_type') === 'paid' ? 'active' : '' }}">💰 Paid</a>

    <div class="filter-divider"></div>

    <a href="{{ route('user.home', ['event_type' => 'online', 'ticket_type' => request('ticket_type'), 'search' => request('search')]) }}"
       class="filter-pill {{ request('event_type') === 'online' ? 'active' : '' }}">🌐 Online</a>

    <a href="{{ route('user.home', ['event_type' => 'offline', 'ticket_type' => request('ticket_type'), 'search' => request('search')]) }}"
       class="filter-pill {{ request('event_type') === 'offline' ? 'active' : '' }}">📍 Offline</a>

    <button type="submit" class="btn btn-primary btn-sm" style="margin-left:auto;">Search</button>
</form>

{{-- RESULTS --}}
@if($events->isEmpty())
    <div class="card animate-in animate-delay-2">
        <div class="empty-state">
            <div class="empty-state-icon">📭</div>
            <h3>No Events Found</h3>
            <p>There are no events matching your criteria. Try adjusting your filters or check back later.</p>
            <a href="{{ route('user.home') }}" class="btn btn-secondary">Clear Filters</a>
        </div>
    </div>
@else
    <div class="results-header animate-in animate-delay-2">
        <span class="results-count">Showing <strong>{{ $events->count() }}</strong> event{{ $events->count() > 1 ? 's' : '' }}</span>
    </div>

    <div class="event-grid">
        @foreach($events as $index => $event)
            @php
                $registeredCount = $event->registered_count;
                $remaining = $event->remaining_quota;
                $isFull = $event->is_full;
                $percentage = $event->quota > 0 ? round(($registeredCount / $event->quota) * 100) : 0;
                $barClass = $percentage >= 90 ? 'high' : ($percentage >= 60 ? 'medium' : 'low');
            @endphp
            <div class="card event-card animate-in animate-delay-{{ min($index + 2, 4) }}">
                <div class="event-card-banner">
                    <span class="event-card-banner-icon">{{ $event->category_icon }}</span>
                    <div class="event-card-badges">
                        <span class="badge badge-{{ $event->type }}">{{ ucfirst($event->type) }}</span>
                        <div style="display:flex; gap:0.35rem;">
                            <span class="badge badge-{{ $event->ticket_type === 'FREE' ? 'free' : 'paid' }}">{{ $event->ticket_type }}</span>
                            @if($isFull)
                                <span class="badge badge-full">FULLY BOOKED</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="event-card-content">
                    <div class="event-card-category">{{ $event->category }}</div>
                    <h3 class="event-card-title">{{ $event->name }}</h3>
                    <p class="event-card-desc">{{ Str::limit($event->description, 90) }}</p>

                    <div class="event-card-meta">
                        <div class="event-card-meta-item">
                            <span class="icon">📅</span>
                            <span>{{ $event->date ? $event->date->format('d M Y, H:i') : 'Date TBA' }}</span>
                        </div>
                        <div class="event-card-meta-item">
                            <span class="icon">📍</span>
                            <span>{{ Str::limit($event->location, 35) }}</span>
                        </div>
                    </div>

                    {{-- Quota Display --}}
                    <div class="event-card-quota">
                        <div class="quota-text">
                            <span class="label">Available</span>
                            <span class="value {{ $isFull ? 'full' : 'available' }}">{{ $remaining }} / {{ $event->quota }}</span>
                        </div>
                        <div class="quota-bar">
                            <div class="quota-bar-fill {{ $barClass }}" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>

                    <div class="event-card-footer">
                        <span class="event-card-price {{ $event->price == 0 ? 'free' : 'paid' }}">
                            {{ $event->price == 0 ? 'FREE' : 'Rp ' . number_format($event->price, 0, ',', '.') }}
                        </span>
                        <a href="{{ route('events.show', $event->id) }}" class="btn btn-primary btn-sm">View Details</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection

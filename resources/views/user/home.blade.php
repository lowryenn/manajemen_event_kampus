@extends('layouts.app')

@section('title', 'Daftar Event Kampus - EventKampus')

@section('styles')
<style>
    .header-section {
        text-align: center;
        margin-bottom: 3rem;
        padding: 2rem 0;
    }

    .header-title {
        font-size: 2.75rem;
        background: linear-gradient(135deg, #fff, var(--text-muted));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 0.75rem;
    }

    .header-subtitle {
        color: var(--text-muted);
        font-size: 1.1rem;
        max-width: 600px;
        margin: 0 auto;
        line-height: 1.6;
    }

    .event-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 2rem;
    }

    .event-card {
        display: flex;
        flex-direction: column;
        height: 100%;
        overflow: hidden;
        padding: 0;
    }

    .event-banner-container {
        height: 180px;
        position: relative;
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.2), rgba(217, 70, 239, 0.2));
        border-bottom: 1px solid var(--border-card);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .event-banner-placeholder {
        font-family: var(--font-outfit);
        font-size: 3rem;
    }

    .event-banner {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .event-badge-type {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: rgba(17, 24, 39, 0.85);
        backdrop-filter: blur(4px);
        padding: 0.25rem 0.75rem;
        border-radius: 999px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        border: 1px solid var(--border-card);
    }

    .event-content {
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        flex: 1;
    }

    .event-date {
        color: var(--accent);
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 0.5rem;
        letter-spacing: 0.05em;
    }

    .event-title {
        font-size: 1.35rem;
        margin-bottom: 0.75rem;
        line-height: 1.3;
        color: #fff;
    }

    .event-desc-snippet {
        font-size: 0.9rem;
        color: var(--text-muted);
        line-height: 1.5;
        margin-bottom: 1.25rem;
    }

    .event-meta {
        margin-bottom: 1.5rem;
        font-size: 0.9rem;
        color: var(--text-muted);
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .event-footer {
        margin-top: auto;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-top: 1px solid rgba(255, 255, 255, 0.05);
        padding-top: 1rem;
    }

    .event-price {
        font-family: var(--font-outfit);
        font-size: 1.25rem;
        font-weight: 700;
        color: #fff;
    }

    .event-price.free {
        color: var(--success);
    }

    .btn-detail {
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
    }
</style>
@endsection

@section('content')
<div class="header-section">
    <h1 class="header-title">Jelajahi Event Kampus</h1>
    <p class="header-subtitle">Temukan seminar, kompetisi, hackathon, dan kegiatan seru lainnya di sekitar kampus Anda.</p>
</div>

@if(session('success'))
    <div class="alert alert-success" style="margin-bottom: 2rem;">
        {{ session('success') }}
    </div>
@endif

@if($events->isEmpty())
    <div class="card" style="text-align: center; padding: 4rem 2rem;">
        <span style="font-size: 3rem;">📅</span>
        <h3 style="margin-top: 1rem; margin-bottom: 0.5rem;">Belum ada event saat ini</h3>
        <p style="color: var(--text-muted);">Silakan kembali lagi nanti untuk melihat event terbaru.</p>
    </div>
@else
    <div class="event-grid">
        @foreach($events as $event)
            <div class="card event-card">
                <div class="event-banner-container">
                    @if(isset($event->banner) && $event->banner)
                        <img src="{{ asset('storage/' . $event->banner) }}" alt="{{ $event->name }}" class="event-banner">
                    @else
                        <div class="event-banner-placeholder">
                            @if(strtolower($event->type) === 'online')
                                💻
                            @else
                                🏛
                            @endif
                        </div>
                    @endif
                    <div class="event-badge-type">
                        {{ ucfirst($event->type) }}
                    </div>
                </div>
                
                <div class="event-content">
                    <div class="event-date">
                        {{ $event->date ? $event->date->format('d M Y - H:i') : 'Tanggal TBA' }}
                    </div>
                    <h3 class="event-title">{{ $event->name }}</h3>
                    
                    <p class="event-desc-snippet">
                        {{ Str::limit($event->description, 95) }}
                    </p>
                    
                    @php
                        // Strictly count using registrations (no tickets relation)
                        $registeredCount = $event->registrations()->where('status', '!=', 'cancelled')->count();
                        $remainingTickets = max(0, $event->quota - $registeredCount);
                        $isFull = $remainingTickets <= 0;
                    @endphp
                    
                    <div class="event-meta">
                        <div class="meta-item">
                            <span>📍</span> 
                            <span>{{ Str::limit($event->location, 30) }}</span>
                        </div>
                        <div class="meta-item">
                            <span>👥</span>
                            <span>Kuota Total: {{ $event->quota }} Peserta</span>
                        </div>
                        <div class="meta-item" style="color: {{ $isFull ? '#ef4444' : '#10b981' }}; font-weight: 600;">
                            <span>🎟</span>
                            <span>{{ $isFull ? 'Event Full (Tiket Habis)' : 'Sisa tiket: ' . $remainingTickets }}</span>
                        </div>
                    </div>

                    <div class="event-footer">
                        <div class="event-price {{ $event->price == 0 ? 'free' : '' }}">
                            @if($isFull)
                                <span style="color: #ef4444; font-size: 0.95rem; font-weight: 800; text-transform: uppercase;">FULL</span>
                            @else
                                {{ $event->price == 0 ? 'GRATIS' : 'Rp ' . number_format($event->price, 0, ',', '.') }}
                            @endif
                        </div>
                        <a href="{{ route('events.show', $event->id) }}" class="btn-primary btn-detail">Detail Event</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection

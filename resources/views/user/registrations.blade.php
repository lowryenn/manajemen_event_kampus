@extends('layouts.app')

@section('title', 'Riwayat Event Saya - EventKampus')

@section('styles')
<style>
    .page-title {
        font-size: 2.25rem;
        color: #fff;
        margin-bottom: 2rem;
    }

    .simulation-box {
        background: rgba(217, 70, 239, 0.05);
        border: 1px dashed rgba(217, 70, 239, 0.3);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2.5rem;
    }

    .simulation-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--accent);
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .logs-container {
        background: rgba(0, 0, 0, 0.4);
        padding: 1rem;
        border-radius: 8px;
        font-family: monospace;
        font-size: 0.85rem;
        color: #60a5fa;
        border: 1px solid rgba(255, 255, 255, 0.03);
        margin-top: 0.75rem;
        max-height: 200px;
        overflow-y: auto;
    }

    .log-item {
        margin-bottom: 0.25rem;
    }

    .ticket-list {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .ticket-card {
        display: grid;
        grid-template-columns: 3fr 1fr;
        align-items: center;
        gap: 1.5rem;
    }

    .ticket-info {
        border-right: 1px dashed rgba(255, 255, 255, 0.08);
        padding-right: 1.5rem;
    }

    .ticket-event-title {
        font-size: 1.5rem;
        color: #fff;
        margin-bottom: 0.5rem;
    }

    .ticket-meta {
        font-size: 0.9rem;
        color: var(--text-muted);
        display: flex;
        gap: 1.5rem;
        margin-bottom: 1rem;
    }

    .ticket-payment-details {
        background: rgba(255, 255, 255, 0.02);
        padding: 0.75rem 1rem;
        border-radius: 8px;
        border: 1px solid rgba(255, 255, 255, 0.04);
        font-size: 0.85rem;
    }

    .ticket-action {
        text-align: center;
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        align-items: center;
        justify-content: center;
    }

    .gateway-link {
        font-size: 0.8rem;
        color: var(--accent);
        text-decoration: underline;
    }

    .gateway-link:hover {
        color: #fff;
    }
</style>
@endsection

@section('content')
<h1 class="page-title">Riwayat Registrasi Event</h1>

<!-- DESIGN PATTERN SIMULATION RESULTS (SHOWS ON NEW REGISTRATION) -->
@if(session('payment_simulation'))
    <div class="simulation-box">
        <div class="simulation-title">⚡ Desain Pattern Terpicu (Simulasi Sistem)</div>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <div>
                <h4 style="color:#fff; margin-bottom:0.5rem;">1. Strategy Pattern (Metode Pembayaran)</h4>
                <p style="font-size:0.9rem; color:var(--text-muted);">
                    Strategi terpilih: <strong>{{ session('payment_simulation.method') }}</strong><br>
                    Kode Bayar: <code style="color:#fff; background:rgba(255,255,255,0.08); padding:0.15rem 0.4rem; border-radius:4px;">{{ session('payment_simulation.payment_code') }}</code><br>
                    Instruksi: <span style="color:var(--text-main);">{{ session('payment_simulation.instructions') }}</span>
                </p>
            </div>
            <div>
                <h4 style="color:#fff; margin-bottom:0.5rem;">2. Adapter Pattern (Payment Gateway API)</h4>
                <p style="font-size:0.9rem; color:var(--text-muted);">
                    Adapter: <code>MidtransAdapter</code> wrapping <code>MidtransSDK</code><br>
                    Token Gateway: <code style="color:#fff; background:rgba(255,255,255,0.08); padding:0.15rem 0.4rem; border-radius:4px;">{{ session('payment_simulation.gateway_token') }}</code><br>
                    Redirect URL: <a href="{{ session('payment_simulation.gateway_url') }}" target="_blank" class="gateway-link">Buka Halaman Bayar Midtrans (Simulasi)</a><br>
                    Status: <span style="color:var(--success); font-weight:600;">{{ session('payment_simulation.gateway_status') }}</span>
                </p>
            </div>
        </div>

        <div style="margin-top: 1.5rem;">
            <h4 style="color:#fff; margin-bottom:0.25rem;">3. Observer Pattern (Notifikasi Pendaftaran)</h4>
            <p style="font-size:0.9rem; color:var(--text-muted);">Subscribers / Observers terlampir yang merespon trigger pendaftaran baru:</p>
            <div class="logs-container">
                @foreach(session('design_patterns_logs', []) as $log)
                    <div class="log-item">{{ $log }}</div>
                @endforeach
            </div>
        </div>
    </div>
@endif

@if($registrations->isEmpty())
    <div class="card" style="text-align: center; padding: 4rem 2rem;">
        <span style="font-size: 3rem;">🎫</span>
        <h3 style="margin-top: 1rem; margin-bottom: 0.5rem;">Belum ada registrasi event</h3>
        <p style="color: var(--text-muted);">Jelajahi daftar event kami untuk mendaftar dan mengikuti kegiatan menarik.</p>
        <a href="{{ route('user.home') }}" class="btn-primary" style="margin-top: 1.5rem;">Cari Event</a>
    </div>
@else
    <div class="ticket-list">
        @foreach($registrations as $reg)
            <div class="card ticket-card">
                <div class="ticket-info">
                    <h3 class="ticket-event-title">{{ $reg->event->title }}</h3>
                    <div class="ticket-meta">
                        <span>📅 {{ $reg->event->event_date ? $reg->event->event_date->format('d M Y, H:i') : 'TBA' }}</span>
                        <span>📍 {{ $reg->event->location }}</span>
                    </div>
                    
                    <div class="ticket-payment-details">
                        <div style="display:flex; justify-content:space-between; margin-bottom:0.25rem;">
                            <span>Status Tiket:</span>
                            <span class="badge badge-registered">{{ $reg->status }}</span>
                        </div>
                        <div style="display:flex; justify-content:space-between;">
                            <span>Biaya Event:</span>
                            <strong>{{ $reg->event->price == 0 ? 'GRATIS' : 'Rp ' . number_format($reg->event->price, 0, ',', '.') }}</strong>
                        </div>
                    </div>
                </div>

                <div class="ticket-action">
                    <span style="font-size: 2rem;">🎟</span>
                    <span style="font-size:0.8rem; color:var(--text-muted);">ID Tiket: #{{ $reg->id }}</span>
                    <a href="{{ route('events.show', $reg->event->id) }}" class="btn-secondary" style="padding: 0.4rem 0.8rem; font-size:0.8rem;">Detail Event</a>
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection

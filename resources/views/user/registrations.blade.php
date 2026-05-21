@extends('layouts.app')

@section('title', 'Riwayat Pendaftaran Event')

@section('styles')
<style>
    .page-title {
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: 2rem;
        color: #fff;
    }

    .fallback-alert {
        background: rgba(245, 158, 11, 0.1);
        border: 1px solid rgba(245, 158, 11, 0.3);
        color: #fbbf24;
        padding: 1rem 1.5rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        line-height: 1.5;
    }

    .ticket-list {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .ticket-card {
        display: grid;
        grid-template-columns: 1fr 3fr 1fr;
        gap: 1.5rem;
        align-items: center;
        background: var(--bg-card);
        border: 1px solid var(--border-card);
        border-radius: 16px;
        padding: 1.5rem;
    }

    @media (max-width: 768px) {
        .ticket-card {
            grid-template-columns: 1fr;
            text-align: center;
            gap: 1rem;
        }
    }

    .ticket-icon {
        width: 70px;
        height: 70px;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.25rem;
        margin: 0 auto;
    }

    .ticket-info {
        display: flex;
        flex-direction: column;
        gap: 0.35rem;
    }

    .ticket-event-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #fff;
    }

    .ticket-event-meta {
        font-size: 0.85rem;
        color: var(--text-muted);
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
    }

    @media (max-width: 768px) {
        .ticket-event-meta {
            justify-content: center;
        }
    }

    .ticket-actions {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        align-items: flex-end;
    }

    @media (max-width: 768px) {
        .ticket-actions {
            align-items: center;
        }
    }

    .status-badge {
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        display: inline-block;
    }

    .status-registered {
        background: rgba(16, 185, 129, 0.15);
        color: var(--success);
        border: 1px solid rgba(16, 185, 129, 0.3);
    }

    .status-pending {
        background: rgba(245, 158, 11, 0.15);
        color: #f59e0b;
        border: 1px solid rgba(245, 158, 11, 0.3);
    }

    .status-cancelled {
        background: rgba(239, 68, 68, 0.15);
        color: #ef4444;
        border: 1px solid rgba(239, 68, 68, 0.3);
    }

    .btn-cancel {
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.2);
        color: #f87171;
        padding: 0.4rem 0.85rem;
        border-radius: 6px;
        font-size: 0.8rem;
        cursor: pointer;
        font-weight: 500;
        transition: all 0.2s;
    }

    .btn-cancel:hover {
        background: #ef4444;
        color: #fff;
    }
</style>
@endsection

@section('content')
<h1 class="page-title">Riwayat Registrasi Event</h1>

@if(!Auth::check())
    <div class="fallback-alert">
        <span>⚠️</span>
        <div>
            <strong>Mode Fallback Aktif (Belum Login):</strong> Menampilkan riwayat pendaftaran untuk default <strong>User ID = 1</strong>. Silakan <a href="{{ route('login') }}" style="color: #fff; font-weight: 600; text-decoration: underline;">Login</a> untuk menggunakan akun pribadi Anda.
        </div>
    </div>
@endif

@if(session('success'))
    <div class="alert alert-success" style="margin-bottom: 2rem;">
        {{ session('success') }}
    </div>
@endif

@if($registrations->isEmpty())
    <div class="card" style="text-align: center; padding: 4rem 2rem;">
        <span style="font-size: 3rem;">🎟</span>
        <h3 style="margin-top: 1rem; margin-bottom: 0.5rem;">Belum ada pendaftaran event</h3>
        <p style="color: var(--text-muted); margin-bottom: 1.5rem;">Jelajahi berbagai event kampus menarik dan daftarkan diri Anda sekarang.</p>
        <a href="{{ route('user.home') }}" class="btn-primary">Cari Event</a>
    </div>
@else
    <div class="ticket-list">
        @foreach($registrations as $reg)
            <div class="card ticket-card">
                <div class="ticket-icon">
                    @if(strtolower($reg->event->type) === 'online')
                        💻
                    @else
                        🏛
                    @endif
                </div>

                <div class="ticket-info">
                    <span class="status-badge status-{{ $reg->status }}">
                        {{ $reg->status }}
                    </span>
                    
                    <h3 class="ticket-event-title">{{ $reg->event->name }}</h3>
                    
                    <div class="ticket-event-meta">
                        <span>📅 {{ $reg->event->date ? $reg->event->date->format('d M Y - H:i') : 'Tanggal TBA' }}</span>
                        <span>📍 {{ $reg->event->location }}</span>
                        <span>💰 {{ $reg->event->price == 0 ? 'GRATIS' : 'Rp ' . number_format($reg->event->price, 0, ',', '.') }}</span>
                        @if($reg->payment_method)
                            <span>💳 {{ $reg->payment_method }}</span>
                        @endif
                    </div>
                </div>

                <div class="ticket-actions">
                    @if($reg->status !== 'cancelled')
                        <form action="{{ route('registrations.cancel', $reg->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pendaftaran event ini?')">
                            @csrf
                            <button type="submit" class="btn-cancel">
                                Batalkan Tiket
                            </button>
                        </form>
                    @else
                        <span style="font-size: 0.85rem; color: var(--text-muted); font-style: italic;">
                            Dibatalkan
                        </span>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection

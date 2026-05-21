@extends('layouts.app')

@section('title', $event->name . ' - Detail Event')

@section('styles')
<style>
    .detail-container {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2.5rem;
        margin-top: 2rem;
    }

    @media (max-width: 992px) {
        .detail-container {
            grid-template-columns: 1fr;
        }
    }

    .detail-image-box {
        width: 100%;
        height: 380px;
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(217, 70, 239, 0.1));
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid var(--border-card);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 2rem;
    }

    .detail-image-placeholder {
        font-size: 6rem;
    }

    .detail-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .info-section {
        background: var(--bg-card);
        border: 1px solid var(--border-card);
        border-radius: 16px;
        padding: 2rem;
    }

    .meta-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
    }

    .meta-tag {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.08);
        padding: 0.35rem 0.85rem;
        border-radius: 999px;
        font-size: 0.8rem;
        font-weight: 500;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }

    .meta-tag-accent {
        background: rgba(99, 102, 241, 0.15);
        border-color: rgba(99, 102, 241, 0.3);
        color: var(--primary);
    }

    .info-title {
        font-size: 2.25rem;
        font-weight: 800;
        line-height: 1.25;
        color: #fff;
        margin-bottom: 1rem;
    }

    .event-desc {
        color: var(--text-muted);
        line-height: 1.7;
        font-size: 1.05rem;
        margin-bottom: 2rem;
    }

    .sidebar-card {
        position: sticky;
        top: 2rem;
        height: fit-content;
    }

    .quota-box {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1rem;
        margin-bottom: 1.5rem;
        background: rgba(255, 255, 255, 0.02);
        padding: 1.25rem;
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.05);
    }

    .quota-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }

    .quota-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .quota-label {
        font-size: 0.9rem;
        color: var(--text-muted);
    }

    .quota-val {
        font-weight: 700;
        font-size: 1rem;
        color: #fff;
    }

    .quota-val.accent {
        color: var(--accent);
    }

    .quota-val.success {
        color: var(--success);
    }

    .quota-val.danger {
        color: #ef4444;
    }

    .price-tag {
        font-size: 2rem;
        font-weight: 800;
        color: #fff;
        margin-bottom: 1.5rem;
        text-align: center;
        font-family: var(--font-outfit);
    }

    .price-tag.free {
        color: var(--success);
    }
</style>
@endsection

@section('content')
<div style="margin-bottom: 1.5rem;">
    <a href="{{ route('user.home') }}" style="color: var(--text-muted); text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; font-size: 0.95rem;">
        ← Kembali ke Daftar Event
    </a>
</div>

@if($errors->any())
    <div class="alert alert-danger" style="margin-bottom: 2rem;">
        {{ $errors->first() }}
    </div>
@endif

<div class="detail-container">
    <div>
        <div class="detail-image-box">
            @if(isset($event->banner) && $event->banner)
                <img src="{{ asset('storage/' . $event->banner) }}" alt="{{ $event->name }}" class="detail-image">
            @else
                <div class="detail-image-placeholder">
                    {{ strtolower($event->type) === 'online' ? '💻' : '🏛' }}
                </div>
            @endif
        </div>

        <div class="info-section">
            <div class="meta-tags">
                <span class="meta-tag meta-tag-accent">
                    🏷 {{ strtoupper($event->type) }}
                </span>
                <span class="meta-tag">
                    📅 {{ $event->date ? $event->date->format('d M Y, H:i') : 'Tanggal TBA' }}
                </span>
                <span class="meta-tag">
                    📍 {{ $event->location }}
                </span>
            </div>

            <h1 class="info-title">{{ $event->name }}</h1>
            
            <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 2rem;">
                Diterbitkan oleh: <strong>Penyelenggara Event</strong>
            </p>

            <div class="event-desc">
                <h3 style="color:#fff; margin-bottom: 0.75rem; font-size: 1.25rem;">Deskripsi Event</h3>
                <p>{!! nl2br(e($event->description)) !!}</p>
            </div>
        </div>
    </div>

    <div>
        <div class="card sidebar-card">
            <h3 style="margin-bottom: 1.5rem; text-align: center;">Informasi Tiket</h3>
            
            <div class="price-tag {{ $event->price == 0 ? 'free' : '' }}">
                {{ $event->price == 0 ? 'GRATIS' : 'Rp ' . number_format($event->price, 0, ',', '.') }}
            </div>

            <div class="quota-box">
                <div class="quota-item">
                    <span class="quota-label">Total Kuota:</span>
                    <span class="quota-val">{{ $event->quota }}</span>
                </div>
                <div class="quota-item">
                    <span class="quota-label">Terdaftar:</span>
                    <span class="quota-val accent">{{ $registeredCount }}</span>
                </div>
                <div class="quota-item">
                    <span class="quota-label">Sisa Tiket:</span>
                    <span class="quota-val {{ $isFull ? 'danger' : 'success' }}">
                        {{ $remainingCount }}
                    </span>
                </div>
            </div>

            @if($isFull)
                <div style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); color: #f87171; border-radius: 8px; padding: 0.75rem; text-align: center; font-weight: 600; margin-bottom: 1.5rem;">
                    ⚠️ Event Full (Tiket Habis)
                </div>
            @endif

            <form action="{{ route('events.register') }}" method="POST">
                @csrf
                <input type="hidden" name="event_id" value="{{ $event->id }}">
                
                @if($event->price > 0)
                    <div style="margin-bottom: 1.25rem;">
                        <label for="payment_method" style="font-size: 0.85rem; font-weight: 600; color: var(--text-muted); display: block; margin-bottom: 0.5rem;">Pilih Metode Pembayaran</label>
                        <select name="payment_method" id="payment_method" style="width: 100%; background: rgba(255, 255, 255, 0.05); border: 1px solid var(--border-card); padding: 0.65rem; border-radius: 8px; color: #fff;" required>
                            <option value="">-- Pilih Metode --</option>
                            <option value="Transfer Bank (Virtual Account)">Transfer Bank (Virtual Account)</option>
                            <option value="GoPay / ShopeePay (E-Wallet)">GoPay / ShopeePay (E-Wallet)</option>
                            <option value="Kunci Saldo Mandiri">Kunci Saldo Mandiri</option>
                        </select>
                    </div>

                    <div style="margin-bottom: 1.5rem;">
                        <label style="font-size: 0.85rem; font-weight: 600; color: var(--text-muted); display: block; margin-bottom: 0.5rem;">Aksi Simulasi</label>
                        <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                            <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.9rem; cursor: pointer; color: #fff;">
                                <input type="radio" name="payment_action" value="pay_now" checked> Bayar Sekarang (Status: Paid/Registered)
                            </label>
                            <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.9rem; cursor: pointer; color: #fff;">
                                <input type="radio" name="payment_action" value="pay_later"> Bayar Nanti (Status: Pending)
                            </label>
                        </div>
                    </div>
                @endif
                
                <button type="submit" class="btn-primary" style="width: 100%; padding: 0.85rem;" {{ $isFull ? 'disabled style=background:var(--text-muted);cursor:not-allowed;' : '' }}>
                    {{ $isFull ? 'Event Full (Tiket Habis)' : 'Daftar Sekarang' }}
                </button>
            </form>
            
            <p style="font-size: 0.8rem; color: var(--text-muted); text-align: center; margin-top: 1rem; line-height: 1.4;">
                * Pendaftaran paid event berstatus pending hingga pembayaran manual diproses.<br>
                * Jika tidak login, pendaftaran otomatis menggunakan fallback ID (User 1).
            </p>
        </div>
    </div>
</div>
@endsection

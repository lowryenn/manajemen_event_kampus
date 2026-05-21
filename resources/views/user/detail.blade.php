@extends('layouts.app')

@section('title', $event->title . ' - EventKampus')

@section('styles')
<style>
    .detail-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
        align-items: start;
    }

    .detail-image-box {
        height: 300px;
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.15), rgba(217, 70, 239, 0.15));
        border-radius: 16px;
        border: 1px solid var(--border-card);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 2rem;
        overflow: hidden;
    }

    .detail-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .detail-image-placeholder {
        font-size: 5rem;
    }

    .info-section {
        margin-bottom: 2rem;
    }

    .info-title {
        font-size: 2.25rem;
        color: #fff;
        margin-bottom: 1rem;
    }

    .meta-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
    }

    .meta-tag {
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid var(--border-card);
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.85rem;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .meta-tag-accent {
        color: var(--accent);
        border-color: rgba(217, 70, 239, 0.3);
    }

    .event-desc {
        color: var(--text-muted);
        line-height: 1.7;
        font-size: 1.05rem;
        margin-bottom: 2rem;
    }

    /* Pattern info section */
    .pattern-info-box {
        background: rgba(99, 102, 241, 0.05);
        border: 1px dashed rgba(99, 102, 241, 0.3);
        border-radius: 12px;
        padding: 1.25rem;
        margin-bottom: 2rem;
    }

    .pattern-title {
        font-size: 0.9rem;
        font-weight: 700;
        color: var(--primary);
        text-transform: uppercase;
        margin-bottom: 0.5rem;
        letter-spacing: 0.05em;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .pattern-desc {
        font-size: 0.9rem;
        color: var(--text-muted);
        line-height: 1.5;
    }

    /* Form styling */
    .register-form-box {
        position: sticky;
        top: 100px;
    }

    .form-title {
        font-size: 1.5rem;
        margin-bottom: 1.25rem;
        color: #fff;
        border-bottom: 1px solid var(--border-card);
        padding-bottom: 0.75rem;
    }

    .form-group {
        margin-bottom: 1.25rem;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .form-label {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--text-muted);
    }

    .form-select, .form-input {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid var(--border-card);
        padding: 0.75rem 1rem;
        border-radius: 8px;
        color: var(--text-main);
        font-family: var(--font-inter);
        font-size: 0.95rem;
        width: 100%;
        transition: all 0.3s ease;
    }

    .form-select:focus, .form-input:focus {
        outline: none;
        border-color: var(--primary);
    }

    .price-summary {
        background: rgba(255, 255, 255, 0.02);
        border-radius: 8px;
        padding: 1rem;
        margin: 1.5rem 0;
        border: 1px solid rgba(255, 255, 255, 0.04);
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
        color: var(--text-muted);
    }

    .summary-row.total {
        margin-bottom: 0;
        margin-top: 0.5rem;
        padding-top: 0.5rem;
        border-top: 1px solid rgba(255, 255, 255, 0.08);
        font-size: 1.15rem;
        font-weight: 700;
        color: #fff;
    }

    .btn-register {
        width: 100%;
        justify-content: center;
        padding: 0.9rem;
    }

    .registered-banner {
        background: rgba(16, 185, 129, 0.1);
        border: 1px solid rgba(16, 185, 129, 0.2);
        color: var(--success);
        padding: 1rem;
        border-radius: 8px;
        text-align: center;
        font-weight: 600;
        font-size: 0.95rem;
    }
</style>
@endsection

@section('content')
<div class="detail-grid">
    <!-- Left column: Event details -->
    <div>
        <div class="detail-image-box">
            @if($event->banner)
                <img src="{{ asset('storage/' . $event->banner) }}" alt="{{ $event->title }}" class="detail-image">
            @else
                <div class="detail-image-placeholder">
                    {{ ($factoryDetails['type'] ?? 'offline') === 'online' ? '💻' : '🏛' }}
                </div>
            @endif
        </div>

        <div class="info-section">
            <div class="meta-tags">
                <span class="meta-tag meta-tag-accent">
                    🏷 {{ strtoupper($factoryDetails['type'] ?? 'offline') }}
                </span>
                <span class="meta-tag">
                    📅 {{ $event->event_date ? $event->event_date->format('d M Y, H:i') : 'TBA' }}
                </span>
                <span class="meta-tag">
                    📍 Venue: {{ $event->location }}
                </span>
                <span class="meta-tag" style="background: rgba(16, 185, 129, 0.1); border-color: rgba(16, 185, 129, 0.2); color: #10b981;">
                    🎟 Sisa Tiket: {{ $remainingTickets }} / {{ $event->quota }}
                </span>
                <span class="meta-tag" style="background: rgba(99, 102, 241, 0.1); border-color: rgba(99, 102, 241, 0.2); color: #6366f1;">
                    👥 Terdaftar: {{ $participantsCount }} Peserta
                </span>
            </div>

            <h1 class="info-title">{{ $event->title }}</h1>
            <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1.5rem;">
                Diterbitkan oleh: <strong>{{ $event->organizer->name ?? 'Admin' }}</strong>
            </p>

            <div class="event-desc">
                <h3 style="color:#fff; margin-bottom: 0.75rem; font-size: 1.25rem;">Deskripsi Event</h3>
                <p>{!! nl2br(e($event->description)) !!}</p>
            </div>
            
            <!-- Factory Pattern Demonstration Details -->
            <div class="pattern-info-box">
                <div class="pattern-title">⚙ Factory Pattern: {{ ucfirst($factoryDetails['type'] ?? 'offline') }}Event Class</div>
                <div class="pattern-desc">
                    <p style="margin-bottom: 0.5rem;">Objek event di atas diinstansiasi secara dinamis melalui <code>EventFactory</code>.</p>
                    <p><strong>Instruksi Event (dari Factory):</strong> <br>
                    <span style="color: var(--text-main);">{{ $factoryDetails['instructions'] }}</span></p>
                    @if(isset($factoryDetails['platform']))
                        <p style="margin-top:0.5rem;"><strong>Platform Webinar:</strong> <span style="color: var(--text-main);">{{ $factoryDetails['platform'] }}</span></p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Right column: Registration Box -->
    <div class="register-form-box card">
        <h3 class="form-title">Daftar Event</h3>

        @if(auth()->check() && auth()->user()->isAdmin())
            <div class="registered-banner" style="background: rgba(99, 102, 241, 0.1); border-color: rgba(99, 102, 241, 0.2); color: var(--primary);">
                Anda adalah Admin.<br>Akses pendaftaran hanya untuk User biasa.
            </div>
        @elseif($isRegistered)
            <div class="registered-banner">
                ✔ Anda telah terdaftar dalam event ini!
                <a href="{{ route('user.registrations') }}" style="display:block; margin-top:0.5rem; font-size:0.85rem; color:var(--primary); text-decoration:underline;">Lihat Tiket & Pembayaran</a>
            </div>
        @else
            @if($errors->any())
                <div class="alert alert-danger" style="font-size: 0.85rem;">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('events.register') }}" method="POST">
                @csrf
                <input type="hidden" name="event_id" value="{{ $event->id }}">

                <div class="form-group">
                    <label for="payment_method" class="form-label">Metode Pembayaran</label>
                    <select name="payment_method" id="payment_method" class="form-select" onchange="toggleProviders()" required>
                        <option value="bank_transfer">Transfer Bank</option>
                        <option value="ewallet">E-Wallet</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="payment_provider" class="form-label">Penyedia</label>
                    <select name="payment_provider" id="payment_provider" class="form-select" required>
                        <!-- POPULATED BY JAVASCRIPT -->
                    </select>
                </div>

                <div class="price-summary">
                    <div class="summary-row">
                        <span>Biaya Registrasi</span>
                        <span>{{ $event->price == 0 ? 'Rp 0' : 'Rp ' . number_format($event->price, 0, ',', '.') }}</span>
                    </div>
                    <div class="summary-row">
                        <span>Pajak & Biaya Layanan</span>
                        <span>Rp 0</span>
                    </div>
                    <div class="summary-row total">
                        <span>Total Bayar</span>
                        <span>{{ $event->price == 0 ? 'GRATIS' : 'Rp ' . number_format($event->price, 0, ',', '.') }}</span>
                    </div>
                </div>

                @auth
                    @if($isFull)
                        <button type="button" class="btn-primary btn-register" style="background: rgba(255, 255, 255, 0.05); border: 1px solid var(--border-card); color: var(--text-muted); cursor: not-allowed;" disabled>Event Full</button>
                    @else
                        <button type="submit" class="btn-primary btn-register">Konfirmasi Pendaftaran</button>
                    @endif
                @else
                    @if($isFull)
                        <button type="button" class="btn-primary btn-register" style="background: rgba(255, 255, 255, 0.05); border: 1px solid var(--border-card); color: var(--text-muted); cursor: not-allowed;" disabled>Event Full</button>
                    @else
                        <a href="{{ route('login') }}" class="btn-primary btn-register" style="text-align: center;">Login untuk Mendaftar</a>
                    @endif
                @endauth
            </form>
        @endif
    </div>
</div>

<script>
    const providers = {
        bank_transfer: [
            { value: 'BCA', text: 'Transfer BCA' },
            { value: 'MANDIRI', text: 'Transfer Mandiri' },
            { value: 'BNI', text: 'Transfer BNI' }
        ],
        ewallet: [
            { value: 'GOPAY', text: 'GoPay QR' },
            { value: 'OVO', text: 'OVO QR' },
            { value: 'SHOPEEPAY', text: 'ShopeePay QR' }
        ]
    };

    function toggleProviders() {
        const method = document.getElementById('payment_method').value;
        const providerSelect = document.getElementById('payment_provider');
        
        providerSelect.innerHTML = '';
        
        providers[method].forEach(p => {
            const opt = document.createElement('option');
            opt.value = p.value;
            opt.text = p.text;
            providerSelect.add(opt);
        });
    }

    // Run on init
    document.addEventListener("DOMContentLoaded", function() {
        if(document.getElementById('payment_method')) {
            toggleProviders();
        }
    });
</script>
@endsection

@extends('layouts.app')

@section('title', 'Verify OTP - EventKampus')

@section('styles')
<style>
    .auth-wrapper {
        min-height: calc(100vh - 200px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 1rem;
    }

    .auth-card {
        width: 100%;
        max-width: 420px;
    }

    .auth-card .card-body { padding: 2.5rem; }

    .auth-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .auth-icon {
        width: 56px; height: 56px;
        background: linear-gradient(135deg, var(--primary-deep), var(--accent-deep));
        border-radius: var(--radius-lg);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin: 0 auto 1.25rem;
        box-shadow: 0 4px 20px rgba(99,102,241,0.3);
    }

    .auth-title {
        font-size: 1.75rem;
        font-weight: 800;
        margin-bottom: 0.4rem;
    }

    .auth-subtitle {
        color: var(--text-muted);
        font-size: 0.9rem;
    }

    .otp-input-container {
        display: flex;
        justify-content: center;
        margin: 1.5rem 0;
    }

    .otp-field {
        font-size: 2rem;
        font-weight: 800;
        letter-spacing: 0.5rem;
        text-align: center;
        font-family: monospace;
        padding: 0.75rem;
        max-width: 240px;
        border: 2px solid var(--border-card);
        border-radius: var(--radius-md);
        background-color: rgba(255,255,255,0.02);
        color: var(--text-primary);
        outline: none;
        transition: all var(--transition-fast);
    }

    .otp-field:focus {
        border-color: var(--primary);
        box-shadow: 0 0 10px rgba(99,102,241,0.2);
    }

    .auth-footer {
        text-align: center;
        margin-top: 1.75rem;
        padding-top: 1.5rem;
        border-top: 1px solid var(--border-subtle);
        font-size: 0.875rem;
        color: var(--text-muted);
    }

    .auth-link {
        color: var(--primary);
        font-weight: 600;
    }

    .auth-link:hover { color: var(--accent); }
</style>
@endsection

@section('content')
<div class="auth-wrapper">
    <div class="auth-card card animate-in">
        <div class="card-body">
            <div class="auth-header">
                <div class="auth-icon">✉️</div>
                <h1 class="auth-title">Verify OTP</h1>
                <p class="auth-subtitle">We have sent a 6-digit OTP code to <br><strong style="color: var(--text-primary);">{{ $email }}</strong></p>
            </div>

            @if($errors->any())
                <div class="alert alert-danger" style="font-size:0.85rem; flex-direction:column; align-items:flex-start;">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            @if(session('success') || ! $errors->any())
                <div class="alert alert-success" style="font-size:0.85rem; margin-bottom:1.5rem;">
                    <span>✓</span> Kode OTP telah berhasil dikirim ke email Anda!
                </div>
            @endif

            <form action="{{ route('register.otp') }}" method="POST">
                @csrf

                <div class="form-group" style="text-align: center;">
                    <label for="otp" class="form-label" style="display:block; margin-bottom:0.5rem;">Enter 6-Digit Code</label>
                    <div class="otp-input-container">
                        <input type="text" name="otp" id="otp" class="otp-field" placeholder="123456" maxlength="6" pattern="\d{6}" required autofocus autocomplete="one-time-code">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-lg" style="margin-top:0.5rem;">Verify & Register</button>
            </form>

            <div class="auth-footer">
                Wrong email address? <a href="{{ route('register') }}" class="auth-link">Start over</a>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Create Account - EventKampus')

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
        max-width: 480px;
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

    .role-info {
        font-size: 0.8rem;
        color: var(--text-faint);
        margin-top: 0.25rem;
    }
</style>
@endsection

@section('content')
<div class="auth-wrapper">
    <div class="auth-card card animate-in">
        <div class="card-body">
            <div class="auth-header">
                <div class="auth-icon">🚀</div>
                <h1 class="auth-title">Create Account</h1>
                <p class="auth-subtitle">Join the campus event community</p>
            </div>

            @if($errors->any())
                <div class="alert alert-danger" style="font-size:0.85rem; flex-direction:column; align-items:flex-start;">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" name="name" id="name" class="form-input" placeholder="e.g. Ahmad Fauzi" required value="{{ old('name') }}">
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" name="email" id="email" class="form-input" placeholder="you@university.ac.id" required value="{{ old('email') }}">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="text" name="phone" id="phone" class="form-input" placeholder="08xxxxxxxxxx" value="{{ old('phone') }}">
                    </div>

                    <div class="form-group">
                        <label for="role" class="form-label">Account Type</label>
                        <select name="role" id="role" class="form-input" required>
                            <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>Student / Attendee</option>
                            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" id="password" class="form-input" placeholder="Min 6 characters" required>
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-input" placeholder="Re-enter password" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-lg" style="margin-top:0.5rem;">Create Account</button>
            </form>

            <div class="auth-footer">
                Already have an account? <a href="{{ route('login') }}" class="auth-link">Sign In</a>
            </div>
        </div>
    </div>
</div>
@endsection

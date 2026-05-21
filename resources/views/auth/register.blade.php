@extends('layouts.app')

@section('title', 'Register - Sistem Manajemen Event Kampus')

@section('styles')
<style>
    .auth-container {
        max-width: 500px;
        margin: 2rem auto;
    }
    
    .auth-header {
        text-align: center;
        margin-bottom: 2rem;
    }
    
    .auth-title {
        font-size: 2rem;
        background: linear-gradient(135deg, var(--text-main), var(--text-muted));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 0.5rem;
    }
    
    .form-group {
        margin-bottom: 1.25rem;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .form-row {
        display: flex;
        gap: 1rem;
    }

    .form-row .form-group {
        flex: 1;
    }
    
    .form-label {
        font-size: 0.9rem;
        font-weight: 500;
        color: var(--text-muted);
    }
    
    .form-input {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid var(--border-card);
        padding: 0.8rem 1rem;
        border-radius: 8px;
        color: var(--text-main);
        font-family: var(--font-inter);
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }
    
    .form-input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 10px rgba(99, 102, 241, 0.25);
    }

    .form-select {
        background: var(--bg-secondary);
        cursor: pointer;
    }
    
    .btn-auth {
        width: 100%;
        padding: 0.9rem;
        margin-top: 1rem;
        font-size: 1rem;
        justify-content: center;
    }
    
    .auth-footer {
        text-align: center;
        margin-top: 1.5rem;
        font-size: 0.9rem;
        color: var(--text-muted);
    }
    
    .auth-link {
        color: var(--primary);
        font-weight: 600;
    }
    
    .auth-link:hover {
        color: var(--accent);
    }
</style>
@endsection

@section('content')
<div class="auth-container">
    <div class="card">
        <div class="auth-header">
            <h2 class="auth-title">Create Account</h2>
            <p style="color: var(--text-muted); font-size: 0.95rem;">Join the campus event hub today</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger" style="font-size: 0.85rem; flex-direction: column; align-items: flex-start;">
                @foreach($errors->all() as $error)
                    <div>• {{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form action="{{ route('register') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" name="name" id="name" class="form-input" placeholder="e.g. John Doe" required value="{{ old('name') }}">
            </div>
            
            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" name="email" id="email" class="form-input" placeholder="e.g. name@test.com" required value="{{ old('email') }}">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="text" name="phone" id="phone" class="form-input" placeholder="e.g. 08123456789" value="{{ old('phone') }}">
                </div>

                <div class="form-group">
                    <label for="role" class="form-label">Register As</label>
                    <select name="role" id="role" class="form-input form-select" required>
                        <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>User (Student / Attendee)</option>
                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin (Event Organizer)</option>
                    </select>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-input" placeholder="Min. 6 characters" required>
                </div>
                
                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-input" placeholder="Confirm password" required>
                </div>
            </div>
            
            <button type="submit" class="btn-primary btn-auth">Create Account</button>
        </form>

        <div class="auth-footer">
            Already have an account? <a href="{{ route('login') }}" class="auth-link">Sign In here</a>
        </div>
    </div>
</div>
@endsection

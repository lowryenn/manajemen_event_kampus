@extends('layouts.app')

@section('title', 'Login - Sistem Manajemen Event Kampus')

@section('styles')
<style>
    .auth-container {
        max-width: 450px;
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
        margin-bottom: 1.5rem;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .form-label {
        font-size: 0.9rem;
        font-weight: 500;
        color: var(--text-muted);
    }
    
    .form-input {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid var(--border-card);
        padding: 0.85rem 1rem;
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

    .demo-credentials {
        margin-top: 2rem;
        background: rgba(255, 255, 255, 0.02);
        border: 1px dashed var(--border-card);
        border-radius: 12px;
        padding: 1rem;
    }

    .demo-title {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--accent);
        text-transform: uppercase;
        margin-bottom: 0.5rem;
        letter-spacing: 0.05em;
    }

    .demo-account {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.03);
    }

    .demo-account:last-child {
        border-bottom: none;
    }

    .demo-info {
        font-size: 0.8rem;
        color: var(--text-muted);
    }

    .demo-btn {
        background: rgba(99, 102, 241, 0.1);
        border: 1px solid rgba(99, 102, 241, 0.2);
        color: var(--primary);
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.75rem;
        cursor: pointer;
        font-family: var(--font-inter);
        font-weight: 500;
    }

    .demo-btn:hover {
        background: var(--primary);
        color: #fff;
    }
</style>
@endsection

@section('content')
<div class="auth-container">
    <div class="card">
        <div class="auth-header">
            <h2 class="auth-title">Welcome Back</h2>
            <p style="color: var(--text-muted); font-size: 0.95rem;">Login to join and manage campus events</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger" style="font-size: 0.85rem; flex-direction: column; align-items: flex-start;">
                @foreach($errors->all() as $error)
                    <div>• {{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" id="login-form">
            @csrf
            
            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" name="email" id="email" class="form-input" placeholder="name@domain.com" required value="{{ old('email') }}">
            </div>
            
            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-input" placeholder="••••••••" required>
            </div>
            
            <button type="submit" class="btn-primary btn-auth">Sign In</button>
        </form>

        <div class="auth-footer">
            Don't have an account? <a href="{{ route('register') }}" class="auth-link">Register here</a>
        </div>

        <!-- Demo Accounts Helper for Easy Testing -->
        <div class="demo-credentials">
            <div class="demo-title">🔑 Quick Login Accounts</div>
            <div class="demo-account">
                <div class="demo-info">
                    <strong>Admin Role:</strong><br>admin@test.com (pass: password)
                </div>
                <button type="button" class="demo-btn" onclick="fillForm('admin@test.com')">Use Admin</button>
            </div>
            <div class="demo-account">
                <div class="demo-info">
                    <strong>User Role:</strong><br>user@test.com (pass: password)
                </div>
                <button type="button" class="demo-btn" onclick="fillForm('user@test.com')">Use User</button>
            </div>
        </div>
    </div>
</div>

<script>
    function fillForm(email) {
        document.getElementById('email').value = email;
        document.getElementById('password').value = 'password';
    }
</script>
@endsection

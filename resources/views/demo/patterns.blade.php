@extends('layouts.app')

@section('title', 'Design Patterns Live Demo - EventKampus')

@section('styles')
<style>
    .demo-header {
        text-align: center;
        margin-bottom: 2.5rem;
    }

    .demo-title {
        font-size: 2.5rem;
        background: linear-gradient(135deg, var(--accent), var(--primary));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 0.5rem;
    }

    .demo-console {
        background: #0f172a;
        border: 1px solid var(--border-card);
        border-radius: 16px;
        padding: 2rem;
        font-family: var(--font-inter);
        color: var(--text-main);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4);
        line-height: 1.6;
    }

    .demo-console h3 {
        color: var(--accent);
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        padding-bottom: 0.5rem;
        margin-top: 2rem;
        margin-bottom: 1rem;
        font-size: 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .demo-console h3:first-of-type {
        margin-top: 0;
    }

    .demo-console pre {
        background: rgba(0, 0, 0, 0.3);
        padding: 1rem;
        border-radius: 8px;
        color: #38bdf8;
        font-family: monospace;
        font-size: 0.85rem;
        overflow-x: auto;
        margin: 0.75rem 0;
        border: 1px solid rgba(255, 255, 255, 0.02);
    }

    .back-btn-row {
        margin-top: 2rem;
        text-align: center;
    }
</style>
@endsection

@section('content')
<div class="demo-header">
    <h1 class="demo-title">Design Patterns Execution Demo</h1>
    <p style="color:var(--text-muted); font-size:1.05rem; max-width:650px; margin:0 auto;">
        Halaman ini menjalankan ke-6 Design Pattern secara sinkron dalam satu request siklus hidup PHP untuk memvalidasi struktur, alur kerja, dan outputnya.
    </p>
</div>

<div class="demo-console">
    @foreach($logs as $log)
        <div>{!! $log !!}</div>
    @endforeach
</div>

<div class="back-btn-row">
    @auth
        @if(auth()->user()->isAdmin())
            <a href="{{ route('admin.dashboard') }}" class="btn-primary">Kembali ke Dashboard Admin</a>
        @else
            <a href="{{ route('user.home') }}" class="btn-primary">Kembali ke Home Event</a>
        @endif
    @else
        <a href="{{ route('login') }}" class="btn-primary">Kembali ke Login</a>
    @endauth
</div>
@endsection

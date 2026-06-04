@extends('layouts.app')

@section('title', 'Participants - EventKampus')

@section('styles')
<style>
    .page-header {
        margin-bottom: 2rem;
    }

    .page-header h1 {
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: 0.3rem;
    }

    .page-header p {
        color: var(--text-muted);
        font-size: 0.9rem;
    }

    .admin-tabs {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 2rem;
        border-bottom: 1px solid var(--border-subtle);
    }

    .admin-tab {
        padding: 0.65rem 1.25rem;
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--text-muted);
        border-bottom: 2px solid transparent;
        margin-bottom: -1px;
        transition: all var(--transition-fast);
        cursor: pointer;
        text-decoration: none;
    }

    .admin-tab:hover { color: var(--text-primary); }
    .admin-tab.active { color: var(--primary); border-bottom-color: var(--primary); }

    .participant-cell {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .participant-avatar {
        width: 36px; height: 36px;
        border-radius: var(--radius-full);
        background: linear-gradient(135deg, var(--primary-glow), var(--accent-glow));
        border: 1px solid var(--border-card);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        font-weight: 700;
        color: var(--primary);
        flex-shrink: 0;
    }

    .participant-name {
        font-weight: 600;
        color: var(--text-primary);
        font-size: 0.9rem;
    }

    .participant-id {
        font-size: 0.72rem;
        color: var(--text-faint);
        font-family: monospace;
    }

    .stats-mini {
        display: flex;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .stats-mini-item {
        font-size: 0.85rem;
        color: var(--text-muted);
    }

    .stats-mini-item strong {
        color: var(--text-primary);
        font-weight: 700;
    }
</style>
@endsection

@section('content')
<div class="page-header animate-in">
    <h1>Participants</h1>
    <p>All registered participants across campus events</p>
</div>

<div class="admin-tabs animate-in animate-delay-1">
    <a href="{{ route('admin.dashboard') }}" class="admin-tab">Events</a>
    <a href="{{ route('admin.participants') }}" class="admin-tab active">Participants</a>
</div>

@php
    $activeCount = $registrations->where('status', 'registered')->count();
    $pendingCount = $registrations->where('status', 'pending')->count();
    $cancelledCount = $registrations->where('status', 'cancelled')->count();
@endphp

<div class="stats-mini animate-in animate-delay-1">
    <span class="stats-mini-item">Total: <strong>{{ $registrations->count() }}</strong></span>
    <span class="stats-mini-item">Active: <strong style="color:var(--success);">{{ $activeCount }}</strong></span>
    <span class="stats-mini-item">Pending: <strong style="color:var(--warning);">{{ $pendingCount }}</strong></span>
    <span class="stats-mini-item">Cancelled: <strong style="color:var(--danger);">{{ $cancelledCount }}</strong></span>
</div>

<div class="table-wrap animate-in animate-delay-2">
    @if($registrations->isEmpty())
        <div class="empty-state">
            <div class="empty-state-icon">👥</div>
            <h3>No Participants Yet</h3>
            <p>Registration data will appear here once users sign up for events.</p>
        </div>
    @else
        <table>
            <thead>
                <tr>
                    <th>Participant</th>
                    <th>Contact</th>
                    <th>Event</th>
                    <th>Payment</th>
                    <th>Registered</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($registrations as $reg)
                    <tr>
                        <td>
                            <div class="participant-cell">
                                <div class="participant-avatar">
                                    {{ strtoupper(substr($reg->user->name ?? 'U', 0, 1)) }}
                                </div>
                                <div>
                                    <div class="participant-name">{{ $reg->user->name }}</div>
                                    <div class="participant-id">#{{ $reg->user_id }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span style="font-size:0.85rem;">{{ $reg->user->email }}</span>
                            @if($reg->user->phone)
                                <br><span style="font-size:0.78rem; color:var(--text-faint);">{{ $reg->user->phone }}</span>
                            @endif
                        </td>
                        <td>
                            <div>
                                <span style="font-weight:600; color:var(--text-primary); font-size:0.88rem;">{{ Str::limit($reg->event->name ?? 'Deleted', 30) }}</span>
                                <br>
                                <span class="badge badge-{{ ($reg->event->price ?? 0) == 0 ? 'free' : 'paid' }}" style="margin-top:0.2rem;">
                                    {{ ($reg->event->price ?? 0) == 0 ? 'Free' : 'Rp ' . number_format($reg->event->price ?? 0, 0, ',', '.') }}
                                </span>
                            </div>
                        </td>
                        <td>
                            @if($reg->payment_method)
                                <span style="font-size:0.82rem;">{{ $reg->payment_method }}</span>
                            @else
                                <span style="font-size:0.82rem; color:var(--text-faint);">—</span>
                            @endif
                        </td>
                        <td style="white-space:nowrap; font-size:0.85rem;">
                            {{ $reg->created_at->format('d M Y') }}
                            <br><span style="font-size:0.75rem; color:var(--text-faint);">{{ $reg->created_at->format('H:i') }}</span>
                        </td>
                        <td>
                            <span class="badge badge-{{ $reg->status }}">{{ strtoupper($reg->status) }}</span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection

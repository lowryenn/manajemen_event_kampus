@extends('layouts.app')

@section('title', 'Admin Dashboard - EventKampus')

@section('styles')
<style>
    /* ============ HEADER ============ */
    .dash-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .dash-header h1 {
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: 0.3rem;
    }

    .dash-header p {
        color: var(--text-muted);
        font-size: 0.9rem;
    }

    /* ============ STATS GRID ============ */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
        margin-bottom: 2.5rem;
    }

    @media (max-width: 992px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 480px) {
        .stats-grid { grid-template-columns: 1fr; }
    }

    .stat-card {
        padding: 1.5rem;
        display: flex;
        align-items: flex-start;
        gap: 1rem;
    }

    .stat-icon {
        width: 48px; height: 48px;
        border-radius: var(--radius-md);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        flex-shrink: 0;
    }

    .stat-icon.purple { background: var(--accent-glow); border: 1px solid rgba(168,85,247,0.2); }
    .stat-icon.blue { background: var(--info-bg); border: 1px solid rgba(88,166,255,0.2); }
    .stat-icon.green { background: var(--success-bg); border: 1px solid var(--success-border); }
    .stat-icon.red { background: var(--danger-bg); border: 1px solid var(--danger-border); }

    .stat-info { flex: 1; }

    .stat-label {
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--text-faint);
        text-transform: uppercase;
        letter-spacing: 0.06em;
        margin-bottom: 0.25rem;
    }

    .stat-value {
        font-family: var(--font-display);
        font-size: 2rem;
        font-weight: 800;
        color: var(--text-primary);
        line-height: 1;
    }

    /* ============ SECTION HEADER ============ */
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.25rem;
    }

    .section-title {
        font-size: 1.25rem;
        font-weight: 700;
    }

    /* ============ TABLE EXTENSIONS ============ */
    .event-name-cell {
        display: flex;
        align-items: center;
        gap: 0.85rem;
    }

    .event-thumb {
        width: 42px; height: 42px;
        border-radius: var(--radius-sm);
        background: linear-gradient(135deg, var(--primary-glow), var(--accent-glow));
        border: 1px solid var(--border-card);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    .event-name-text {
        font-weight: 600;
        color: var(--text-primary);
        font-size: 0.9rem;
    }

    .event-name-sub {
        font-size: 0.75rem;
        color: var(--text-faint);
        margin-top: 0.15rem;
    }

    .actions-cell {
        display: flex;
        gap: 0.4rem;
        align-items: center;
    }

    .btn-edit {
        background: transparent;
        border: 1px solid rgba(99,102,241,0.3);
        color: var(--primary);
        padding: 0.35rem 0.7rem;
        border-radius: var(--radius-sm);
        font-size: 0.78rem;
        font-weight: 600;
        cursor: pointer;
        transition: all var(--transition-fast);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        font-family: var(--font-body);
    }

    .btn-edit:hover {
        background: var(--primary-glow);
        border-color: var(--primary);
    }

    .btn-delete {
        background: transparent;
        border: 1px solid var(--danger-border);
        color: var(--danger);
        padding: 0.35rem 0.7rem;
        border-radius: var(--radius-sm);
        font-size: 0.78rem;
        font-weight: 600;
        cursor: pointer;
        transition: all var(--transition-fast);
        font-family: var(--font-body);
    }

    .btn-delete:hover {
        background: var(--danger-bg);
        border-color: var(--danger);
    }

    /* Quota mini bar */
    .mini-quota {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
        min-width: 80px;
    }

    .mini-quota-text {
        font-size: 0.75rem;
        color: var(--text-muted);
    }

    .mini-quota-bar {
        width: 100%;
        height: 4px;
        background: rgba(255,255,255,0.06);
        border-radius: 2px;
        overflow: hidden;
    }

    .mini-quota-fill {
        height: 100%;
        border-radius: 2px;
    }

    /* Admin nav tabs */
    .admin-tabs {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 2rem;
        border-bottom: 1px solid var(--border-subtle);
        padding-bottom: 0;
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

    .admin-tab.active {
        color: var(--primary);
        border-bottom-color: var(--primary);
    }
</style>
@endsection

@section('content')
{{-- HEADER --}}
<div class="dash-header animate-in">
    <div>
        <h1>Dashboard</h1>
        <p>Welcome back, {{ auth()->user()->name }}. Manage your campus events here.</p>
    </div>
    <a href="{{ route('admin.events.create') }}" class="btn btn-primary">+ Create Event</a>
</div>

{{-- ADMIN TABS --}}
<div class="admin-tabs animate-in animate-delay-1">
    <a href="{{ route('admin.dashboard') }}" class="admin-tab active">Events</a>
    <a href="{{ route('admin.participants') }}" class="admin-tab">Participants</a>
</div>

{{-- STATS CARDS --}}
<div class="stats-grid animate-in animate-delay-1">
    <div class="card stat-card">
        <div class="stat-icon purple">📊</div>
        <div class="stat-info">
            <div class="stat-label">Total Events</div>
            <div class="stat-value">{{ $stats['total_events'] }}</div>
        </div>
    </div>
    <div class="card stat-card">
        <div class="stat-icon blue">👥</div>
        <div class="stat-info">
            <div class="stat-label">Total Users</div>
            <div class="stat-value">{{ $stats['total_users'] }}</div>
        </div>
    </div>
    <div class="card stat-card">
        <div class="stat-icon green">🟢</div>
        <div class="stat-info">
            <div class="stat-label">Active Events</div>
            <div class="stat-value">{{ $stats['active_events'] }}</div>
        </div>
    </div>
    <div class="card stat-card">
        <div class="stat-icon red">🔴</div>
        <div class="stat-info">
            <div class="stat-label">Fully Booked</div>
            <div class="stat-value">{{ $stats['full_events'] }}</div>
        </div>
    </div>
</div>

{{-- EVENTS TABLE --}}
<div class="section-header animate-in animate-delay-2">
    <h2 class="section-title">All Events</h2>
    <span style="font-size:0.8rem; color:var(--text-faint);">{{ $events->count() }} total</span>
</div>

<div class="table-wrap animate-in animate-delay-2">
    @if($events->isEmpty())
        <div class="empty-state">
            <div class="empty-state-icon">📁</div>
            <h3>No Events Yet</h3>
            <p>Create your first event to get started.</p>
            <a href="{{ route('admin.events.create') }}" class="btn btn-primary">Create Event</a>
        </div>
    @else
        <table>
            <thead>
                <tr>
                    <th>Event</th>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Price</th>
                    <th>Quota</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($events as $event)
                    @php
                        $regCount = $event->registrations_count;
                        $pct = $event->quota > 0 ? round(($regCount / $event->quota) * 100) : 0;
                        $barColor = $pct >= 90 ? 'var(--danger)' : ($pct >= 60 ? 'var(--warning)' : 'var(--success)');
                        $isFull = $regCount >= $event->quota;
                    @endphp
                    <tr>
                        <td>
                            <div class="event-name-cell">
                                <div class="event-thumb">{{ $event->category_icon }}</div>
                                <div>
                                    <div class="event-name-text">{{ Str::limit($event->name, 35) }}</div>
                                    <div class="event-name-sub">{{ $event->category }} · {{ ucfirst($event->type) }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="white-space:nowrap;">
                            {{ $event->date ? $event->date->format('d M Y') : 'TBA' }}
                            @if($event->date)
                                <br><span style="font-size:0.75rem; color:var(--text-faint);">{{ $event->date->format('H:i') }}</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge badge-{{ $event->type }}">{{ ucfirst($event->type) }}</span>
                        </td>
                        <td>
                            <span class="badge badge-{{ $event->price == 0 ? 'free' : 'paid' }}">
                                {{ $event->price == 0 ? 'Free' : 'Rp ' . number_format($event->price, 0, ',', '.') }}
                            </span>
                        </td>
                        <td>
                            <div class="mini-quota">
                                <span class="mini-quota-text">{{ $regCount }} / {{ $event->quota }}</span>
                                <div class="mini-quota-bar">
                                    <div class="mini-quota-fill" style="width:{{ $pct }}%; background:{{ $barColor }};"></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($isFull)
                                <span class="badge badge-full">Full</span>
                            @elseif($event->status_label === 'completed')
                                <span class="badge badge-completed">Completed</span>
                            @else
                                <span class="badge badge-published">Published</span>
                            @endif
                        </td>
                        <td>
                            <div class="actions-cell">
                                <a href="{{ route('admin.events.edit', $event->id) }}" class="btn-edit">Edit</a>
                                <form action="{{ route('admin.events.destroy', $event->id) }}" method="POST" onsubmit="return confirm('Delete this event permanently?')" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection

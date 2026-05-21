@extends('layouts.app')

@section('title', 'Admin Dashboard - EventKampus')

@section('styles')
<style>
    .admin-title-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
        margin-bottom: 2.5rem;
    }

    .stat-card {
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .stat-value {
        font-family: var(--font-outfit);
        font-size: 2.5rem;
        font-weight: 800;
        background: linear-gradient(135deg, #fff, var(--primary));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .stat-label {
        color: var(--text-muted);
        font-size: 0.95rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .dashboard-subtitle {
        font-size: 1.5rem;
        margin-bottom: 1.25rem;
        color: #fff;
    }

    /* Table styling */
    .table-container {
        background: var(--bg-card);
        border: 1px solid var(--border-card);
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 2rem;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
        font-size: 0.95rem;
    }

    th {
        background: rgba(255, 255, 255, 0.02);
        padding: 1rem 1.5rem;
        color: var(--text-muted);
        font-weight: 600;
        border-bottom: 1px solid var(--border-card);
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.05em;
    }

    td {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.03);
        color: var(--text-main);
    }

    tr:last-child td {
        border-bottom: none;
    }

    tr:hover td {
        background: rgba(255, 255, 255, 0.01);
    }

    .btn-delete {
        background: transparent;
        border: 1px solid rgba(239, 68, 68, 0.4);
        color: #ef4444;
        padding: 0.4rem 0.8rem;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.85rem;
        transition: all 0.3s ease;
    }

    .btn-delete:hover {
        background: rgba(239, 68, 68, 0.1);
        border-color: #ef4444;
    }

    .btn-edit {
        background: transparent;
        border: 1px solid rgba(99, 102, 241, 0.4);
        color: #6366f1;
        padding: 0.4rem 0.8rem;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.85rem;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s ease;
    }

    .btn-edit:hover {
        background: rgba(99, 102, 241, 0.1);
        border-color: #6366f1;
    }

    .thumbnail-placeholder {
        width: 45px;
        height: 45px;
        border-radius: 8px;
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.2), rgba(217, 70, 239, 0.2));
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }
</style>
@endsection

@section('content')
<div class="admin-title-row">
    <div>
        <h1 style="color:#fff; font-size:2.25rem;">Dashboard Kelola Event</h1>
        <p style="color:var(--text-muted);">Selamat datang, {{ auth()->user()->name }}! Kelola semua agenda event kampus di sini.</p>
    </div>
    <a href="{{ route('admin.events.create') }}" class="btn-primary">➕ Buat Event Baru</a>
</div>

<!-- Metrics Stats -->
<div class="stats-grid">
    <div class="card stat-card">
        <span class="stat-label">Total Event Aktif</span>
        <span class="stat-value">{{ $stats['total_events'] }}</span>
    </div>
    <div class="card stat-card">
        <span class="stat-label">Total Mahasiswa Terdaftar</span>
        <span class="stat-value">{{ $stats['total_users'] }}</span>
    </div>
    <div class="card stat-card">
        <span class="stat-label">Total Partisipasi Event</span>
        <span class="stat-value">{{ $stats['total_registrations'] }}</span>
    </div>
</div>

<!-- Events Table List -->
<h2 class="dashboard-subtitle">Daftar Event Terbit</h2>
<div class="table-container">
    @if($events->isEmpty())
        <div style="text-align: center; padding: 4rem 2rem; color: var(--text-muted);">
            <span style="font-size: 2.5rem;">📁</span>
            <h3 style="margin-top: 1rem; color: #fff;">Belum ada event terbit</h3>
            <p style="margin-top: 0.25rem;">Klik tombol "Buat Event Baru" di atas untuk menambahkan event pertama Anda.</p>
        </div>
    @else
        <table>
            <thead>
                <tr>
                    <th>Event</th>
                    <th>Tanggal</th>
                    <th>Lokasi / Tipe</th>
                    <th>Harga</th>
                    <th>Registran</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($events as $event)
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 1rem;">
                                <div class="thumbnail-placeholder">
                                    {{ $event->type === 'online' ? '💻' : '🏛' }}
                                </div>
                                <div>
                                    <strong style="color: #fff;">{{ $event->name }}</strong><br>
                                    <span style="font-size: 0.8rem; color: var(--text-muted);">Kuota: {{ $event->quota }}</span>
                                </div>
                            </div>
                        </td>
                        <td>{{ $event->date ? $event->date->format('d M Y, H:i') : 'TBA' }}</td>
                        <td>
                            {{ $event->type === 'online' ? 'Online Webinar' : 'Offline Venue' }}<br>
                            <span style="font-size: 0.8rem; color: var(--text-muted);">{{ Str::limit($event->location, 25) }}</span>
                        </td>
                        <td>
                            <strong>{{ $event->price == 0 ? 'Gratis' : 'Rp ' . number_format($event->price, 0, ',', '.') }}</strong>
                        </td>
                        <td>
                            <span class="badge badge-user">{{ $event->registrations_count }} Peserta</span>
                        </td>
                        <td>
                            <div style="display: flex; gap: 0.5rem;">
                                <a href="{{ route('admin.events.edit', $event->id) }}" class="btn-edit">Edit</a>
                                <form action="{{ route('admin.events.destroy', $event->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus event ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete">Hapus</button>
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

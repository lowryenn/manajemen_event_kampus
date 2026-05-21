@extends('layouts.app')

@section('title', 'Daftar Peserta Event - EventKampus')

@section('styles')
<style>
    .page-title {
        font-size: 2.25rem;
        color: #fff;
        margin-bottom: 0.5rem;
    }

    .table-container {
        background: var(--bg-card);
        border: 1px solid var(--border-card);
        border-radius: 16px;
        overflow: hidden;
        margin-top: 2rem;
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
</style>
@endsection

@section('content')
<h1 class="page-title">Daftar Peserta Event</h1>
<p style="color:var(--text-muted);">Melihat seluruh data mahasiswa yang telah terdaftar pada event-event kampus.</p>

<div class="table-container">
    @if($registrations->isEmpty())
        <div style="text-align: center; padding: 4rem 2rem; color: var(--text-muted);">
            <span style="font-size: 2.5rem;">👥</span>
            <h3 style="margin-top: 1rem; color: #fff;">Belum ada peserta terdaftar</h3>
            <p style="margin-top: 0.25rem;">Data registrasi peserta baru akan muncul di sini setelah user mendaftar pada event.</p>
        </div>
    @else
        <table>
            <thead>
                <tr>
                    <th>Nama Peserta</th>
                    <th>Email & Telepon</th>
                    <th>Event yang Diikuti</th>
                    <th>Tanggal Daftar</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($registrations as $reg)
                    <tr>
                        <td>
                            <strong style="color: #fff;">{{ $reg->user->name }}</strong><br>
                            <span style="font-size: 0.8rem; color: var(--text-muted);">User ID: #{{ $reg->user_id }}</span>
                        </td>
                        <td>
                            {{ $reg->user->email }}<br>
                            <span style="font-size: 0.85rem; color: var(--text-muted);">{{ $reg->user->phone ?? '-' }}</span>
                        </td>
                        <td>
                            <strong style="color: var(--accent);">{{ $reg->event->name ?? 'Event Deleted' }}</strong><br>
                            <span style="font-size: 0.85rem; color: var(--text-muted);">Harga: Rp {{ number_format($reg->event->price ?? 0, 0, ',', '.') }}</span>
                        </td>
                        <td>
                            {{ $reg->created_at->format('d M Y, H:i') }}
                        </td>
                        <td>
                            <span class="badge" style="background: {{ $reg->status === 'registered' ? 'rgba(16, 185, 129, 0.1)' : 'rgba(239, 68, 68, 0.1)' }}; color: {{ $reg->status === 'registered' ? '#10b981' : '#ef4444' }}; border: 1px solid {{ $reg->status === 'registered' ? 'rgba(16, 185, 129, 0.2)' : 'rgba(239, 68, 68, 0.2)' }}; padding: 0.25rem 0.5rem; border-radius: 6px; font-size: 0.8rem; font-weight: 600;">
                                {{ strtoupper($reg->status) }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection

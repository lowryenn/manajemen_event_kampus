@extends('layouts.app')

@section('title', 'Edit Event - EventKampus')

@section('styles')
<style>
    .form-container {
        max-width: 700px;
        margin: 0 auto;
    }

    .form-header {
        margin-bottom: 2rem;
    }

    .form-header-title {
        font-size: 2.25rem;
        color: #fff;
        margin-bottom: 0.5rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .form-row {
        display: flex;
        gap: 1.5rem;
    }

    .form-row .form-group {
        flex: 1;
    }

    .form-label {
        font-size: 0.9rem;
        font-weight: 600;
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

    textarea.form-input {
        resize: vertical;
        min-height: 120px;
    }

    .form-select {
        background: var(--bg-secondary);
        cursor: pointer;
    }

    .btn-row {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        margin-top: 2rem;
    }

    /* Helper info for factory pattern */
    .factory-helper-box {
        margin-top: 1rem;
        background: rgba(99, 102, 241, 0.03);
        border: 1px solid rgba(99, 102, 241, 0.15);
        border-radius: 8px;
        padding: 1rem;
        font-size: 0.85rem;
        color: var(--text-muted);
    }
</style>
@endsection

@section('content')
<div class="form-container">
    <div class="form-header">
        <h1 class="form-header-title">Edit Event</h1>
        <p style="color:var(--text-muted);">Perbarui detail event di bawah ini.</p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger" style="font-size: 0.85rem; flex-direction: column; align-items: flex-start;">
            @foreach($errors->all() as $error)
                <div>• {{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="card">
        <form action="{{ route('admin.events.update', $event->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name" class="form-label">Nama Event</label>
                <input type="text" name="name" id="name" class="form-input" placeholder="e.g. Seminar Nasional Web Core & Security" required value="{{ old('name', $event->name) }}">
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Deskripsi Event</label>
                <textarea name="description" id="description" class="form-input" placeholder="Tuliskan detail event, materi, pembicara, dsb." required>{{ old('description', $event->description) }}</textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="type" class="form-label">Tipe Event</label>
                    <select name="type" id="type" class="form-input form-select" onchange="adjustLocationPlaceholder()" required>
                        <option value="online" {{ old('type', $event->type) === 'online' ? 'selected' : '' }}>Online (Webinar / Zoom)</option>
                        <option value="offline" {{ old('type', $event->type) === 'offline' ? 'selected' : '' }}>Offline (Fisik / Venue)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="location" class="form-label">Lokasi / Link Zoom</label>
                    <input type="text" name="location" id="location" class="form-input" placeholder="e.g. https://zoom.us/j/999888777" required value="{{ old('location', $event->location) }}">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="date" class="form-label">Tanggal Pelaksanaan</label>
                    <input type="datetime-local" name="date" id="date" class="form-input" required value="{{ old('date', $event->date ? $event->date->format('Y-m-d\TH:i') : '') }}">
                </div>

                <div class="form-group">
                    <label for="price" class="form-label">Harga Tiket (Rupiah)</label>
                    <input type="number" name="price" id="price" class="form-input" placeholder="Masukkan 0 jika gratis" min="0" required value="{{ old('price', $event->price) }}">
                </div>

                <div class="form-group">
                    <label for="quota" class="form-label">Kuota Peserta</label>
                    <input type="number" name="quota" id="quota" class="form-input" placeholder="e.g. 100" min="1" required value="{{ old('quota', $event->quota) }}">
                </div>
            </div>

            <div class="btn-row">
                <a href="{{ route('admin.dashboard') }}" class="btn-secondary" style="padding: 0.85rem 1.5rem;">Batal</a>
                <button type="submit" class="btn-primary" style="padding: 0.85rem 1.5rem;">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function adjustLocationPlaceholder() {
        const type = document.getElementById('type').value;
        const locInput = document.getElementById('location');
        if (type === 'online') {
            locInput.placeholder = 'e.g. https://zoom.us/j/999888777';
        } else {
            locInput.placeholder = 'e.g. Auditorium Gedung C Lantai 3';
        }
    }

    // Initialize placeholder
    document.addEventListener("DOMContentLoaded", function() {
        adjustLocationPlaceholder();
    });
</script>
@endsection

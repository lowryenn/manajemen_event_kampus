@extends('layouts.app')

@section('title', 'Edit Event - EventKampus')

@section('styles')
<style>
    .form-page {
        max-width: 720px;
        margin: 0 auto;
    }

    .form-page-header {
        margin-bottom: 2rem;
    }

    .form-page-header h1 {
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: 0.3rem;
    }

    .form-page-header p {
        color: var(--text-muted);
        font-size: 0.9rem;
    }

    .form-card .card-body { padding: 2rem; }

    .form-section-label {
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--text-faint);
        text-transform: uppercase;
        letter-spacing: 0.06em;
        margin-bottom: 1rem;
        margin-top: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid var(--border-subtle);
    }

    .form-section-label:first-child { margin-top: 0; }

    .form-actions {
        display: flex;
        gap: 0.75rem;
        justify-content: flex-end;
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid var(--border-subtle);
    }

    #price-group {
        transition: opacity var(--transition-base);
    }

    #price-group.hidden-field {
        opacity: 0.3;
        pointer-events: none;
    }

    .event-id-badge {
        font-size: 0.75rem;
        color: var(--text-faint);
        font-family: monospace;
        background: rgba(255,255,255,0.03);
        padding: 0.15rem 0.5rem;
        border-radius: var(--radius-sm);
    }
</style>
@endsection

@section('content')
<div class="form-page">
    <div class="form-page-header animate-in">
        <a href="{{ route('admin.dashboard') }}" style="font-size:0.85rem; color:var(--text-muted); margin-bottom:0.75rem; display:inline-flex; align-items:center; gap:0.3rem;">← Back to Dashboard</a>
        <h1>Edit Event <span class="event-id-badge">ID #{{ $event->id }}</span></h1>
        <p>Update the details for "{{ $event->name }}"</p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger" style="font-size:0.85rem; flex-direction:column; align-items:flex-start;">
            @foreach($errors->all() as $error)
                <div>• {{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="card form-card animate-in animate-delay-1">
        <div class="card-body">
            <form action="{{ route('admin.events.update', $event->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-section-label">Event Information</div>

                <div class="form-group">
                    <label for="name" class="form-label">Event Name</label>
                    <input type="text" name="name" id="name" class="form-input" required value="{{ old('name', $event->name) }}">
                </div>

                <div class="form-group">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" id="description" class="form-input" required style="min-height:140px;">{{ old('description', $event->description) }}</textarea>
                </div>

                <div class="form-section-label">Event Details</div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="type" class="form-label">Event Type</label>
                        <select name="type" id="type" class="form-input" onchange="updateLocationHint()" required>
                            <option value="offline" {{ old('type', $event->type) === 'offline' ? 'selected' : '' }}>📍 Offline (Venue)</option>
                            <option value="online" {{ old('type', $event->type) === 'online' ? 'selected' : '' }}>🌐 Online (Webinar)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="location" class="form-label" id="location-label">Location</label>
                        <input type="text" name="location" id="location" class="form-input" required value="{{ old('location', $event->location) }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="date" class="form-label">Date & Time</label>
                    <input type="datetime-local" name="date" id="date" class="form-input" required value="{{ old('date', $event->date ? $event->date->format('Y-m-d\TH:i') : '') }}">
                </div>

                <div class="form-section-label">Ticket & Pricing</div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Ticket Type</label>
                        <select id="ticket_type_toggle" class="form-input" onchange="togglePrice()">
                            <option value="free" {{ old('price', $event->price) == 0 ? 'selected' : '' }}>🎫 Free</option>
                            <option value="paid" {{ old('price', $event->price) > 0 ? 'selected' : '' }}>💰 Paid</option>
                        </select>
                    </div>

                    <div class="form-group" id="price-group">
                        <label for="price" class="form-label">Price (IDR)</label>
                        <input type="number" name="price" id="price" class="form-input" min="0" required value="{{ old('price', $event->price) }}">
                    </div>

                    <div class="form-group">
                        <label for="quota" class="form-label">Quota</label>
                        <input type="number" name="quota" id="quota" class="form-input" min="1" required value="{{ old('quota', $event->quota) }}">
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary btn-lg">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function updateLocationHint() {
        const type = document.getElementById('type').value;
        const loc = document.getElementById('location');
        const label = document.getElementById('location-label');
        if (type === 'online') {
            loc.placeholder = 'e.g. https://zoom.us/j/999888777';
            label.textContent = 'Meeting Link';
        } else {
            loc.placeholder = 'e.g. Auditorium Gedung C Lt. 3';
            label.textContent = 'Location';
        }
    }

    function togglePrice() {
        const ticketType = document.getElementById('ticket_type_toggle').value;
        const priceGroup = document.getElementById('price-group');
        const priceInput = document.getElementById('price');
        if (ticketType === 'free') {
            priceGroup.classList.add('hidden-field');
            priceInput.value = 0;
        } else {
            priceGroup.classList.remove('hidden-field');
            if (priceInput.value === '0') priceInput.value = '';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        updateLocationHint();
        togglePrice();
    });
</script>
@endsection

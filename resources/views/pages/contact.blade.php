@extends('layouts.portal')

@section('title', 'Contact us — '.config('app.name'))
@section('meta_description', 'Contact Lanka Realtors for property enquiries, agent partnerships, or listing support. We respond within one business day.')
@section('canonical', route('contact'))

@push('head')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="">
@endpush

@section('content')
<section class="section section--tight site-page">
    <div class="container">
        <h1 class="section-title">Contact us</h1>
        <p class="section-lead">Send us a message or use the details below—we typically respond within one business day.</p>

        @if(session('status'))
            <div class="flash mt-2">{{ session('status') }}</div>
        @endif

        <div class="site-page__contact-grid">
            <div class="site-page__contact-sidebar">
                <div class="site-page__contact-cards">
                    @if(!empty($company['phone']))
                        <article class="site-page__card">
                            <h2>Phone</h2>
                            <p class="mb-0"><a href="tel:{{ preg_replace('/\s+/', '', $company['phone']) }}">{{ $company['phone'] }}</a></p>
                        </article>
                    @endif

                    @if(!empty($company['email']))
                        <article class="site-page__card">
                            <h2>Email</h2>
                            <p class="mb-0"><a href="mailto:{{ $company['email'] }}">{{ $company['email'] }}</a></p>
                        </article>
                    @endif

                    @if(!empty($company['address_line']) || !empty($company['city']))
                        <article class="site-page__card">
                            <h2>Office</h2>
                            <p class="muted mb-0">
                                @if(!empty($company['address_line'])){{ $company['address_line'] }}<br>@endif
                                {{ $company['city'] ?? '' }}
                            </p>
                        </article>
                    @endif

                    @if(!empty($company['hours']))
                        <article class="site-page__card">
                            <h2>Hours</h2>
                            <p class="muted mb-0">{{ $company['hours'] }}</p>
                        </article>
                    @endif
                </div>

                @if(!empty($company['latitude']) && !empty($company['longitude']))
                    @php
                        $mapLat = (float) $company['latitude'];
                        $mapLng = (float) $company['longitude'];
                        $mapZoom = (int) ($company['map_zoom'] ?? 15);
                        $mapLabel = trim(($company['address_line'] ?? '').', '.($company['city'] ?? ''));
                        $mapsQuery = urlencode($mapLabel !== ',' ? $mapLabel : 'Colombo 03, Sri Lanka');
                    @endphp
                    <div class="site-page__contact-map glass glass--pad">
                        <h2 class="site-page__subhead">Find us</h2>
                        <p class="muted site-page__contact-map-lead">Our office is in Colombo 03, on Galle Road.</p>
                        <div id="contact-office-map"
                             class="site-page__contact-map-canvas"
                             data-lat="{{ $mapLat }}"
                             data-lng="{{ $mapLng }}"
                             data-zoom="{{ $mapZoom }}"
                             data-label="{{ config('app.name') }}"
                             role="img"
                             aria-label="Map showing office location in Colombo 03"></div>
                        <p class="site-page__contact-map-foot muted mb-0">
                            <a href="https://www.google.com/maps/search/?api=1&query={{ $mapsQuery }}" target="_blank" rel="noopener noreferrer">Open in Google Maps</a>
                        </p>
                    </div>
                @endif
            </div>

            <div class="glass glass--pad site-page__form-panel">
                <h2 class="site-page__subhead">Send a message</h2>
                <p class="muted" style="margin:0 0 1.25rem;font-size:0.95rem">Fill in the form and our team will follow up by phone or email.</p>

                <form method="POST" action="{{ route('contact.submit') }}" class="contact-form">
                    @csrf

                    <div class="field">
                        <label for="contact-name">Full name</label>
                        <input id="contact-name" type="text" class="input @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name">
                        @error('name')<div class="error-text">{{ $message }}</div>@enderror
                    </div>

                    <div class="field mt-2">
                        <label for="contact-phone">Phone</label>
                        <input id="contact-phone" type="tel" class="input @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required autocomplete="tel">
                        @error('phone')<div class="error-text">{{ $message }}</div>@enderror
                    </div>

                    <div class="field mt-2">
                        <label for="contact-email">Email</label>
                        <input id="contact-email" type="email" class="input @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                        @error('email')<div class="error-text">{{ $message }}</div>@enderror
                    </div>

                    <div class="field mt-2">
                        <label for="contact-message">Message</label>
                        <textarea id="contact-message" class="input contact-form__textarea @error('message') is-invalid @enderror" name="message" rows="5" required>{{ old('message') }}</textarea>
                        @error('message')<div class="error-text">{{ $message }}</div>@enderror
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn-gold">Send message</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
@if(!empty($company['latitude']) && !empty($company['longitude']))
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
<script>
(function () {
    var mapEl = document.getElementById('contact-office-map');
    if (!mapEl || typeof L === 'undefined') return;

    var lat = parseFloat(mapEl.getAttribute('data-lat'));
    var lng = parseFloat(mapEl.getAttribute('data-lng'));
    var zoom = parseInt(mapEl.getAttribute('data-zoom'), 10) || 15;
    var label = mapEl.getAttribute('data-label') || 'Office';

    var map = L.map(mapEl, { scrollWheelZoom: false }).setView([lat, lng], zoom);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    var markerIcon = L.divIcon({
        className: 'contact-office-marker',
        html: '<span aria-hidden="true"></span>',
        iconSize: [32, 32],
        iconAnchor: [16, 32],
        popupAnchor: [0, -28]
    });

    L.marker([lat, lng], { icon: markerIcon })
        .addTo(map)
        .bindPopup('<strong>' + label + '</strong><br>Colombo 03')
        .openPopup();

    function refreshMapSize() {
        map.invalidateSize();
    }

    setTimeout(refreshMapSize, 100);
    window.addEventListener('load', refreshMapSize);

    mapEl.addEventListener('click', function () {
        map.scrollWheelZoom.enable();
    }, { once: true });
})();
</script>
@endif
@endpush

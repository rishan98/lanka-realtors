@extends('layouts.portal')

@section('title', 'Locate me — '.config('app.name'))
@section('meta_description', 'Search Sri Lankan property listings on an interactive map. Discover homes, land, and rentals by location.')
@section('canonical', route('locate'))

@push('head')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
@endpush

@section('content')
<section class="section section--tight locate-page">
    <div class="container">
        <h1 class="section-title">Locate me</h1>
        <p class="section-lead">Browse active property ads on the map. Use your location or open coordinates from a listing.</p>

        <div class="glass glass--pad">
            <div class="locate-page__toolbar">
                <button type="button" class="btn-gold" id="btn-locate">Use my location</button>
                <span class="muted locate-page__status" id="loc-status">
                    @if($mapListings->count())
                        Showing {{ $mapListings->count() }} active {{ Str::plural('listing', $mapListings->count()) }} on the map.
                    @else
                        No mappable listings yet.
                    @endif
                </span>
            </div>

            <div class="locate-page__legend" aria-label="Map legend">
                <span class="locate-page__legend-item">
                    <span class="locate-marker locate-marker--sale" aria-hidden="true"><span></span></span>
                    For sale
                </span>
                <span class="locate-page__legend-item">
                    <span class="locate-marker locate-marker--rental" aria-hidden="true"><span></span></span>
                    For rent
                </span>
                <span class="locate-page__legend-item">
                    <span class="locate-marker locate-marker--invest" aria-hidden="true"><span></span></span>
                    Invest
                </span>
            </div>

            <div id="map" class="locate-page__map"></div>

            <p class="locate-page__disclaimer">
                Please note that the location displayed on the map is pinned based on the address provided by the respective agent or property owner in the advertisement.
            </p>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script type="application/json" id="map-listings-data">{!! $mapListings->toJson() !!}</script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
(function () {
    var listings = JSON.parse(document.getElementById('map-listings-data').textContent || '[]');
    var params = new URLSearchParams(window.location.search);
    var focusLat = parseFloat(params.get('lat'));
    var focusLng = parseFloat(params.get('lng'));
    var hasFocus = !isNaN(focusLat) && !isNaN(focusLng);
    var startLat = hasFocus ? focusLat : 7.8731;
    var startLng = hasFocus ? focusLng : 80.7718;
    var startZoom = hasFocus ? 14 : 8;

    var map = L.map('map').setView([startLat, startLng], startZoom);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    var markerIcons = {
        sale: L.divIcon({
            className: 'locate-marker locate-marker--sale',
            html: '<span></span>',
            iconSize: [28, 28],
            iconAnchor: [14, 28],
            popupAnchor: [0, -24]
        }),
        rental: L.divIcon({
            className: 'locate-marker locate-marker--rental',
            html: '<span></span>',
            iconSize: [28, 28],
            iconAnchor: [14, 28],
            popupAnchor: [0, -24]
        }),
        invest: L.divIcon({
            className: 'locate-marker locate-marker--invest',
            html: '<span></span>',
            iconSize: [28, 28],
            iconAnchor: [14, 28],
            popupAnchor: [0, -24]
        })
    };

    var listingLayer = L.layerGroup().addTo(map);
    var bounds = [];

    listings.forEach(function (item) {
        var icon = markerIcons[item.kind] || markerIcons.sale;
        var marker = L.marker([item.lat, item.lng], { icon: icon });

        var popupHtml = '<div class="locate-popup">'
            + '<strong>' + escapeHtml(item.title) + '</strong>'
            + '<div class="locate-popup__meta">' + escapeHtml(item.kind_label) + ' · ' + escapeHtml(item.price) + '</div>';

        if (item.city) {
            popupHtml += '<div class="locate-popup__meta">' + escapeHtml(item.city) + '</div>';
        }

        popupHtml += '<a class="locate-popup__link" href="' + escapeHtml(item.url) + '">View listing</a></div>';

        marker.bindPopup(popupHtml);
        marker.addTo(listingLayer);
        bounds.push([item.lat, item.lng]);
    });

    var userMarker = null;

    if (hasFocus) {
        userMarker = L.marker([focusLat, focusLng]).addTo(map);
        bounds.push([focusLat, focusLng]);
    }

    if (bounds.length && !hasFocus) {
        map.fitBounds(bounds, { padding: [36, 36], maxZoom: 12 });
    } else if (bounds.length > 1 && hasFocus) {
        map.fitBounds(bounds, { padding: [48, 48], maxZoom: 14 });
    }

    function setStatus(text) {
        var el = document.getElementById('loc-status');
        if (el) {
            el.textContent = text || '';
        }
    }

    function escapeHtml(value) {
        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    document.getElementById('btn-locate').addEventListener('click', function () {
        if (!navigator.geolocation) {
            setStatus('Geolocation is not supported by this browser.');
            return;
        }

        setStatus('Locating…');

        navigator.geolocation.getCurrentPosition(function (pos) {
            var lat = pos.coords.latitude;
            var lng = pos.coords.longitude;

            map.setView([lat, lng], 14);

            if (!userMarker) {
                userMarker = L.marker([lat, lng]).addTo(map);
            } else {
                userMarker.setLatLng([lat, lng]);
            }

            setStatus('Showing your approximate location and nearby listings.');
        }, function () {
            setStatus('Unable to read your location. Check browser permissions.');
        });
    });
})();
</script>
@endpush

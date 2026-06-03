@extends('layouts.portal')

@section('title', 'Locate me — '.config('app.name'))

@push('head')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
<style>#map { height: 480px; border-radius: 16px; overflow: hidden; border: 1px solid rgba(11,27,51,0.08); }</style>
@endpush

@section('content')
<section class="section section--tight">
    <div class="container">
        <h1 class="section-title">Locate me</h1>
        <p class="section-lead">Drop a pin from your device or open coordinates shared with a listing.</p>

        <div class="glass glass--pad">
            <div class="row-flex mt-2" style="margin-bottom:14px">
                <button type="button" class="btn-gold" id="btn-locate">Use my location</button>
                <span class="muted" id="loc-status" style="font-size:0.92rem"></span>
            </div>
            <div id="map"></div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
(function () {
    var params = new URLSearchParams(window.location.search);
    var startLat = parseFloat(params.get('lat')) || 7.8731;
    var startLng = parseFloat(params.get('lng')) || 80.7718;
    var map = L.map('map').setView([startLat, startLng], 8);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);
    var marker = L.marker([startLat, startLng]).addTo(map);

    function setStatus(text) {
        var el = document.getElementById('loc-status');
        if (el) el.textContent = text || '';
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
            marker.setLatLng([lat, lng]);
            setStatus('Showing your approximate location.');
        }, function () {
            setStatus('Unable to read your location. Check browser permissions.');
        });
    });
})();
</script>
@endpush

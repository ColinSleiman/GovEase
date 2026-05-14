<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Citizen Dashboard | GovEase</title>
    <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <style>
        :root {
            --ink: #172033;
            --muted: #637083;
            --line: #d9e1ec;
            --soft: #f5f8fb;
            --brand: #1967d2;
            --accent: #0f9d58;
        }

        body {
            margin: 0;
            background: var(--soft);
            color: var(--ink);
            font-family: Arial, sans-serif;
        }

        .dashboard-shell {
            min-height: 100vh;
        }

        .topbar {
            background: #fff;
            border-bottom: 1px solid var(--line);
            padding: 16px 0;
        }

        .brand-mark {
            color: var(--brand);
            font-weight: 800;
            text-decoration: none;
        }

        .dashboard-main {
            padding: 28px 0;
        }

        .page-title {
            margin: 0;
            font-size: 30px;
            font-weight: 800;
        }

        .page-subtitle {
            margin: 8px 0 0;
            color: var(--muted);
            max-width: 760px;
        }

        .map-layout {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 380px;
            gap: 20px;
            margin-top: 24px;
        }

        .panel {
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 10px 28px rgba(23, 32, 51, 0.06);
        }

        #citizen-map {
            height: 640px;
            width: 100%;
        }

        .sidebar {
            display: flex;
            min-height: 640px;
            flex-direction: column;
        }

        .sidebar-header {
            border-bottom: 1px solid var(--line);
            padding: 18px;
        }

        .btn-location {
            border: 0;
            border-radius: 6px;
            background: var(--brand);
            color: #fff;
            font-weight: 700;
            padding: 11px 14px;
            width: 100%;
        }

        .status-text {
            color: var(--muted);
            font-size: 14px;
            margin: 12px 0 0;
        }

        .municipality-list {
            overflow: auto;
            padding: 0;
        }

        .municipality-card {
            border-bottom: 1px solid var(--line);
            cursor: pointer;
            padding: 16px 18px;
            transition: background 0.15s ease;
        }

        .municipality-card:hover,
        .municipality-card.is-active {
            background: #eef5ff;
        }

        .municipality-name {
            font-size: 16px;
            font-weight: 800;
            margin: 0;
        }

        .municipality-meta {
            color: var(--muted);
            font-size: 13px;
            margin: 6px 0 0;
        }

        .distance-pill {
            background: #e8f5ee;
            border-radius: 999px;
            color: #137333;
            display: inline-block;
            font-size: 12px;
            font-weight: 800;
            margin-top: 10px;
            padding: 4px 9px;
        }

        .empty-state {
            color: var(--muted);
            padding: 20px 18px;
        }

        .api-warning {
            border: 1px solid #f2d086;
            background: #fff8e5;
            border-radius: 8px;
            color: #815800;
            margin-top: 24px;
            padding: 16px;
        }

        @media (max-width: 991px) {
            .map-layout {
                grid-template-columns: 1fr;
            }

            #citizen-map,
            .sidebar {
                min-height: auto;
                height: 480px;
            }

            .sidebar {
                height: auto;
            }
        }
    </style>
</head>
<body>
<div class="dashboard-shell">
    <header class="topbar">
        <div class="container d-flex align-items-center justify-content-between gap-3">
            <a class="brand-mark" href="{{ route('home') }}">GovEase</a>
            <div class="d-flex gap-2">
                <a class="btn btn-sm btn-primary" href="{{ route('citizen.documents.index') }}">Request Documents</a>
                <a class="btn btn-sm btn-outline-primary" href="{{ route('home') }}">Home</a>
            </div>
        </div>
    </header>

    <main class="dashboard-main">
        <div class="container">
            <h1 class="page-title">Citizen Dashboard</h1>
            <p class="page-subtitle">Find municipalities near you and check their contact details, working hours, and location before starting a service request.</p>

            @if (! $apiKey)
                <div class="api-warning">
                    Google Maps is unavailable because <strong>GOOGLE_MAPS_API_KEY</strong> is not configured in the environment file.
                </div>
            @else
                <div class="map-layout">
                    <section class="panel">
                        <div id="citizen-map"></div>
                    </section>

                    <aside class="panel sidebar">
                        <div class="sidebar-header">
                            <button type="button" id="use-location" class="btn-location">Use my location</button>
                            <p id="location-status" class="status-text">Showing saved municipalities. Use your location to sort by nearest.</p>
                        </div>

                        <div id="municipality-list" class="municipality-list"></div>
                    </aside>
                </div>
            @endif
        </div>
    </main>
</div>

@if ($apiKey)
    <script>
        const municipalities = @json($municipalities);
        let map;
        let infoWindow;
        let userMarker = null;
        let activeMarker = null;
        const markers = new Map();

        function escapeHtml(value) {
            return String(value ?? '')
                .replaceAll('&', '&amp;')
                .replaceAll('<', '&lt;')
                .replaceAll('>', '&gt;')
                .replaceAll('"', '&quot;')
                .replaceAll("'", '&#039;');
        }

        function distanceKm(from, to) {
            const earthRadiusKm = 6371;
            const latDistance = (to.lat - from.lat) * Math.PI / 180;
            const lngDistance = (to.lng - from.lng) * Math.PI / 180;
            const fromLat = from.lat * Math.PI / 180;
            const toLat = to.lat * Math.PI / 180;

            const a = Math.sin(latDistance / 2) ** 2
                + Math.cos(fromLat) * Math.cos(toLat) * Math.sin(lngDistance / 2) ** 2;

            return earthRadiusKm * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        }

        function markerContent(municipality) {
            return `
                <div style="max-width:240px">
                    <strong>${escapeHtml(municipality.name)}</strong><br>
                    <span>${escapeHtml(municipality.region || 'Region unavailable')}</span><br>
                    <span>${escapeHtml(municipality.address || 'Address unavailable')}</span><br>
                    <span>${escapeHtml(municipality.working_hours || 'Working hours unavailable')}</span>
                </div>
            `;
        }

        function renderMunicipalityList(items) {
            const list = document.getElementById('municipality-list');

            if (!items.length) {
                list.innerHTML = '<div class="empty-state">No municipalities with map coordinates were found.</div>';
                return;
            }

            list.innerHTML = items.map((municipality) => `
                <article class="municipality-card" data-id="${municipality.id}">
                    <h2 class="municipality-name">${escapeHtml(municipality.name)}</h2>
                    <p class="municipality-meta">${escapeHtml(municipality.region || 'Region unavailable')}</p>
                    <p class="municipality-meta">${escapeHtml(municipality.address || 'Address unavailable')}</p>
                    <p class="municipality-meta">${escapeHtml(municipality.contact_info || 'Contact unavailable')}</p>
                    ${municipality.distance !== undefined ? `<span class="distance-pill">${municipality.distance.toFixed(1)} km away</span>` : ''}
                </article>
            `).join('');

            list.querySelectorAll('.municipality-card').forEach((card) => {
                card.addEventListener('click', () => {
                    focusMunicipality(Number(card.dataset.id));
                });
            });
        }

        function focusMunicipality(id) {
            const municipality = municipalities.find((item) => item.id === id);
            const marker = markers.get(id);

            if (!municipality || !marker) {
                return;
            }

            if (activeMarker) {
                activeMarker.setIcon('https://maps.google.com/mapfiles/ms/icons/red-dot.png');
            }

            activeMarker = marker;
            marker.setIcon('https://maps.google.com/mapfiles/ms/icons/green-dot.png');
            map.panTo(marker.getPosition());
            map.setZoom(Math.max(map.getZoom(), 13));
            infoWindow.setContent(markerContent(municipality));
            infoWindow.open({ anchor: marker, map });

            document.querySelectorAll('.municipality-card').forEach((card) => {
                card.classList.toggle('is-active', Number(card.dataset.id) === id);
            });
        }

        function sortByUserLocation(position) {
            const userPosition = {
                lat: position.coords.latitude,
                lng: position.coords.longitude,
            };

            if (userMarker) {
                userMarker.setPosition(userPosition);
            } else {
                userMarker = new google.maps.Marker({
                    position: userPosition,
                    map,
                    title: 'Your location',
                    icon: 'https://maps.google.com/mapfiles/ms/icons/blue-dot.png',
                });
            }

            const sorted = municipalities
                .map((municipality) => ({
                    ...municipality,
                    distance: distanceKm(userPosition, {
                        lat: Number(municipality.latitude),
                        lng: Number(municipality.longitude),
                    }),
                }))
                .sort((a, b) => a.distance - b.distance);

            renderMunicipalityList(sorted);

            const bounds = new google.maps.LatLngBounds();
            bounds.extend(userPosition);
            sorted.slice(0, 6).forEach((municipality) => {
                bounds.extend({
                    lat: Number(municipality.latitude),
                    lng: Number(municipality.longitude),
                });
            });
            map.fitBounds(bounds);

            document.getElementById('location-status').textContent = 'Municipalities are sorted from nearest to farthest.';
        }

        function initCitizenMap() {
            const fallbackLocation = { lat: 33.8938, lng: 35.5018 };
            map = new google.maps.Map(document.getElementById('citizen-map'), {
                center: fallbackLocation,
                zoom: 11,
                mapTypeControl: false,
                streetViewControl: false,
            });
            infoWindow = new google.maps.InfoWindow();

            const bounds = new google.maps.LatLngBounds();

            municipalities.forEach((municipality) => {
                const position = {
                    lat: Number(municipality.latitude),
                    lng: Number(municipality.longitude),
                };

                if (Number.isNaN(position.lat) || Number.isNaN(position.lng)) {
                    return;
                }

                const marker = new google.maps.Marker({
                    position,
                    map,
                    title: municipality.name,
                    icon: 'https://maps.google.com/mapfiles/ms/icons/red-dot.png',
                });

                marker.addListener('click', () => focusMunicipality(municipality.id));
                markers.set(municipality.id, marker);
                bounds.extend(position);
            });

            if (!bounds.isEmpty()) {
                map.fitBounds(bounds);
            }

            renderMunicipalityList(municipalities);

            document.getElementById('use-location').addEventListener('click', () => {
                const status = document.getElementById('location-status');

                if (!navigator.geolocation) {
                    status.textContent = 'Your browser does not support location access.';
                    return;
                }

                status.textContent = 'Waiting for location permission...';
                navigator.geolocation.getCurrentPosition(
                    sortByUserLocation,
                    () => {
                        status.textContent = 'Location permission was denied or unavailable.';
                    },
                    { enableHighAccuracy: true, timeout: 10000, maximumAge: 60000 }
                );
            });
        }

        window.initCitizenMap = initCitizenMap;
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ $apiKey }}&callback=initCitizenMap" async defer></script>
@endif
</body>
</html>

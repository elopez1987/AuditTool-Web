@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Mapa del sitio
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-lg-12">
                                @can('maps-geofence.create')
                                    <a href="{{route('maps-geofence.create')}}">
                                        @include('buttons.add')
                                    </a>
                                @endcan
                                <a href="{{route('home')}}">
                                    @include('buttons.cancel')
                                </a>
                            </div>
                        </div>
                        <hr class="m-t-1">
                        <div class="row">
                            <x-select2
                                    name="centerid"
                                    label="Centro de distribución"
                                    :options="$centers"
                                    valueField="centerid"
                                    textField="name"
                                    :selected="$selectedCenterId"
                                    colClass="col-lg-2 col-md-12"
                            />
                            <x-text-input
                                    name="folionumber"
                                    label="Folio de unidad"
                                    uppercase
                                    colClass="col-lg-2 col-md-12"
                            />
                            <x-text-input
                                    name="boxnumber"
                                    label="Placa de unidad"
                                    uppercase
                                    colClass="col-lg-2 col-md-12"
                            />
                            <div class="col-lg-2 col-md-12">
                                <div class="mb-3">
                                    <label class="d-block">&nbsp;</label>
                                    @include('buttons.search')
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div id="unit-search-feedback" class="alert" role="alert" style="display:none;"></div>
                            </div>
                        </div>

                    </form>
                    <div id="geofence-warning" class="alert alert-warning" style="@if(! $selectedCenterId || $geofence) display:none; @endif">
                        El centro seleccionado no cuenta con una geocerca registrada.
                    </div>
                    <p id="geofence-name" class="text-muted" style="@unless($geofence) display:none; @endunless">
                        Geocerca: <strong>{{ $geofence->name ?? '' }}</strong>
                    </p>
                    <div id="map" style="height: 1200px;"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <style>
        .unit-marker-highlighted {
            background: transparent;
            border: none;
        }
        .unit-marker-highlighted-pin {
            display: block;
            width: 24px;
            height: 24px;
            background-color: #e53935;
            border: 3px solid #b71c1c;
            border-radius: 50% 50% 50% 0;
            transform: rotate(-45deg);
            box-shadow: 0 0 8px rgba(0, 0, 0, .6);
            animation: unit-marker-pulse 1s ease-in-out infinite alternate;
        }
        @keyframes unit-marker-pulse {
            from { box-shadow: 0 0 4px rgba(229, 57, 53, .7); }
            to { box-shadow: 0 0 16px rgba(229, 57, 53, 1); }
        }
    </style>
@endsection

@section('script')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        const DEFAULT_CENTER = [19.4326, -99.1332];
        const DEFAULT_ZOOM = 11;

        const map = L.map('map').setView(DEFAULT_CENTER, DEFAULT_ZOOM);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors',
            maxZoom: 19,
        }).addTo(map);

        let currentPolygon = null;
        const unitMarkers = L.layerGroup().addTo(map);

        let currentUnits = [];
        let unitMarkersById = {};
        let highlightedMarkers = [];
        const defaultMarkerIcon = new L.Icon.Default();
        const highlightedMarkerIcon = L.divIcon({
            className: 'unit-marker-highlighted',
            html: '<span class="unit-marker-highlighted-pin"></span>',
            iconSize: [24, 24],
            iconAnchor: [12, 24],
            popupAnchor: [0, -22],
        });

        function updateMapView() {
            map.invalidateSize();

            const boundsPoints = [];

            if (currentPolygon) {
                boundsPoints.push(...currentPolygon.getLatLngs()[0]);
            }

            unitMarkers.eachLayer(function (marker) {
                boundsPoints.push(marker.getLatLng());
            });

            if (boundsPoints.length) {
                map.fitBounds(L.latLngBounds(boundsPoints), {maxZoom: 17, padding: [30, 30]});
            } else {
                map.setView(DEFAULT_CENTER, DEFAULT_ZOOM);
            }
        }

        function renderGeofence(centerSelected, name, points) {
            if (currentPolygon) {
                map.removeLayer(currentPolygon);
                currentPolygon = null;
            }

            if (points && points.length) {
                const latlngs = points.map(p => [p.lat, p.lng]);
                currentPolygon = L.polygon(latlngs, {color: '#3388ff'}).addTo(map);
            }

            $('#geofence-name strong').text(name || '');
            $('#geofence-name').toggle(Boolean(name));
            $('#geofence-warning').toggle(Boolean(centerSelected) && ! name);

            updateMapView();
        }

        function buildUnitPopup(unit) {
            const rows = [
                ['Unidad', unit.id],
                ['C.Barra', unit.label],
                ['Embarque', unit.shipment],
                ['Caja', unit.boxnumber],
                ['Transportista', unit.carrier],
                ['Posición', unit.recorded_at || 'Sin fecha registrada'],
            ];

            return rows
                .filter(([, value]) => value !== null && value !== undefined && value !== '')
                .map(([field, value]) => '<strong>' + field + ':</strong> ' + value)
                .join('<br>');
        }

        function renderUnitMarkers(units) {
            unitMarkers.clearLayers();

            currentUnits = units || [];
            unitMarkersById = {};
            highlightedMarkers = [];

            currentUnits.forEach(function (unit) {
                const marker = L.marker([unit.lat, unit.lng], {icon: defaultMarkerIcon})
                    .addTo(unitMarkers)
                    .bindPopup(buildUnitPopup(unit));

                unitMarkersById[unit.id] = marker;
            });
        }

        function clearUnitHighlights() {
            highlightedMarkers.forEach(marker => marker.setIcon(defaultMarkerIcon));
            highlightedMarkers = [];
        }

        function normalizeSearchValue(value) {
            return (value || '').toString().trim().toUpperCase();
        }

        function findMatchingUnits(folionumber, boxnumber) {
            const folioAsNumber = folionumber !== '' && ! isNaN(Number(folionumber)) ? Number(folionumber) : null;

            return currentUnits.filter(function (unit) {
                const matchesFolio = folioAsNumber !== null && Number(unit.id) === folioAsNumber;
                const matchesBox = boxnumber !== '' && (unit.boxnumber || '').toString().toUpperCase().includes(boxnumber);

                return matchesFolio || matchesBox;
            });
        }

        function showUnitSearchFeedback(message, type) {
            $('#unit-search-feedback')
                .removeClass('alert-warning alert-success')
                .addClass('alert-' + type)
                .text(message)
                .show();
        }

        function searchUnitOnMap() {
            const folionumber = normalizeSearchValue($('#folionumber').val());
            const boxnumber = normalizeSearchValue($('#boxnumber').val());

            clearUnitHighlights();

            if (! folionumber && ! boxnumber) {
                showUnitSearchFeedback('Captura el folio o la placa de la unidad a buscar.', 'warning');
                return;
            }

            const matches = findMatchingUnits(folionumber, boxnumber);
            const matchedMarkers = matches.map(unit => unitMarkersById[unit.id]).filter(Boolean);

            if (! matchedMarkers.length) {
                showUnitSearchFeedback('No se encontró ninguna unidad en el mapa con esos datos.', 'warning');
                return;
            }

            highlightedMarkers = matchedMarkers;
            matchedMarkers.forEach(marker => marker.setIcon(highlightedMarkerIcon));

            if (matchedMarkers.length === 1) {
                map.setView(matchedMarkers[0].getLatLng(), 18);
                matchedMarkers[0].openPopup();
            } else {
                map.fitBounds(L.latLngBounds(matchedMarkers.map(marker => marker.getLatLng())), {
                    maxZoom: 18,
                    padding: [30, 30],
                });
            }

            showUnitSearchFeedback(
                matchedMarkers.length === 1
                    ? 'Unidad localizada en el mapa.'
                    : matchedMarkers.length + ' unidades localizadas en el mapa.',
                'success'
            );
        }

        $(window).on('load', function () {
            renderUnitMarkers(@json($unitsInYard));
            renderGeofence(@json((bool) $selectedCenterId), @json($geofence->name ?? null), @json($points));
        });

        $('#centerid').on('change', function () {
            const centerid = $(this).val();

            if (! centerid) {
                renderGeofence(false, null, []);
                return;
            }

            $.get('{{ route('getDataGeofence') }}', {centerid: centerid})
                .done(function (response) {
                    renderGeofence(true, response.name, response.points);
                })
                .fail(function () {
                    renderGeofence(true, null, []);
                });
        });

        $('#search').on('submit', function (event) {
            event.preventDefault();
            searchUnitOnMap();
        });
    </script>
@endsection

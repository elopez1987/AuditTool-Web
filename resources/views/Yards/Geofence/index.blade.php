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
                                    colClass="col-lg-3 col-md-12"
                            />
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

        function renderGeofence(centerSelected, name, points) {
            // Si el layout (sidebar, cards) todavia no termina de acomodarse cuando
            // Leaflet mide el contenedor, calcula mal el tamaño y el mapa se ve
            // "abierto" a nivel mundial con el mundo repetido. Forzamos el recalculo
            // antes de hacer zoom para evitarlo.
            map.invalidateSize();

            if (currentPolygon) {
                map.removeLayer(currentPolygon);
                currentPolygon = null;
            }

            if (points && points.length) {
                const latlngs = points.map(p => [p.lat, p.lng]);
                currentPolygon = L.polygon(latlngs, {color: '#3388ff'}).addTo(map);
                map.fitBounds(currentPolygon.getBounds(), {maxZoom: 17, padding: [30, 30]});
            } else {
                map.setView(DEFAULT_CENTER, DEFAULT_ZOOM);
            }

            $('#geofence-name strong').text(name || '');
            $('#geofence-name').toggle(Boolean(name));
            $('#geofence-warning').toggle(Boolean(centerSelected) && ! name);
        }

        // Se espera a que la página termine de cargar por completo (incluyendo el
        // acomodo del layout) antes del primer render, para que Leaflet mida bien
        // el contenedor desde el inicio.
        $(window).on('load', function () {
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
    </script>
@endsection

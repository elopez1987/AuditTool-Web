@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Crear mapa
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('maps-geofence')}}" id="geofence-form" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                @include('buttons.save')
                                <a href="{{route('maps-geofence.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="mb-3">
                        <div class="row">
                            <x-select2
                                    name="centerid"
                                    label="Centros de distribución"
                                    :options="$centers"
                                    valueField="centerid"
                                    textField="name"
                                    :required="true"
                            />
                            <x-text-input
                                    name="name"
                                    label="Nombre completo"
                                    required
                                    uppercase
                                    colClass="col-lg-3 col-md-12"
                            />
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <p class="text-muted mb-2">
                                    Dibuja el polígono de la geocerca en el mapa con la herramienta de dibujo
                                    (mínimo 3 puntos). Si el centro seleccionado ya cuenta con una geocerca,
                                    esta será reemplazada por la que dibujes aquí.
                                </p>
                                <div id="map" style="height: 1200px;"></div>
                            </div>
                        </div>
                        <input type="hidden" name="points" id="points-input">
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.Partials.Errors')
@endsection

@section('css')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.css">
@endsection

@section('script')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.js"></script>
    <script>
        const map = L.map('map').setView([19.4326, -99.1332], 11);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors',
            maxZoom: 19,
        }).addTo(map);

        // Si el layout todavia no termina de acomodarse cuando Leaflet mide el
        // contenedor, calcula mal el tamaño y el mapa se ve incorrecto (mundo
        // repetido a bajo zoom). Se recalcula una vez que la pagina carga por completo.
        $(window).on('load', function () {
            map.invalidateSize();
        });

        const drawnItems = new L.FeatureGroup();
        map.addLayer(drawnItems);

        const drawControl = new L.Control.Draw({
            draw: {
                polygon: true,
                marker: false,
                circle: false,
                circlemarker: false,
                polyline: false,
                rectangle: false,
            },
            edit: {
                featureGroup: drawnItems,
            },
        });
        map.addControl(drawControl);

        function syncPointsInput() {
            const layers = drawnItems.getLayers();

            if (! layers.length) {
                document.getElementById('points-input').value = '';
                return;
            }

            const latlngs = layers[0].getLatLngs()[0];
            const points = latlngs.map(ll => ({lat: ll.lat, lng: ll.lng}));
            document.getElementById('points-input').value = JSON.stringify(points);
        }

        map.on(L.Draw.Event.CREATED, function (e) {
            drawnItems.clearLayers(); // solo un polígono por centro de distribución
            drawnItems.addLayer(e.layer);
            syncPointsInput();
        });

        map.on(L.Draw.Event.EDITED, syncPointsInput);
        map.on(L.Draw.Event.DELETED, syncPointsInput);

        document.getElementById('geofence-form').addEventListener('submit', function (event) {
            if (! document.getElementById('points-input').value) {
                event.preventDefault();
                alert('Dibuja el polígono de la geocerca en el mapa antes de guardar (mínimo 3 puntos).');
            }
        });
    </script>
@endsection

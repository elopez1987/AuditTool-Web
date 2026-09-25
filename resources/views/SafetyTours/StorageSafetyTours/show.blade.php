@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Detalle del recorrido.
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-lg-12">
                                <a href="">
                                    @include('buttons.search')
                                </a>
                                <a href="{{route('storagesafetytours.index')}}">
                                    @include('buttons.cancel')
                                </a>
                            </div>
                        </div>
                        <hr class="m-t-1">
                        <div class="row">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="name">Número de recorrido.</label>
                                    <input type="text" class="form-control"
                                           value="{{$tour->id}}" readonly
                                    >
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="name">Nombre de recorrido.</label>
                                    <input type="text" class="form-control"
                                           value="{{$tour->name}}" readonly
                                    >
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="name">Fecha de recorrido.</label>
                                    <input type="text" class="form-control"
                                           value="{{$tour->datetours}}" readonly
                                    >
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="name">Fecha de inicio.</label>
                                    <input type="text" class="form-control"
                                           value="{{$tour->startdate}}" readonly
                                    >
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="name">Fecha de cierre.</label>
                                    <input type="text" class="form-control"
                                           value="{{$tour->finishdate}}" readonly
                                    >
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-12">
                            <table id="table-tags" class="table table-bordered table-hover table-condensed tablegeneral">
                                <thead>
                                <tr>
                                    <th class="bg-inverse text-white">Tag</th>
                                    <th class="bg-inverse text-white">Secuencia</th>
                                    <th class="bg-inverse text-white">Latitud</th>
                                    <th class="bg-inverse text-white">Longitud</th>
                                    <th class="bg-inverse text-white">¿Con incidencia?</th>
                                    <th class="bg-inverse text-white">Comentario</th>
                                    <th class="bg-inverse text-white">Evidencia</th>
                                    <th class="bg-inverse text-white">Creador</th>
                                    <th class="bg-inverse text-white">Fecha de registro</th>
                                    <th class="bg-inverse text-white">Estatus</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($details as $detail)
                                    <tr>
                                        <td>{{$detail->tag->tagid}}</td>
                                        <td>{{$detail->sequence}}</td>
                                        <td>{{$detail->latitude}}</td>
                                        <td>{{$detail->longitude}}</td>
                                        <td>{{$detail->categorytype ? $detail->categorytype->name : ''}}</td>
                                        <td>{{$detail->comment}}</td>
                                        <td>
                                            @if(!empty($detail->evidencefile))
                                                <div class="el-card-item">
                                                    <div class="el-card-avatar el-overlay-1">
                                                        <div class="el-overlay">
                                                            <ul class="list-style-none el-info">
                                                                <li class="el-item bg-grey">
                                                                    <a class="btn default btn-outline image-popup-vertical-fit el-link"
                                                                       href="{{asset('storage/' . $detail->evidencefile)}}">
                                                                        Ver
                                                                        <i class="icon-magnifier"></i>
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                Sin Evidencia
                                            @endif
                                        </td>
                                        <td>{{$detail->user ? $detail->user->name : ''}}</td>
                                        <td>{{$detail->created_at}}</td>
                                        <td>
                                            @if($detail->statusid == 'RSE-02')
                                                <span class="badge badge-success">{{$detail->status ? $detail->status->name : ''}}</span>
                                            @else
                                                <span class="badge badge-danger">{{$detail->status ? $detail->status->name : ''}}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div id="map" ></div>
        </div>
    </div>
@endsection

@section('css')
    <style>
        #map {
            height: 900px;
            width: 100%;
        }
        .gps-marker {
            background: transparent;
            border: none;
        }

        .gps-icon {
            font-size: 32px;
            filter: drop-shadow(0 2px 3px rgba(0, 0, 0, 0.4));
        }
    </style>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <link rel="stylesheet" href="{{asset('/theme/assets/extra-libs/prism/prism.css')}}">
    <link rel="stylesheet" href="{{asset('/theme/assets/libs/magnific-popup/dist/magnific-popup.css')}}">
@endsection

@section('script')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="{{asset('theme/assets/extra-libs/prism/prism.js')}}"></script>
    <script src="{{asset('theme/assets/libs/magnific-popup/dist/jquery.magnific-popup.min.js')}}"></script>
    <script src="{{asset('theme/assets/libs/magnific-popup/meg.init.js')}}"></script>
    @include('layouts.Partials.DataTable')
    
    <script>
        const locations = @json($locations);

        function initMap() {
            console.log('Iniciando mapa con', locations.length, 'ubicaciones');

            // Validar que existan ubicaciones
            if (!locations || locations.length === 0) {
                console.warn('No hay ubicaciones disponibles para mostrar en el mapa');
                document.getElementById('map').innerHTML = '<div style="padding: 20px; ' +
                    'text-align: center; background: #f8f9fa; ' +
                    'border: 1px solid #dee2e6; border-radius: 4px;">No hay ubicaciones disponibles para mostrar en el mapa</div>';
                return;
            }

            const center = [19.3540031, -99.587697];
            const map = L.map('map').setView(center, 8);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors',
                maxZoom: 19,
            }).addTo(map);

            map.invalidateSize();

            locations.forEach(location => {
                console.log('Agregando marcador:', location);

                const markerColor = getMarkerColor(location.status);

                const gpsIcon = L.divIcon({
                    className: 'gps-marker',
                    html: `
                        <div class="gps-icon" style="color: ${markerColor};">
                            <i class="fas fa-map-marker"></i>
                        </div>
                         `,
                    iconSize: [30, 30],
                    iconAnchor: [20, 20],
                    popupAnchor: [0, -20]
                });

                L.marker([location.lat, location.lng], {
                    icon: gpsIcon
                })
                    .addTo(map)
                    .bindPopup(`
            <strong>${location.name}</strong><br>
            Estado: ${location.status}
        `);
            });

            map.fitBounds(getBoundsForLocations(locations));

            console.log('Mapa inicializado correctamente');
        }

        function getMarkerColor(status) {
            switch (status) {
                case 'TERMINADO':
                    return 'green';
                case 'PENDIENTE':
                    return 'red';
                default:
                    return 'blue';
            }
        }

        function getBoundsForLocations(locations) {
            return L.latLngBounds(locations.map(location => [location.lat, location.lng]));
        }

        $(window).on('load', initMap);
    </script>
@endsection

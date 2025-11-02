@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    REPORTAR INCIDENCIA EN ENTREGA
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-md-12">
                                <a href="{{route('storageincidencesjourneys.create')}}">
                                    @include('buttons.add')
                                </a>
                                <a>
                                    @include('buttons.reloadajax')
                                </a>
                                <a href="{{route('menushipment')}}">
                                    @include('buttons.cancel')
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header"></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-10 col-xs-12">
                            <table id="table-monitorincidences"
                                   class="table table-bordered table-hover table-condensed">
                                <thead>
                                <tr>
                                    <th>Embarque</th>
                                    <th>Transporte</th>
                                    <th>Cliente</th>
                                    <th>Ciudad</th>
                                    <th>Cita de Carga</th>
                                    <th>Cita de descarga</th>
                                    <th>Fecha de alta</th>
                                    <th>Tiempo sin respuesta</th>
                                    <th>Opciones</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('css')
    <link rel="stylesheet"
@endsection

@section('script')
    @include('Shipments.StorageIncidenceJourneys.partials.table')
    <script>
        function reloadtable() {
            $('#table-monitorincidences').DataTable().ajax.reload();
        }
    </script>
@endsection

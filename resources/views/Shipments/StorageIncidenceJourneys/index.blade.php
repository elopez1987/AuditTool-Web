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
                                   class="table table-bordered table-striped table-condensed">
                                <thead>
                                <tr>
                                    <th class="bg-inverse text-white">Embarque</th>
                                    <th class="bg-inverse text-white">Transporte</th>
                                    <th class="bg-inverse text-white">Cliente</th>
                                    <th class="bg-inverse text-white">Ciudad</th>
                                    <th class="bg-inverse text-white">Cita de Carga</th>
                                    <th class="bg-inverse text-white">Cita de descarga</th>
                                    <th class="bg-inverse text-white">Fecha de alta</th>
                                    <th class="bg-inverse text-white">Tiempo sin respuesta</th>
                                    <th class="bg-inverse text-white">Opciones</th>
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

@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                   MONITOR DE OPERACIONES.
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-md-12">
                                <a href="">
                                    @include('buttons.search')
                                </a>
                                <a>
                                    @include('buttons.reloadajax')
                                </a>
                                <a href="{{route('menushipment')}}">
                                    @include('buttons.cancel')
                                </a>
                            </div>
                        </div>
                        <hr class="m-t-1">
                    </form>

                    <div class="row">
                        <div class="col-lg-12">
                            <table id="table-monitortrafico"
                                   class="table table-bordered  table-condensed table-hover">
                                <thead>
                                <tr>
                                    <th class="bg-inverse text-white">Embarque</th>
                                    <th class="bg-inverse text-white">Cliente</th>
                                    <th class="bg-inverse text-white">Ciudad</th>
                                    <th class="bg-inverse text-white">Linea de transporte</th>
                                    <th class="bg-inverse text-white">Estatus Almacén</th>
                                    <th class="bg-inverse text-white">Estatus Trafico</th>
                                    <th class="bg-inverse text-white">Comentarios</th>
                                    <th class="bg-inverse text-white">Ingreso de Unidad</th>
                                    <th class="bg-inverse text-white">Confirmación de Llaves</th>
                                    <th class="bg-inverse text-white">Estadia de Llaves</th>
                                    <th class="bg-inverse text-white">Estadia Total</th>
                                    <th class="bg-inverse text-white">Cita de entrega</th>
                                    <th class="bg-inverse text-white">Maxima de Salida</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    Control de patios.
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    @include('Shipments.MonitorShipments.MonitorOperation.table')
    <script>
        function reloadtable() {
            $('#table-monitortrafico').DataTable().ajax.reload();
        }
    </script>

@endsection

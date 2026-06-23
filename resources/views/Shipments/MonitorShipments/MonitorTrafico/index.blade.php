@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Monitor de arribos.
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
                        <hr class="mt-4">
                        <div class="row">
                            <div class="col-lg-12">
                                <x-text-input
                                        name="shipmentid"
                                        label="Número de embarque"
                                />
                            </div>
                        </div>
                    </form>
                    <hr class="m-t-1">

                    <div class="row">
                        <div class="col-lg-12">
                            <table id="table-monitortrafico" class="table table-bordered  table-condensed table-hover">
                                <thead>
                                    <tr>
                                        <th>Embarque</th>
                                        <th>Cliente</th>
                                        <th>Ciudad</th>
                                        <th>Linea de transporte</th>
                                        <th>Fecha de carga</th>
                                        <th>Estatus</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-center">
                    Control de patios.
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    @include('Shipments.MonitorShipments.MonitorTrafico.table')
    <script>
        function reloadtable() {
            $('#table-monitortrafico').DataTable().ajax.reload();
        }
    </script>
    <script>
        $(document).ready(function () {
            setInterval(reloadtable, 1000 * 60 * 5);
        });
    </script>
@endsection

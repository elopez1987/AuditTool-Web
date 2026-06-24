@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    SEGUIMIENTO DE INCIDENCIA
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
                        <hr class="mb-3">
                        <div class="row">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="shipmentid">Embarque</label>
                                    <input type="text" class="form-control"  id="shipmentid" name="shipmentid">
                                </div>
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
                <div class="card-header font-10">
                    <i class="ti ti-minus text-black-50"></i> No requiere Firma
                    <i class="ti ti-close text-danger ml-2"></i> Pendiente
                    <i class="ti ti-check text-success ml-3"></i> Firmado
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-10 col-xs-12">
                            <table id="table-followupincidences"
                                   class="table table-bordered  table-condensed table-hover">
                                    <thead>
                                        <tr>
                                            <th>Embarque</th>
                                            <th>Transporte</th>
                                            <th>Cliente</th>
                                            <th>Ciudad</th>
                                            <th>Cita de entrega</th>
                                            <th>Estatus</th>
                                            <th>Fecha de alta</th>
                                            <th>Fecha de respuesta</th>
                                            <th>Estatus POD</th>
                                            <th>Firma Almacén</th>
                                            <th>Firma Transporte</th>
                                            <th>Cita de entrega</th>
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

@endsection

@section('script')
    @include('Shipments.FollowupIncidences.partials.table')
    <script>
        function reloadtable() {
            $('#table-followupincidences').DataTable().ajax.reload();
        }
    </script>
@endsection

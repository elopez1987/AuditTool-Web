@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Detalle de registros
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <a >
                                @include('buttons.reloadajax')
                            </a>
                            <a href="{{route('systemwhatsapps.index')}}">
                                @include('buttons.cancel')
                            </a>
                        </div>
                    </div>
                    <hr class="mb-3">
                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Descripción </label>
                                <input type="text" class="form-control" value="{{$systemwhatsapp->description}}"
                                       readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="phonenumber">Número de Celular </label>
                                <input type="text" class="form-control" id="phonenumber" name="phonenumber">
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="shipmentid">Embarque </label>
                                <input type="text" class="form-control" id="shipmentid" name="shipmentid"
                                       aria-describedby="name">
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="folionumber">Número de folio </label>
                                <input type="text" class="form-control" id="folionumber" name="folionumber"
                                       aria-describedby="name">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <table id="table-detailkardex"  class="table table-bordered table-striped table-condensed table-hover">
                                <thead>
                                <tr>
                                    <th class="bg-inverse text-white">Centro de distribución</th>
                                    <th class="bg-inverse text-white">Embarque</th>
                                    <th class="bg-inverse text-white">Folio de unidad</th>
                                    <th class="bg-inverse text-white">Mensaje</th>
                                    <th class="bg-inverse text-white">Contacto</th>
                                    <th class="bg-inverse text-white">Mensaje del servidor</th>
                                    <th class="bg-inverse text-white">ID del Servicio</th>
                                    <th class="bg-inverse text-white">Fecha del evento</th>
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

@section('script')
    @include('Configuration.SystemWhatsApps.partials.tableDetail')
    <script>
        function reloadtable() {
            $('#table-detailkardex').DataTable().ajax.reload();
        }
    </script>
@endsection

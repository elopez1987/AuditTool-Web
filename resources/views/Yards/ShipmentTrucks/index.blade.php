@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Capacidad de trailer
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-lg-12">
                                @can('shipmenttrucks.create')
                                    <a href="{{route('shipmenttrucks.create')}}">
                                        @include('buttons.add')
                                    </a>
                                @endcan
                                <a href="">
                                    @include('buttons.search')
                                </a>
                                <a>
                                  @include('buttons.reloadajax')
                                </a>
                                <a href="{{route('home')}}">
                                    @include('buttons.cancel')
                                </a>
                            </div>
                        </div>
                        <hr class="mb-3">
                        <div class="row">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="name">Nombre</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                           aria-describedby="name">
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-12">
                            <table id="table-trucks" class="table table-bordered table-condensed">
                                <thead>
                                <tr>
                                    <th>C.Distribución</th>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Tipo WMS</th>
                                    <th>Auxiliar 01</th>
                                    <th>Auxiliar 02</th>
                                    <th>Auxiliar 03</th>
                                    <th>Auxiliar 04</th>
                                    <th>Auxiliar 05</th>
                                    <th>Estatus</th>
                                    <th>Opciones</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            @include('layouts.Partials.Errors')
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    @can('shipmenttrucks.importtruck')
                        <a href="" data-target="#modal-upload" data-toggle="modal" data-placement="top">
                            <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md"
                                    data-toggle="tooltip" data-placement="top" title="Importar archivo">
                                <img src="{{asset('/ico/uploadcsv.png')}}" alt="" class="img-16">
                                <span style="font-size: 11px;"> Importar catalogo </span>
                            </button>
                        </a>
                        @include('Yards.ShipmentTrucks.uploadfile')
                    @endcan
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    @include('Yards.ShipmentTrucks.partials.table')
    <script>
        function reloadtable() {
            $('#table-trucks').DataTable().ajax.reload();
        }
    </script>
@endsection

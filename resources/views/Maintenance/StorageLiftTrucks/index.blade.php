@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Catalogo de montacargas
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-md-12">
                                @can('storagelifttrucks.create')
                                    <a href="{{route('storagelifttrucks.create')}}">
                                        @include('buttons.add')
                                    </a>
                                @endcan
                                <a href="">
                                    @include('buttons.search')
                                </a>
                                <a>
                                    @include('buttons.reloadajax')
                                </a>
                                <a href="{{route('menumaintenance')}}">
                                    @include('buttons.cancel')
                                </a>
                            </div>
                        </div>
                        <hr class="mb-3">
                        <div class="row">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="serialnumber">Número de serie</label>
                                    <input type="text" class="form-control"  id="serialnumber" name="serialnumber">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="numbertruck">Número de equipo</label>
                                    <input type="text" class="form-control"  id="numbertruck" name="numbertruck">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="statusid">Estatus del equipo <span style="color: red">*</span></label>
                                    <select name="statusid" class="form-control select2">
                                        <option value="" selected>Seleccione una opcion</option>
                                        @foreach($status as $model)
                                            <option value="{{$model->statusid}}"> {{$model->name}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-lg-12">
                            <table id="table-lifttruck" class="table table-bordered  table-condensed">
                                <thead>
                                <tr>
                                    <th class="bg-inverse text-white">C.Distribución</th>
                                    <th class="bg-inverse text-white">Número de serie</th>
                                    <th class="bg-inverse text-white">Número de equipo</th>
                                    <th class="bg-inverse text-white">Marca</th>
                                    <th class="bg-inverse text-white">Modelo</th>
                                    <th class="bg-inverse text-white">Tipo</th>
                                    <th class="bg-inverse text-white">F.Creación</th>
                                    <th class="bg-inverse text-white">Creado por</th>
                                    <th class="bg-inverse text-white">Estatus</th>
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

@section('script')
    @include('Maintenance.StorageLiftTrucks.partials.table')
    <script>
        function reloadtable() {
            $('#table-lifttruck').DataTable().ajax.reload();
        }
        $("#statusid").change(function () {
            reloadtable();
        });
    </script>
@endsection

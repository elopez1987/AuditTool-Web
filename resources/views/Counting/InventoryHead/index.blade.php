@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">Inventario</div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-lg-12">
                                @can('inventoryhead.create')
                                    <a href="{{route('inventoryhead.create')}}">
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
                        <hr class="m-t-1">
                        <div class="row">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="statusid">Estatus <span style="color: red">*</span></label>
                                    <select name="statusid" id="statusid" class="form-control select2">
                                        <option value="" selected>Seleccione una opción</option>
                                        @foreach($status as $model)
                                            <option value="{{$model->statusid}}"> {{$model->name}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-lg-12 col-xl-12">
                            <table id="table-inventoryhead" class="table table-bordered table-striped table-condensed table-hover">
                                <thead>
                                <tr>
                                    <th class="text-white">C.Distribución</th>
                                    <th class="text-white">Bodega</th>
                                    <th class="text-white">ID. de Inventario</th>
                                    <th class="text-white">Tipo</th>
                                    <th class="text-white">Descripción</th>
                                    <th class="text-white">F.Creación</th>
                                    <th class="text-white">F.Inicio</th>
                                    <th class="text-white">F.Cierre</th>
                                    <th class="text-white">Creado por</th>
                                    <th class="text-white">Tipo de conteo</th>
                                    <th class="text-white">Estatus</th>
                                    <th class="text-white">Opciones</th>
                                </tr>
                                </thead>
                                <tfoot>
                                <tr>
                                    <th class="text-white">C.Distribución</th>
                                    <th class="text-white">Bodega</th>
                                    <th class="text-white">ID. de Inventario</th>
                                    <th class="text-white">Tipo</th>
                                    <th class="text-white">Descripción</th>
                                    <th class="text-white">F.Creación</th>
                                    <th class="text-white">F.Inicio</th>
                                    <th class="text-white">F.Cierre</th>
                                    <th class="text-white">Creado por</th>
                                    <th class="text-white">Tipo de conteo</th>
                                    <th class="text-white">Estatus</th>
                                    <th class="text-white">Opciones</th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-center">Inventario Fiscal - YardTrax</div>
            </div>
        </div>
    </div>
@endsection

@section('css')

@endsection


@section('script')
    @include('Counting.InventoryHead.partials.table')
    <script>
        function reloadtable() {
            $('#table-inventoryhead').DataTable().ajax.reload();
        }
    </script>
    <script>
        $("#statusid").change(function () {
            reloadtable();
        });
    </script>
@endsection

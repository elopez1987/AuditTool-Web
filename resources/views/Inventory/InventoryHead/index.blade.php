@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                Inventario Fiscal
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-md-12">
                                @can('inventoryheads.create')
                                    <a href="{{route('inventoryheads.create')}}">
                                        @include('buttons.add')
                                    </a>
                                @endcan
                                <a href="">
                                    @include('buttons.search')
                                </a>
                                <a >
                                    @include('buttons.reloadajax')
                                </a>
                                <a href="{{route('home')}}">
                                    @include('buttons.cancel')
                                </a>
                            </div>
                        </div>
                        <hr class="m-t-3">
                        <div class="row">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="name">Nombre</label>
                                    <input type="text" class="form-control"  id="name" name="name" value="{{old('name')}}">
                                </div>
                            </div>
{{--                            <div class="col-lg-2 col-md-6 col-sm-12">--}}
{{--                                <div class="form-group">--}}
{{--                                    <label for="statusid">Estatus</label>--}}
{{--                                    <select name="statusid" class="form-control select2">--}}
{{--                                        <option value="">Selecciona una opción</option>--}}
{{--                                        @foreach($status as $model)--}}
{{--                                            <option value="{{$model->statusid}}"> {{$model->name}} </option>--}}
{{--                                        @endforeach--}}
{{--                                    </select>--}}
{{--                                </div>--}}
{{--                            </div>--}}
                        </div>
                    </form>

                    <div class="row">
                        <div class="col-12">
                            <table width="100%" id="table-head" class="table table-bordered  table-condensed">
                                <thead>
                                <tr>
                                    <th>C.Distribución</th>
                                    <th>Bodega</th>
                                    <th>Nombre</th>
                                    <th>Fecha de creación</th>
                                    <th>Creado por</th>
                                    <th>Inicio de inventario</th>
                                    <th>Fin de inventario</th>
                                    <th>Cerrado por</th>
                                    <th>Estatus</th>
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

@section('script')
    @include('Inventory.InventoryHead.partials.table')
    <script>
        function reloadtable() {
            $('#table-head').DataTable().ajax.reload();
        }
    </script>
@endsection

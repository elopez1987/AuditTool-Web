@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Tareas a operadores
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-md-12">
                                @can('storagetask.create')
                                    <a href="{{route('storagetask.create')}}">
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
                        <hr class="m-t-1">
                        <div class="row">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="folionumber">Folio de patios</label>
                                    <input type="text" class="form-control" id="folionumber" name="folionumber"
                                           onKeyUp=this.value=this.value.toUpperCase()
                                    >
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="barcode">Código de barra</label>
                                    <input type="text" class="form-control" id="barcode" name="barcode"
                                           onKeyUp=this.value=this.value.toUpperCase()
                                    >
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="boxnumber">Placa de caja</label>
                                    <input type="text" class="form-control" id="boxnumber" name="boxnumber"
                                           onKeyUp=this.value=this.value.toUpperCase()
                                    >
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="statusid">Estatus <span style="color: red">*</span></label>
                                    <select name="statusid" class="form-control select2">
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
                            <table id="table-trucks"  class="table table-bordered  table-condensed">
                                <thead>
                                <tr>
                                    <th class="bg-inverse text-white">C.Distribución</th>
                                    <th class="bg-inverse text-white">Bodega</th>
                                    <th class="bg-inverse text-white">Asignado a</th>
                                    <th class="bg-inverse text-white">Folio</th>
                                    <th class="bg-inverse text-white">C.Barra</th>
                                    <th class="bg-inverse text-white">Placa de Caja</th>
                                    <th class="bg-inverse text-white">Eco. de Caja</th>
                                    <th class="bg-inverse text-white">Operador</th>
                                    <th class="bg-inverse text-white">Tipo de Movimiento</th>
                                    <th class="bg-inverse text-white">Zona Destino</th>
                                    <th class="bg-inverse text-white">Ubicación Destino</th>
                                    <th class="bg-inverse text-white">Ubicación Actual</th>
                                    <th class="bg-inverse text-white">Estatus</th>
                                    <th class="bg-inverse text-white">F.Creación</th>
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
    @include('Yards.StorageTask.partials.table')
@endsection

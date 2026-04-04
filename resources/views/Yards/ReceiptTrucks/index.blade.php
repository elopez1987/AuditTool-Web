@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Inventario de tractos
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-md-12">
                                @can('receipttrucks.create')
                                    <a href="{{route('receipttrucks.create')}}">
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
                                    <label for="warehouseid">Bodega</label>
                                    <select name="warehouseid" id='warehouseid'  class="form-control select2">
                                        <option value="" selected>Seleccione una opción</option>
                                        @foreach($warehouses as $model)
                                            <option value="{{$model->warehouseid}}"> {{$model->name}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="folionumber">Número de folio</label>
                                    <input type="text" class="form-control numeric" id="folionumber" name="folionumber"
                                           onKeyUp=this.value=this.value.toUpperCase()
                                    >
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="trucknumber">Placa de tracto</label>
                                    <input type="text" class="form-control" id="trucknumber" name="trucknumber"
                                           onKeyUp=this.value=this.value.toUpperCase()
                                    >
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="econumber">Número de tracto</label>
                                    <input type="text" class="form-control" id="econumber" name="econumber"
                                           onKeyUp=this.value=this.value.toUpperCase()
                                    >
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="barcode">Código de barra</label>
                                    <input type="text" class="form-control numeric" id="barcode" name="barcode"
                                           onKeyUp=this.value=this.value.toUpperCase()
                                    >
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="name">Nombre de operador</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                           onKeyUp=this.value=this.value.toUpperCase()
                                    >
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="carrierid">Linea de transporte<span style="color: red">*</span></label>
                                    <select name="carrierid" id="carrierid" class="form-control select2">
                                        <option value="" selected>Seleccione una opción</option>
                                        @foreach($carriers as $model)
                                            <option value="{{$model->id}}"> {{$model->name}} </option>
                                        @endforeach
                                    </select>
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
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <table id="table-trucks" class="table table-bordered table-condensed table-hover table-uppercase">
                                <thead>
                                <tr>
                                    <th class="text-white">Bodega</th>
                                    <th class="text-white">Folio</th>
                                    <th class="text-white">Linea de transporte</th>
                                    <th class="text-white">Placas de tracto</th>
                                    <th class="text-white">Número de tracto</th>
                                    <th class="text-white">Nombre del operador</th>
                                    <th class="text-white">Celular</th>
                                    <th class="text-white">C.Barra</th>
                                    <th class="text-white">F.Entrada</th>
                                    <th class="text-white">Opciones</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    @can('receipttrucks.cleartruck')
                        <a href="" data-target="#modal-cleartruck" data-toggle="modal" data-placement="top">
                            <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md"
                                    data-toggle="tooltip" data-placement="top" title="Importar archivo">
                                <img src="{{asset('/ico/engine_gear_internet_option_security_setting_web_icon_127066.png')}}" alt="" class="img-16">
                                <span> Limpiar tractos.</span>
                            </button>
                        </a>
                        @include('Yards.ReceiptTrucks.cleartruck')
                    @endcan
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')

@endsection


@section('script')
    @include('Yards.ReceiptTrucks.partials.table')
    <script>
        function reloadtable() {
            $('#table-trucks').DataTable().ajax.reload();
        }
    </script>
    <script>
        $("#carrierid,#warehouseid").change(function () {
            reloadtable();
        });
    </script>
@endsection

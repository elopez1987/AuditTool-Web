@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Evidencias de embarque y recibo.
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form method="GET" id="search" role="form">
                                {{csrf_field()}}
                                <div class="row">
                                    <div class="col-md-12">
                                        @can('storageevidenceprocess.create')
                                            <a href="{{route('storageevidenceprocess.create')}}">
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
                                <div class="card-input">
                                    <div class="row">
                                        <div class="col-lg-2 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="shipmentid">Embarque</label>
                                                <input type="text" class="form-control" id="shipmentid"
                                                       name="shipmentid"
                                                       aria-describedby="name">
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="statusid">Estatus <span style="color: red">*</span></label>
                                                <select name="statusid" class="form-control select2">
                                                    <option value="" selected>Seleccione un opción:</option>
                                                    @foreach($status as $model)
                                                        <option value="{{$model->statusid}}"> {{$model->name}} </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="">Fecha de trabajo</label>
                                                <div class='input-group mb-3'>
                                                    <input type='text' id="daterange" name="daterange" class="form-control daterange"/>
                                                    <div class="input-group-append" >
                                                        <span class="input-group-text">
                                                            <span class="ti-calendar"></span>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-6 col-sm-12">
                                            <div id="customer">
                                                <div class="form-group">
                                                    <label for="customerid">Cliente</label>
                                                    <select name="customerid" id="customerid"
                                                            class="form-control select2 @error('customerid') is-invalid @enderror">
                                                        <option value="" selected> Seleccione una opcion</option>
                                                        @foreach($customers as $model)
                                                            <option value="{{$model->id}}"> {{$model->name}} </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="turnid">Turno <span style="color: red">*</span></label>
                                                <select name="turnid" id="turnid"
                                                        class="form-control select2 @error('turnid') is-invalid @enderror">
                                                    <option value="" selected>Selecciones un turno</option>
                                                    @foreach($turns as $model)
                                                        <option value="{{$model->id}}"> {{$model->name}} </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="categoryid">Categoria <span
                                                        style="color: red">*</span></label>
                                                <select name="categoryid" id="categoryid"
                                                        class="form-control select2 @error('categoryid') is-invalid @enderror">
                                                    <option value="" selected>Selecciones una opción</option>
                                                    @foreach($categories as $model)
                                                        <option
                                                            value="{{$model->id}}"> {{$model->description}} </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card-input">
                        <div class="row">
                            <div class="col-12">
                                <table id="table-evidence" class="table table-bordered table-striped table-condensed">
                                    <thead>
                                    <tr>
                                        <th class="bg-inverse text-white">C.Distribución</th>
                                        <th class="bg-inverse text-white">Bodega</th>
                                        <th class="bg-inverse text-white">Embarque</th>
                                        <th class="bg-inverse text-white">Turno</th>
                                        <th class="bg-inverse text-white">Origen</th>
                                        <th class="bg-inverse text-white">Fecha de trabajo</th>
                                        <th class="bg-inverse text-white">Fecha de cita</th>
                                        <th class="bg-inverse text-white">Cliente</th>
                                        <th class="bg-inverse text-white">Proveedor</th>
                                        <th class="bg-inverse text-white">Linea de transporte</th>
                                        <th class="bg-inverse text-white">Placa de unidad</th>
                                        <th class="bg-inverse text-white">Eco. De unidad</th>
                                        <th class="bg-inverse text-white">Cortina</th>
                                        <th class="bg-inverse text-white">Tipo de registro</th>
                                        <th class="bg-inverse text-white">Creado Por</th>
                                        <th class="bg-inverse text-white">Estatus</th>
                                        <th class="bg-inverse text-white">Opciones</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-center">
                    Evidencias / Evidencias de Proceso / Registros
                </div>
            </div>
        </div>
    </div>

@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('theme/assets/libs/daterangepicker/daterangepicker.css')}}">
@endsection

@section('script')
    @include('Evidence.StorageEvidences.partials.table')
    <script src="{{asset('/theme/assets/libs/daterangepicker/daterangepicker.js')}}"></script>
    <script src="{{asset('/theme/assets/libs/moment/moment.js')}}"></script>
    <script>
        function reloadtable() {
            $('#table-evidence').DataTable().ajax.reload();
        }
    </script>
    <script>
        $("#turnid,#categoryid,#customerid,#statusid").change(function () {
            reloadtable();
        });
    </script>
    <script>
        $('.daterange').daterangepicker(
            {
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear',
                    format: 'YYYY-MM-DD'
                }
            }
        );
    </script>
@endsection

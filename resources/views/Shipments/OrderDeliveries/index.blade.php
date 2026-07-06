@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Monitor de embarques
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form" class="r-separator">
                        {{csrf_field()}}
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <h5 class="card-title text-10 text-info font-light font-weight-light float-left"></h5>
                                @can('orderdeliveries.create')
                                    <a href="{{route('orderdeliveries.create')}}">
                                        @include('buttons.add')
                                    </a>
                                @endcan
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
                        <hr class="m-t-0">
                        <div class="row">

                            <x-select2
                                    name="warehouseid"
                                    label="Bodega"
                                    :options="$warehouses"
                                    valueField="warehouseid"
                                    textField="name"
                            />


                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="shipmentid">Embarque</label>
                                    <input type="text" class="form-control" id="shipmentid" name="shipmentid">
                                </div>
                            </div>

                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="workday">Fecha de trabajo</label>
                                    <input type="text" class="form-control" id="workday" name="workday">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="statusid">Estatus </label>
                                    <select name="statusid" id="statusid" class="form-control select2">
                                        <option value="" selected>Seleccione un valor</option>
                                        @foreach($status as $model)
                                            <option value="{{$model->statusid}}"> {{$model->name}} </option>
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
                <div class="card-header"></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <table id="table-orders"
                                   class="table table-bordered  table-condensed table-hover table-uppercase" style="width: 100%;">
                                <thead>
                                <tr>
                                    <th class="text-white">Bodega</th>
                                    <th class="text-white">Fecha</th>
                                    <th class="text-white">Turno</th>
                                    <th class="text-white">Embarque</th>
                                    <th class="text-white">Linea de Transporte</th>
                                    <th class="text-white">Cliente</th>
                                    <th class="text-white">Destino</th>
                                    <th class="text-white">Cita de carga</th>
                                    <th class="text-white">Cita de entrega</th>
                                    <th class="text-white">Estatus</th>
                                    <th class="text-white">Detalle</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header"></div>
                <div class="card-body">

                    @can('orderdeliveries.import')
                        <a href="" data-target="#modal-upload" data-toggle="modal" data-placement="top">
                            <button type="button"
                                    class="btn waves-effect waves-light btn-outline-info btn-md"
                                    data-toggle="tooltip" data-placement="top" title="Importar archivo">
                                <img src="{{asset('/ico/uploadcsv.png')}}" alt="" class="img-16">
                                <span class="text-button"> Importar embarques </span>
                            </button>
                        </a>
                        @include('Shipments.OrderDeliveries.uploadfile')
                    @endcan

                    @can('orderdeliveries.importcheck')
                        <a href="" data-target="#modal-uploadstock" data-toggle="modal" data-placement="top">
                            <button type="button"
                                    class="btn waves-effect waves-light btn-outline-info btn-md"
                                    data-toggle="tooltip" data-placement="top" title="Importar archivo">
                                <img src="{{asset('/ico/uploadcsv.png')}}" alt="" class="img-16">
                                <span class="text-button"> Importar pedidos </span>
                            </button>
                        </a>
                        @include('Shipments.OrderDeliveries.uploadstock')
                    @endcan

                    @can('orderdeliveries.importbilling')
                        <a href="" data-target="#modal-uploadbilling" data-toggle="modal" data-placement="top">
                            <button type="button"
                                    class="btn waves-effect waves-light btn-outline-info btn-md"
                                    data-toggle="tooltip" data-placement="top" title="Importar archivo">
                                <img src="{{asset('/ico/uploadcsv.png')}}" alt="" class="img-16">
                                <span class="text-button"> Importar facturas </span>
                            </button>
                        </a>
                        @include('Shipments.OrderDeliveries.uploadbilling')
                    @endcan
                    @can('orderdeliveries.importcomment')
                        <a href="" data-target="#modal-comment" data-toggle="modal" data-placement="top">
                            <button type="button"
                                    class="btn waves-effect waves-light btn-outline-info btn-md"
                                    data-toggle="tooltip" data-placement="top" title="Importar archivo">
                                <img src="{{asset('/ico/uploadcsv.png')}}" alt="" class="img-16">
                                <span class="text-button"> Importar comentarios </span>
                            </button>
                        </a>
                        @include('Shipments.OrderDeliveries.importcomment')
                    @endcan
                </div>
            </div>
        </div>
    </div>
    @include('layouts.Partials.Errors')
@endsection

@section('css')
    <link rel="stylesheet"
          href="{{asset('/theme/assets/libs/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css')}}">
    <link rel="stylesheet" href="{{asset('/theme/assets/libs/quill/dist/quill.snow.css')}}">
@endsection

@section('script')
    <script src="{{asset('/theme/assets/libs/moment/moment.js')}}"></script>
    <script
            src="{{asset('/theme/assets/libs/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker-custom.js')}}"></script>
    <script src="{{asset('/theme/assets/libs/quill/dist/quill.min.js')}}"></script>
    @include('Shipments.OrderDeliveries.partials.table')
    <script>
        $('#workday').bootstrapMaterialDatePicker({format: 'YYYY-MM-DD', weekStart: 1, time: false});

        function reloadtable() {
            $('#table-orders').DataTable().ajax.reload();
        }
    </script>
    <script>
        $("#statusid,#turnid,#warehouseid").change(function () {
            reloadtable();
        });
    </script>

@endsection

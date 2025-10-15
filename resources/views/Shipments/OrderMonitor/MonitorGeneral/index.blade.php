@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Monitor de seguimiento de embarques
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form" class="r-separator">
                        {{csrf_field()}}
                        <div class="row mb-3">
                            <div class="col-md-12">
                                @can('ordermonitor.create')
                                    <a href="{{route('ordermonitor.create')}}">
                                        @include('buttons.add')
                                    </a>
                                @endcan
                                <a href="{{route('menushipment')}}">
                                    @include('buttons.cancel')
                                </a>
                                <a href="">
                                    @include('buttons.search')
                                </a>
                                <a>
                                    @include('buttons.reloadajax')
                                </a>
                            </div>
                        </div>
                        <hr class="m-t-0">
                        <div class="row">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="name">Embarque</label>
                                    <input type="text" class="form-control" id="shipmentid" name="shipmentid"
                                           aria-describedby="name">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="statusid">Estatus <span style="color: red">*</span></label>
                                    <select name="statusid" class="form-control select2">
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
                        <div class="col-12">
                            <table id="table-monitorgeneral"
                                   class="table table-bordered table-striped table-condensed table-hover">
                                <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Embarque</th>
                                    <th>Estatus</th>
                                    <th>Cita con cliente</th>
                                    {{--                                        <th>Inicio de Preparación</th>--}}
                                    {{--                                        <th>Arribo de Unidad</th>--}}
                                    {{--                                        <th>Inicio de Carga</th>--}}
                                    <th>Enviar a Facturación</th>
                                    <th>Inicio de Facturación</th>
                                    <th>Autorizar Salida</th>
                                    <th>Salida maxima</th>
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
    <link rel="stylesheet"
          href="{{asset('/theme/assets/libs/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css')}}">
@endsection

@section('script')
    <script src="{{asset('/theme/assets/libs/moment/moment.js')}}"></script>
    <script
            src="{{asset('/theme/assets/libs/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker-custom.js')}}"></script>
    <script>
        $('#workday').bootstrapMaterialDatePicker({format: 'YYYY-MM-DD', weekStart: 1, time: false});
    </script>
    @include('Shipments.OrderMonitor.MonitorGeneral.table')
    <script>
        function reloadtable() {
            $('#table-monitorgeneral').DataTable().ajax.reload();
        }
    </script>
@endsection



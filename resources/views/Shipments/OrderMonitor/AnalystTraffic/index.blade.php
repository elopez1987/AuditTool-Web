@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Monitor de tráfico y facturación
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form" class="r-separator">
                        {{csrf_field()}}
                        <div class="row mb-3">
                            <div class="col-md-8">
                                <a href="{{route('menushipment')}}" >
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
                                    <label for="turnid">Turno<span style="color: red">*</span></label>
                                    <select name="turnid" id="turnid" class="form-control select2">
                                        <option value="" selected>Seleecione una opción</option>
                                        @foreach($turns as $model)
                                            <option value="{{$model->id}}"> {{$model->name}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="shipmentid">Embarque</label>
                                    <input type="text" class="form-control"  id="shipmentid" name="shipmentid">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="statusid">Estatus <span style="color: red">*</span></label>
                                    <select name="statusid" id="statusid" class="form-control select2">
                                        <option value="" selected>Seleecione una opción</option>
                                        @foreach($status as $model)
                                            <option value="{{$model->statusid}}"> {{$model->name}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="workday">Fecha de trabajo</label>
                                    <input type="text" class="form-control"  id="workday" name="workday">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header"></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <table id="table-traffics" class="table table-bordered table-striped table-condensed  table-hover table-uppercase">
                                <thead>
                                <tr>
                                    <th class="bg-inverse text-white">Opciones</th>
                                    <th class="bg-inverse text-white">¿Datos?</th>
                                    <th class="bg-inverse text-white">Fecha</th>
                                    <th class="bg-inverse text-white">Turno</th>
                                    <th class="bg-inverse text-white">Embarque</th>
                                    <th class="bg-inverse text-white">Estatus</th>
                                    <th class="bg-inverse text-white">Cortina</th>
                                    <th class="bg-inverse text-white">¿Llaves?</th>
                                    <th class="bg-inverse text-white">Comentario</th>
                                    <th class="bg-inverse text-white">E.de llegada</th>
                                    <th class="bg-inverse text-white">Operador</th>
                                    <th class="bg-inverse text-white">Linea transportista</th>
                                    <th class="bg-inverse text-white">Placa de caja</th>
                                    <th class="bg-inverse text-white">Cliente</th>
                                    <th class="bg-inverse text-white">Destino</th>
                                    <th class="bg-inverse text-white">Cita de carga</th>
                                    <th class="bg-inverse text-white">Cita con cliente</th>
                                    <th class="bg-inverse text-white">Hora de llegada</th>
                                    <th class="bg-inverse text-white">mesa de control</th>
                                    <th class="bg-inverse text-white">T. de transito</th>
                                    <th class="bg-inverse text-white">Aut. de salida</th>
                                    <th class="bg-inverse text-white">Salida maxima</th>
                                    <th class="bg-inverse text-white">Horas restantes</th>
                                    <th class="bg-inverse text-white">D.Negocio</th>
                                    <th class="bg-inverse text-white">Planeador</th>
                                    <th class="bg-inverse text-white">Precarga</th>
                                    <th class="bg-inverse text-white">F.Patios</th>
                                    <th class="bg-inverse text-white">% de armado</th>
                                    <th class="bg-inverse text-white">Cajas</th>
                                    <th class="bg-inverse text-white">Pallets</th>
                                    <th class="bg-inverse text-white">Peso Total</th>
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
    <link rel="stylesheet" href="{{asset('/theme/assets/libs/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css')}}">
    <link rel="stylesheet" href="{{asset('/theme/assets/libs/quill/dist/quill.snow.css')}}">
@endsection

@section('script')
    @include('Shipments.OrderMonitor.AnalystTraffic.partials.table')
    <script src="{{asset('/theme/assets/libs/moment/moment.js')}}"></script>
    <script
        src="{{asset('/theme/assets/libs/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker-custom.js')}}"></script>
    <script>
        $('#workday').bootstrapMaterialDatePicker({format: 'YYYY-MM-DD', weekStart: 1, time: false});
    </script>
    <script>
        function reloadtable() {
            $('#table-traffics').DataTable().ajax.reload();
        }
    </script>
    <script>
        $("#statusid,#turnid,#warehouseid").change(function () {
            reloadtable();
        });
    </script>
@endsection

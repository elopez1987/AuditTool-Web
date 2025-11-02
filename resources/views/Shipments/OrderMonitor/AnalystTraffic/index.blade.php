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
                            <table id="table-traffics" class="table table-bordered  table-condensed  table-hover table-uppercase">
                                <thead>
                                <tr>
                                    <th >Opciones</th>
                                    <th >¿Datos?</th>
                                    <th >Fecha</th>
                                    <th >Turno</th>
                                    <th >Embarque</th>
                                    <th >Estatus</th>
                                    <th >Cortina</th>
                                    <th >¿Llaves?</th>
                                    <th >Comentario</th>
                                    <th >E.de llegada</th>
                                    <th >Operador</th>
                                    <th >Linea transportista</th>
                                    <th >Placa de caja</th>
                                    <th >Cliente</th>
                                    <th >Destino</th>
                                    <th >Cita de carga</th>
                                    <th >Cita con cliente</th>
                                    <th >Hora de llegada</th>
                                    <th >mesa de control</th>
                                    <th >T. de transito</th>
                                    <th >Aut. de salida</th>
                                    <th >Salida maxima</th>
                                    <th >Horas restantes</th>
                                    <th >D.Negocio</th>
                                    <th >Planeador</th>
                                    <th >Precarga</th>
                                    <th >F.Patios</th>
                                    <th >% de armado</th>
                                    <th >Cajas</th>
                                    <th >Pallets</th>
                                    <th>Peso Total</th>
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

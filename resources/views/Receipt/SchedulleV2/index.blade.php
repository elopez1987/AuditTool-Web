@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Horario de descarga
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-md-12">
                                <a>
                                    @include('buttons.reloadajax')
                                </a>
                                <a href="{{route('menureceipt')}}">
                                    @include('buttons.cancel')
                                </a>
                            </div>
                        </div>
                        <hr class="mb-3">
                        <div class="row">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="workday"> {{ ui_label('appointmentschedule_workday_index','Fecha de trabajo') }}</label>
                                    <input type="text" class="form-control" id="workday" name="workday">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="shipmentid"> {{ ui_label('appointmentschedule_shipmentid_index','Número de recibo') }}</label>
                                    <input type="text" class="form-control" id="shipmentid" name="shipmentid">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="statusid"> {{ ui_label('appointmentschedule_statusid_index','Estatus') }}</label>
                                    <select name="statusid" id="statusid" class="form-control select2">
                                        <option value="" selected>Selecione una opción</option>
                                        @foreach($status as $model)
                                            <option value="{{$model->statusid}}"> {{$model->name}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-1 col-md-6 col-sm-12" id="reset-filter">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md btn-block" id="btn-reset-filter">
                                        <i class="ti ti-reload"></i> <span class="text-button">Limpiar Filtros</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="row">
                        <div class="col-lg-12">
                            <table id="schedulle" class="table table-bordered table-hover  table-condensed">
                                <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>C.Distribución</th>
                                    <th>Bodega</th>
                                    <th>Fecha de trabajo</th>
                                    <th>Secuencia</th>
                                    <th>Horario</th>
                                    <th>Estatus de reserva</th>
                                    <th>Embarque</th>
                                    <th>Proveedor</th>
                                    <th>Material</th>
                                    <th>Cantidad</th>
                                    <th>Tipo de embarque</th>
                                    <th>Linea de transporte</th>
                                    <th>Factura</th>
                                    <th>PO</th>
                                    <th>Prioridad</th>
                                    <th>Cortina</th>
                                    <th>Observaciones</th>
                                    <th>Estatus de descarga</th>
                                    <th>Hora de arribo</th>
                                    <th>Hora de enrampe</th>
                                    <th>Inicio de descarga</th>
                                    <th>Fin de descarga</th>
                                    <th>Salida de unidad</th>
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
@endsection


@section('script')

    <script src="{{asset('/theme/assets/libs/moment/moment.js')}}"></script>
    <script src="{{asset('/theme/assets/libs/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker-custom.js')}}"></script>
    @include('Receipt.SchedulleV2.partials.table')
    <script>
        $('#workday').bootstrapMaterialDatePicker({format: 'YYYY-MM-DD', weekStart: 1, time: false});
    </script>
    <script>
        function reloadtable() {
            $('#schedulle').DataTable().ajax.reload();
        }
    </script>
    <script>
        $(document).ready(function () {
            setInterval(reloadtable, 1000 * 60 * 5);
        });
    </script>
    <script>
        $("#statusid,#workday").change(function () {
            let workdayselect = document.getElementById("workday").value;
            sessionStorage.setItem("workdayop", workdayselect);
            reloadtable();
        });
        $("#shipmentid").blur(function () {
            let nom = document.getElementById("shipmentid").value;
            sessionStorage.setItem("shipmentidop", nom);
            reloadtable();
        });
    </script>
    <script>
        function getWorkdayDate() {
            const now = moment();
            const currentHour = now.hour();
            const currentMinute = now.minute();
            
            // Si la hora está entre 00:01 y 07:00 (12:01 AM a 7:00 AM)
            if (currentHour < 7 || (currentHour === 0 && currentMinute >= 1)) {
                // Restar 1 día
                return now.subtract(1, 'days').format('YYYY-MM-DD');
            }
            
            // Si la hora está entre 07:00 y 23:59
            return now.format('YYYY-MM-DD');
        }

        $(document).ready(function () {
            document.getElementById("shipmentid").value = sessionStorage.getItem('shipmentidop');
            
            // Si no hay un valor guardado en sessionStorage, establecer la fecha automáticamente
            const savedWorkday = sessionStorage.getItem('workdayop');
            if (!savedWorkday) {
                document.getElementById("workday").value = getWorkdayDate();
            } else {
                document.getElementById("workday").value = savedWorkday;
            }
            
            reloadtable();
        });
    </script>
    <script>
        $("#btn-reset-filter").click(function () {
            document.getElementById("workday").value = '';
            document.getElementById("shipmentid").value = '';
            $('#statusid').val('').trigger('change');
            sessionStorage.removeItem('workdayop');
            sessionStorage.removeItem('shipmentidop');
            reloadtable();
        });
    </script>

@endsection

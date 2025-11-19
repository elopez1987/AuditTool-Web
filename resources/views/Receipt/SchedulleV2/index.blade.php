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
                                    <label for="workday">Fecha de trabajo</label>
                                    <input type="text" class="form-control" id="workday" name="workday">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="shipmentid">Embarque</label>
                                    <input type="text" class="form-control" id="shipmentid" name="shipmentid">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="statusid">Estatus <span style="color: red">*</span></label>
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
                                        <i class="fas fa-undo text-button"></i> <span class="text-button">Limpiar Filtros</span>
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
                                    <th>Estatus</th>
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
        $(document).ready(function () {
            document.getElementById("shipmentid").value = sessionStorage.getItem('shipmentidop');
            document.getElementById("workday").value = sessionStorage.getItem('workdayop');
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

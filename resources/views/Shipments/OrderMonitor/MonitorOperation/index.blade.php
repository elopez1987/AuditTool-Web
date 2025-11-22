@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Monitor de operaciones
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form" class="r-separator">
                        {{csrf_field()}}
                        <div class="row mb-3">
                            <div class="col-md-12">
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
                                    <label for="warehouseid">Bodega <span style="color: red">*</span></label>
                                    <select name="warehouseid" id="warehouseid" class="form-control select2">
                                        @foreach($warehouses as $warehouse)
                                            <option value="{{$warehouse->warehouseid}}"> {{$warehouse->name}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="shipmentid">Embarque</label>
                                    <input type="text" class="form-control" id="shipmentid" name="shipmentid"
                                           onKeyUp=this.value=this.value.toUpperCase()
                                    >
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="statusid">Estatus <span style="color: red">*</span></label>
                                    <select name="statusid[]" id="statusid" class="form-control select2" multiple="multiple">
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
                        @foreach($statusQuantitys as $status)
                            <div class="col-lg-1 col-md-3">
                                <div class="card card-shipment">
                                        <div class="d-flex flex-row">
                                            <div class="p-10">
                                                <h3 class="text-black-50 m-b-0 ">{{$status->total}}</h3>
                                                <span class="text-black">{{$status->status->name}}</span>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
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
                        <div class="col-12">
                            <table id="table-monitoroperation"
                                   class="table table-bordered table-condensed table-list table-hover table-uppercase">
                                <thead>
                                <tr>
                                    <th >Opciones</th>
                                    <th >F. de trabajo</th>
                                    <th >Turno</th>
                                    <th >Prioridad</th>
                                    <th >Embarque</th>
                                    <th >Estatus</th>
                                    <th >Cliente</th>
                                    <th >Destino</th>
                                    <th >Cortina</th>
                                    <th >Precarga</th>
                                    <th >¿Enrampado?</th>
                                    <th >¿Llaves?</th>
                                    <th >Cita de carga</th>
                                    <th >Cita con cliente</th>
                                    <th >Max.Salida</th>
                                    <th >% de armado</th>
                                    <th >Full pallet</th>
                                    <th >Total de cajas</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card-footer"></div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link rel="stylesheet"
          href="{{asset('/theme/assets/libs/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css')}}">
    <link rel="stylesheet" href="{{asset('/theme/assets/libs/quill/dist/quill.snow.css')}}">
    <style>
        .card-shipment{
            border-left: 3px solid #b0bec5 !important;
        }
    </style>
@endsection

@section('script')
    @include('Shipments.OrderMonitor.MonitorOperation.partials.table')
    <script src="{{asset('/theme/assets/libs/moment/moment.js')}}"></script>
    <script src="{{asset('/theme/assets/libs/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker-custom.js')}}"></script>
    <script>
        $('#workday').bootstrapMaterialDatePicker({format: 'YYYY-MM-DD', weekStart: 1, time: false});
    </script>
    <script>
        function reloadtable() {
            $('#table-monitoroperation').DataTable().ajax.reload();
        }
    </script>
    <script>
        $(document).ready(function () {
            setInterval(reloadtable, 1000 * 60 * 5);
        });
    </script>
    <script>
        $("#statusid,#warehouseid").change(function () {
            reloadtable();
        });
        $("#shipmentid").blur(function () {
            reloadtable();
            let nom = document.getElementById("shipmentid").value;
            sessionStorage.setItem("shipmentidop", nom);
        });
        // Guardar valores seleccionados de statusid en sessionStorage
        $("#statusid").on('change', function () {
            let selectedValues = $(this).val();
            if (selectedValues && selectedValues.length > 0) {
                sessionStorage.setItem('statusidop', JSON.stringify(selectedValues));
            } else {
                sessionStorage.removeItem('statusidop');
            }
        });
    </script>
    <script>
        $(document).ready(function () {
            document.getElementById("shipmentid").value = sessionStorage.getItem('shipmentidop');
            
            // Cargar valores guardados de statusid
            let savedStatus = sessionStorage.getItem('statusidop');
            if (savedStatus) {
                let statusValues = JSON.parse(savedStatus);
                $('#statusid').val(statusValues).trigger('change');
            }
            
            reloadtable();
        });
    </script>
@endsection

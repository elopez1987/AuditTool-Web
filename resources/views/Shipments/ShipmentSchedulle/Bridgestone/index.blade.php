@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Horario de Carga
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form" class="r-separator">
                        {{csrf_field()}}
                        <div class="row mb-3">
                            <div class="col-md-8">
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
                                    <select name="statusid" id="statusid" class="form-control select2">
                                        <option value="" selected>Seleeciona un valor</option>
                                        @foreach($status as $model)
                                            <option value="{{$model->statusid}}"> {{$model->name}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="workday">Fecha de trabajo</label>
                                    <input type="text" class="form-control" id="workday" name="workday" >
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
                        <div class="col-12">
                            <table id="table-schedullebrid"
                                   class="table table-bordered table-striped table-condensed table-list table-hover table-uppercase">
                                <thead>
                                <tr>
                                    <th class="bg-inverse text-white">Opciones</th>
                                    <th class="bg-inverse text-white">Embarque</th>
                                    <th class="bg-inverse text-white">Destino</th>
                                    <th class="bg-inverse text-white">Linea de Transporte</th>
                                    <th class="bg-inverse text-white">Tipo de Transporte</th>
                                    <th class="bg-inverse text-white">Cita de carga</th>
                                    <th class="bg-inverse text-white">Canal</th>
                                    <th class="bg-inverse text-white">Cortina</th>
                                    <th class="bg-inverse text-white">Estatus</th>
                                    <th class="bg-inverse text-white">Placa de caja</th>
                                    <th class="bg-inverse text-white">Eco. de caja</th>
                                    <th class="bg-inverse text-white">ETA</th>
                                    <th class="bg-inverse text-white">Cantidad de llantas</th>
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
    <link rel="stylesheet" href="{{asset('/theme/assets/libs/quill/dist/quill.snow.css')}}">
@endsection

@section('script')
    @include('Shipments.ShipmentSchedulle.Bridgestone.partials.table')

    <script src="{{asset('/theme/assets/libs/moment/moment.js')}}"></script>
    <script src="{{asset('/theme/assets/libs/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker-custom.js')}}"></script>
    <script>
        $('#workday').bootstrapMaterialDatePicker({format: 'YYYY-MM-DD', weekStart: 1, time: false});
    </script>
    <script>
        function reloadtable() {
            $('#table-schedullebrid').DataTable().ajax.reload();
        }
    </script>
    <script>
        $(document).ready(function () {
            setInterval(reloadtable, 1000 * 60 * 5);
        });
    </script>
    <script>
        $("#statusid,#warehouseid,#workday").change(function () {
            let workdayselect = document.getElementById("workday").value;
            sessionStorage.setItem("workdayop", workdayselect);
            reloadtable();
        });
        $("#shipmentid,#workday").blur(function () {
            reloadtable();
            let nom = document.getElementById("shipmentid").value;
            sessionStorage.setItem("shipmentidop", nom);
        });
    </script>
    <script>
        $(document).ready(function () {
            document.getElementById("shipmentid").value = sessionStorage.getItem('shipmentidop');
            document.getElementById("workday").value = sessionStorage.getItem('workdayop');
            reloadtable();
        });
    </script>
@endsection

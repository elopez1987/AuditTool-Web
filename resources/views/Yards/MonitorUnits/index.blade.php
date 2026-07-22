@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Monitor de cajas en patio
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-lg-12">
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
                                    <label for="folionumber">Número de folio</label>
                                    <input type="text" class="form-control numeric" id="folionumber" name="folionumber"
                                           onKeyUp=this.value=this.value.toUpperCase()
                                    >
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="boxnumber">Placa de caja</label>
                                    <input type="text" class="form-control " id="boxnumber" name="boxnumber"
                                           onKeyUp=this.value=this.value.toUpperCase()
                                    >
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="shipmentid">Número de embarque</label>
                                    <input type="text" class="form-control" id="shipmentid" name="shipmentid"
                                           onKeyUp=this.value=this.value.toUpperCase()
                                    >
                                </div>
                            </div>
{{--                            <div class="col-lg-2 col-md-6 col-sm-12">--}}
{{--                                <div class="form-group">--}}
{{--                                    <label for="razonstatusid">Concepto de entrada</label>--}}
{{--                                    <select name="razonstatusid" id="razonstatusid" class="form-control select2">--}}
{{--                                        <option value="" selected>Seleccione una opción</option>--}}
{{--                                        @foreach($razon as $model)--}}
{{--                                            <option value="{{$model->id}}"> {{$model->name}} </option>--}}
{{--                                        @endforeach--}}
{{--                                    </select>--}}
{{--                                </div>--}}
{{--                            </div>--}}
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
                        <div class="col-lg-12 ">
                            <table id="table-monitorbox" class="table table-bordered table-condensed table-hover table-uppercase ">
                                <thead>
                                <tr>
                                    <th class="text-white">Folio</th>
                                    <th class="text-white">Código de barra</th>
                                    <th class="text-white">Embarque</th>
                                    <th class="text-white">Linea de transporte</th>
                                    <th class="text-white">Placas de caja</th>
                                    <th class="text-white">Eco. De caja</th>
                                    <th class="text-white">Concepto de entrada</th>
                                    <th class="text-white">Hora de llegada</th>
                                    <th class="text-white">R. mesa de control</th>
                                    <th class="text-white">Hora de enrampe</th>
                                    <th class="text-white">Registro de llaves</th>
                                    <th class="text-white">Inicio de proceso</th>
                                    <th class="text-white">Fin de proceso</th>
                                    <th class="text-white">Liberación de unidad</th>
{{--                                    <th class="text-white">T.Entrada</th>--}}
{{--                                    <th class="text-white">T. Inicio de carga</th>--}}
{{--                                    <th class="text-white">T.de carga</th>--}}
                                    <th class="text-white">Tiempo de proceso</th>
                                    <th class="text-white">Estadia total</th>
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

@endsection


@section('script')
    @include('Yards.MonitorUnits.partials.table')
    <script>
        function reloadtable() {
            $('#table-monitorbox').DataTable().ajax.reload();
        }
    </script>
    <script>
        $("#razonstatusid").change(function () {
            reloadtable();
        });
    </script>
@endsection

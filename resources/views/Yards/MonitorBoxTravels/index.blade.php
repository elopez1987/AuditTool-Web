@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Monitor de cajas en circuito
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
                                    <label for="folionumber">Folio de planta</label>
                                    <input type="text" class="form-control numeric" id="folionumber" name="folionumber"
                                           onKeyUp=this.value=this.value.toUpperCase()
                                    >
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="folionumberwm">Folio de Cedis</label>
                                    <input type="text" class="form-control numeric" id="folionumberwm"
                                           name="folionumberwm"
                                           onKeyUp=this.value=this.value.toUpperCase()
                                    >
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="boxnumber">Placa de caja</label>
                                    <input type="text" class="form-control" id="boxnumber" name="boxnumber"
                                           onKeyUp=this.value=this.value.toUpperCase()
                                    >
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
                            <table id="table-monitortravels" class="table table-bordered table-striped table-condensed table-hover table-uppercase ">
                                <thead>
                                <tr>
                                    <th colspan="8"
                                        style="background-color: #005b4f !important;color: black !important;text-align: center !important;font-weight: 800 !important;font-size: 13px !important;">
                                        Datos Generales
                                    </th>
                                    <th colspan="9"
                                        style="background-color: #00897b !important;color: black !important;text-align: center !important;font-weight: 800 !important;font-size: 13px !important;">
                                        Tiempos de planta
                                    </th>
                                    <th colspan="1"
                                        style="background-color:#ba2d65 !important;color: black !important;text-align: center !important;font-weight: 800 !important;font-size: 13px !important;">
                                        Trayecto (Min)
                                    </th>
                                    <th colspan="10"
                                        style="background-color: #43a047 !important;color: black !important;text-align: center !important;font-weight: 800 !important;font-size: 13px !important;">
                                        Tiempos de cedis
                                    </th>
                                </tr>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Código</th>
                                    <th>Linea</th>
                                    <th>Placa de caja</th>
                                    <th>Operador</th>
                                    <th>Tipo</th>
                                    <th>Embarque</th>
                                    <th>F.Inicio de vuelta</th>
                                    <th>Folio</th>
                                    <th>Ingreso</th>
                                    <th>Llegada a mesa</th>
                                    <th>Enrampe</th>
                                    <th>Inicio proceso</th>
                                    <th>Fin proceso</th>
                                    <th>Autoriza salida</th>
                                    <th>Salida</th>
                                    <th>Estadia</th>
                                    <th>Tiempo de trayecto</th>
                                    <th>Folio</th>
                                    <th>Ingreso</th>
                                    <th>Llegada a mesa</th>
                                    <th>Enrampe</th>
                                    <th>Inicio proceso</th>
                                    <th>Fin proceso</th>
                                    <th>Autoriza salida</th>
                                    <th>Salida</th>
                                    <th>Estadia</th>
                                    <th>Opciones</th>
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
    @include('Yards.MonitorBoxTravels.partials.table')
    <script>
        function reloadtable() {
            $('#table-monitortravels').DataTable().ajax.reload();
        }
    </script>
@endsection

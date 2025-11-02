@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Reporte de estadias unidades cargadas
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card-input">
                                <form method="GET" id="search" role="form">
                                    {{csrf_field()}}
                                    <div class="row">
                                        <div class="col-md-12">
                                            <a>
                                                @include('buttons.reloadajax')
                                            </a>
                                            <a href="{{route('home')}}">
                                                @include('buttons.cancel')
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4 p-4">
                        <div class="col-lg-12 col-xs-12">
                            <div class="card-input">
                                <table id="table-stays" class="table table-bordered  table-condensed">
                                    <thead>
                                    <tr>
                                        <th class="bg-inverse text-white">Bodega</th>
                                        <th class="bg-inverse text-white">Folio</th>
                                        <th class="bg-inverse text-white">Embarque</th>
                                        <th class="bg-inverse text-white">Linea</th>
                                        <th class="bg-inverse text-white">Placa de Caja</th>
                                        <th class="bg-inverse text-white">Eco. de Caja</th>
                                        <th class="bg-inverse text-white">Tipo de Unidad</th>
                                        <th class="bg-inverse text-white">Capacidad de Unidad</th>
                                        <th class="bg-inverse text-white">Ubicación</th>
                                        <th class="bg-inverse text-white">Estatus de Unidad</th>
                                        <th class="bg-inverse text-white">Estatus de Folio</th>
                                        <th class="bg-inverse text-white">F.Entrada</th>
                                        <th class="bg-inverse text-white">F.Autorización</th>
                                        <th class="bg-inverse text-white">Motivo de Entrada</th>
                                        <th class="bg-inverse text-white">Origen</th>
                                        <th class="bg-inverse text-white">Estadía Total</th>
                                        <th class="bg-inverse text-white">Tiempo de autorización de salida</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script>
        function reloadtable() {
            $('#table-stays').DataTable().ajax.reload();
        }
    </script>
    @include('Yards.ReportStays.partials.table')
@endsection

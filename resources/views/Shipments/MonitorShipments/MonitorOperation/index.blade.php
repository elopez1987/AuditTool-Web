@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                   MONITOR DE OPERACIONES.
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-md-12">
                                <a href="">
                                    @include('buttons.search')
                                </a>
                                <a>
                                    @include('buttons.reloadajax')
                                </a>
                                <a href="{{route('menushipment')}}">
                                    @include('buttons.cancel')
                                </a>
                            </div>
                        </div>
                        <hr class="m-t-1">
                    </form>
                    <hr class="m-t-1 m-b-1">
                    <div class="row">
                        @foreach($statusQuantitys as $status)
                            <div class="col-lg-1 col-md-3">
                                <div class="card " style="border: 1px solid #cfdd01;border-radius: 8px">
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
                    <hr class="m-t-1 m-b-1">
                    <div class="row">
                        <div class="col-lg-12">
                            <table id="table-monitortrafico"
                                   class="table table-bordered  table-condensed table-hover">
                                <thead>
                                <tr>
                                    <th class="bg-inverse text-white">Embarque</th>
                                    <th class="bg-inverse text-white">Cliente</th>
                                    <th class="bg-inverse text-white">Ciudad</th>
                                    <th class="bg-inverse text-white">Linea de transporte</th>
                                    <th class="bg-inverse text-white">Estatus Almacén</th>
                                    <th class="bg-inverse text-white">Estatus de unidad</th>
                                    <th class="bg-inverse text-white">Comentarios</th>
                                    <th class="bg-inverse text-white">Ingreso de Unidad</th>
                                    <th class="bg-inverse text-white">Confirmación de Llaves</th>
                                    <th class="bg-inverse text-white">Estadia Total</th>
                                    <th class="bg-inverse text-white">Cita de entrega</th>
                                    <th class="bg-inverse text-white">Maxima de Salida</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>

                    <hr class="mt-3 mb-3">

                    <div class="row">
                        <div class="col-lg-6">
                            <table class="table table-bordered table-striped table-condensed table-hover tablegeneral" width="100%">
                                <thead>
                                <tr>
                                    <th>Fecha de trabajo</th>
                                    <th>Estatus de transporte</th>
                                    <th>Programado</th>
                                    <th>Preparando</th>
                                    <th>Preparado en piso</th>
                                    <th>Por Cargar</th>
                                    <th>Cargando</th>
                                    <th>Cargado</th>
                                    <th>Doc. Entregada a FAC</th>
                                    <th>Doc. Entregado</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($monitorStatus as $item)
                                    <tr>
                                        <td>{{$item->workday}}</td>
                                        <td>{{$item->statustransporte}}</td>
                                        <td>{{$item->PROGRAMADO}}</td>
                                        <td>{{$item->PREPARANDO}}</td>
                                        <td>{{$item->PREPARADO_EN_PISO}}</td>
                                        <td>{{$item->POR_CARGAR}}</td>
                                        <td>{{$item->CARGANDO}}</td>
                                        <td>{{$item->CARGADO}}</td>
                                        <td>{{$item->DOC_ENTREGADA_A_FAC}}</td>
                                        <td>{{$item->DOC_ENTREGADO}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="col-lg-6">
                            <table class="table table-bordered table-striped table-condensed table-hover tablegeneral" width="100%">
                                <thead>
                                <tr>
                                    <th>Fecha de trabajo</th>
                                    <th>Estatus del embarque</th>
                                    <th>Total de embarques</th>
                                    <th>Total picking</th>
                                    <th>Total pallets</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($totalPallets as $item)
                                    <tr>
                                        <th>{{$item->workday}}</th>
                                        <th>{{$item->statusname}}</th>
                                        <th>{{$item->totalembarques}}</th>
                                        <th>{{$item->picking}}</th>
                                        <th>{{$item->totalcontainers}}</th>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
                <div class="card-footer">
                    Control de patios.
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    @include('Shipments.MonitorShipments.MonitorOperation.table')
    <script>
        function reloadtable() {
            $('#table-monitortrafico').DataTable().ajax.reload();
        }
    </script>

@endsection

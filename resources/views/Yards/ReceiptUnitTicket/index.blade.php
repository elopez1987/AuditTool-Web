@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Modulo de pesos de unidad
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-md-12">
                                @can('receiptunitticket.create')
                                    <a href="{{route('receiptunitticket.create')}}">
                                        @include('buttons.add')
                                    </a>
                                @endcan
                                <a>
                                    @include('buttons.reloadajax')
                                </a>
                                <a href="{{route('home')}}">
                                    @include('buttons.cancel')
                                </a>
                            </div>
                        </div>
                        <hr class="m-t-1">
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
                        <div class="col-12">
                            <table id="table-tickets"
                                   class="table table-bordered  table-condensed table-hover table-uppercase">
                                <thead>
                                <tr>
                                    <th class="text-white">Bodega</th>
                                    <th class="text-white">Folio</th>
                                    <th class="text-white">Ticket de báscula</th>
                                    <th class="text-white">Fecha de entrada</th>
                                    <th class="text-white">Linea de transporte</th>
                                    <th class="text-white">Placa de caja</th>
                                    <th class="text-white">Nombre del operador</th>
                                    <th class="text-white">Peso de entrada KG</th>
                                    <th class="text-white">Peso de salida KG</th>
                                    <th class="text-white">Peso Bruto</th>
                                    <th class="text-white">Kg Neto</th>
                                    <th class="text-white">Embalaje</th>
                                    <th class="text-white">Peso de material</th>
                                    <th class="text-white">Diferencia Kg</th>
                                    <th class="text-white">% de diferencia</th>
                                    <th class="text-white">Estatus</th>
                                    <th class="text-white">Opciones</th>
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
    @include('Yards.ReceiptUnitTicket.partials.table')
    <script>
        $("#carrierid,#warehouseid").change(function () {
            reloadtable();
        });
    </script>

@endsection

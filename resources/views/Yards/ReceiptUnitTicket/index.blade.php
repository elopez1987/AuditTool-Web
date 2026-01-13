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

    @if(Auth::user()->access->warehouse->auxiliary01 == 'PT' )
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <table id="table-ticketspt"
                                       class="table table-bordered  table-condensed table-hover table-uppercase">
                                    <thead>
                                    <tr>
                                        <th style="background-color: #0b0b0b !important;color: white !important;text-transform: uppercase">
                                            Folio
                                        </th>
                                        <th style="background-color: #0b0b0b !important;color: white !important;text-transform: uppercase">
                                            Número de ticket
                                        </th>
                                        <th style="background-color: #0b0b0b !important;color: white !important;text-transform: uppercase">
                                            Peso de entrada kilogramos
                                        </th>
                                        <th style="background-color: #0b0b0b !important;color: white !important;text-transform: uppercase">
                                            Peso de entrada libras
                                        </th>
                                        <th style="background-color: #0b0b0b !important;color: white !important;text-transform: uppercase">
                                            Peso de salida kilogramos
                                        </th>
                                        <th style="background-color: #0b0b0b !important;color: white !important;text-transform: uppercase">
                                            Peso de salida libras
                                        </th>
                                        <th style="background-color: #2196f3 !important;color: white !important;text-transform: uppercase">
                                            Peso de material kilogramos
                                        </th>
                                        <th style="background-color: #66bb6a !important;color: white !important;text-transform: uppercase">
                                            Peso de material libras
                                        </th>
                                        <th style="background-color: #673ab7 !important;color: white !important;text-transform: uppercase">
                                            Peso de SAP libras
                                        </th>
                                        <th style="background-color: #e53935 !important;color: white !important;text-transform: uppercase">
                                            % de diferencia
                                        </th>
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
    @endif

    @if(Auth::user()->access->warehouse->auxiliary01 == 'MP' )
        <div class="row mt-3">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <table width="100%" id="table-mp" class="table table-condensed table-bordered  table-hover">
                            <thead>
                            <tr>
                                <th style="background-color: #0b0b0b !important;color: white !important;text-transform: uppercase">
                                    Folio
                                </th>
                                <th style="background-color: #0b0b0b !important;color: white !important;text-transform: uppercase">
                                    Número de ticket
                                </th>
                                <th style="background-color: #0b0b0b !important;color: white !important;text-transform: uppercase">
                                    KG ENTRADA
                                </th>
                                <th style="background-color: #0b0b0b !important;color: white !important;text-transform: uppercase">
                                    KG SALIDA
                                </th>
                                <th style="background-color: #2196f3 !important;color: white !important;text-transform: uppercase">
                                    KG MATERIAL BRUTO
                                </th>
                                <th style="background-color: #673ab7 !important;color: white !important;text-transform: uppercase">
                                    KG NETO
                                </th>
                                <th style="background-color: #673ab7 !important;color: white !important;text-transform: uppercase">
                                    EMBALAJE
                                </th>
                                <th style="background-color: #2196f3 !important;color: white !important;text-transform: uppercase">
                                    TOTAL
                                </th>
                                <th style="background-color: #e53935 !important;color: white !important;text-transform: uppercase">
                                    DIF. KILOGRAMOS
                                </th>
                                <th style="background-color: #00e676 !important;color: black !important;text-transform: uppercase">
                                    ESTATUS
                                </th>
                                <th style="background-color: #00e676 !important;color: black !important;text-transform: uppercase">
                                    % VAR
                                </th>
                                <th>OPCIONES</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif

@endsection

@section('css')

@endsection


@section('script')
    @if(Auth::user()->access->warehouse->auxiliary01 == 'MP' )
        @include('Yards.ReceiptUnitTicket.partials.table-mp')
    @endif
    @if(Auth::user()->access->warehouse->auxiliary01 == 'PT' )
        @include('Yards.ReceiptUnitTicket.partials.table')
    @endif

    <script>
        $("#carrierid,#warehouseid").change(function () {
            reloadtable();
        });
    </script>

@endsection

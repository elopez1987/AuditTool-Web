@extends('layouts.app')

@section('css')
    <style>
        .status-cards-row .status-card {
            position: relative;
            overflow: hidden;
            height: 100%;
            text-align: center;
            padding: 24px 14px 20px;
            background-color: #ffffff;
            background-image: radial-gradient(#dbe7ef 1px, transparent 1.4px);
            background-size: 14px 14px;
            border: 1px solid #e6eef4;
            border-radius: 14px;
            box-shadow: 0 4px 14px rgba(41, 171, 226, 0.10);
            transition: transform .2s ease, box-shadow .2s ease;
        }
        .status-cards-row .status-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(41, 171, 226, 0.22);
        }
        .status-cards-row .status-card__top {
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 88%;
            height: 8px;
            /*background: linear-gradient(90deg, #29abe2, #1b8dbf);*/
            background: linear-gradient(90deg, #D0DF00, #B5BD00);
            border-radius: 0 0 8px 8px;
        }
        .status-cards-row .status-card__bottom {
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            height: 10px;
            background: linear-gradient(90deg, #D0DF00, #B5BD00);
        }
        .status-cards-row .status-card__body {
            position: relative;
            z-index: 1;
        }
        .status-cards-row .status-card__total {
            width: 62px;
            height: 62px;
            margin: 6px auto 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: linear-gradient(90deg, #D0DF00, #B5BD00);
            color: #ffffff;
            font-size: 1.5rem;
            font-weight: 700;
            line-height: 1;
            box-shadow: 0 6px 14px rgba(41, 171, 226, 0.35);
        }
        .status-cards-row .status-card__label {
            display: block;
            margin-top: 12px;
            font-size: .78rem;
            font-weight: 600;
            letter-spacing: .4px;
            text-transform: uppercase;
            color: #5a6b78;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Monitor de operaciones de embarques.
                </div>
                <div class="card-body">
                    <div class="row status-cards-row">
                        @foreach($statusQuantitys as $status)
                            <div class="col-lg-1 col-md-1 col-sm-3 mb-3">
                                <div class="status-card">
                                    <span class="status-card__top"></span>
                                    <div class="status-card__body">
                                        <div class="status-card__total">{{$status->total}}</div>
                                        <span class="status-card__label">{{$status->status->name}}</span>
                                    </div>
                                    <span class="status-card__bottom"></span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <hr class="mt-3 mb-3">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header">Estatus de embarques</div>
                                <div class="card-body">
                                    <div class="row">
                                        <form method="GET" id="search-monitortrafico" role="form">
                                            {{csrf_field()}}
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <a>
                                                        @include('buttons.reloadajax')
                                                    </a>
                                                    <a href="{{route('menushipment')}}">
                                                        @include('buttons.cancel')
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <x-text-input
                                                        name="shipmentid"
                                                        label="Número de embarque"
                                                />
                                                <x-select2
                                                        name="carrierid"
                                                        label="Linea de transporte"
                                                        :options="$carriers"
                                                />
                                                <x-select2
                                                        name="customerid"
                                                        label="Cliente"
                                                        :options="$customers"
                                                />
                                            </div>
                                        </form>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <table id="table-monitortrafico"
                                                   class="table table-bordered  table-condensed table-hover"
                                                   width="100%">
                                                <thead>
                                                <tr>
                                                    <th>Embarque</th>
                                                    <th>Cliente</th>
                                                    <th>Ciudad</th>
                                                    <th>Linea de transporte</th>
                                                    <th>Estatus Almacén</th>
                                                    <th>Estatus de unidad</th>
                                                    <th>Comentarios</th>
                                                    <th>Ingreso de Unidad</th>
                                                    <th>Confirmación de Llaves</th>
                                                    <th>Estadia Total</th>
                                                    <th>Cita de entrega</th>
                                                    <th>Maxima de Salida</th>
                                                </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    Tiempo de estadia de embarque con unidades
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <form method="GET" id="search-monitor-status" role="form">
                                            {{csrf_field()}}
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md" onclick="reloadtablestatus();">
                                                        <img src="{{asset('/ico/reloadpage.png')}}" alt="" class="img-16 ml-1">
                                                        <span class="text-button"> Refrescar</span>
                                                    </button>
                                                    <a href="{{route('menushipment')}}">
                                                        @include('buttons.cancel')
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <x-text-input
                                                        name="shipmentid-status"
                                                        label="Número de embarque"
                                                />
                                                <x-select2
                                                        name="carrierid-status"
                                                        label="Linea de transporte"
                                                        :options="$carriers"
                                                />
                                                <x-select2
                                                        name="customerid-status"
                                                        label="Cliente"
                                                        :options="$customers"
                                                />
                                            </div>
                                        </form>
                                    </div>
                                    <table id="table-monitor-status" class="table table-bordered table-condensed table-hover tablegeneral" width="100%">
                                        <thead>
                                        <tr>
                                            <th>Embarque</th>
                                            <th>Cliente</th>
                                            <th>Estatus</th>
                                            <th>Estadia</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="mt-3 mb-3">
                </div>
                <div class="card-footer">
                    Control de patios.
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @include('Shipments.MonitorShipments.MonitorOperation.MonDeOper.table')
    @include('Shipments.MonitorShipments.MonitorOperation.MonDeOper.table-status')

    <script>
        $("#carrierid,#customerid").change(function () {
            reloadtable();
        });
        $("#carrierid-status,#customerid-status").change(function () {
            reloadtablestatus();
        });

        function reloadtable() {
            $('#table-monitortrafico').DataTable().ajax.reload();
        }
        function reloadtablestatus() {
            $('#table-monitor-status').DataTable().ajax.reload();
        }
    </script>

@endsection

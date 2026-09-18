@extends('layouts.app')

@section('css')
    <style>
        .status-cards-row {
            row-gap: .5rem;
        }
        .status-card {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: #fff;
            border: 1px solid #e8e8e8;
            border-radius: 10px;
            padding: .85rem .5rem;
            height: 100%;
            cursor: pointer;
            transition: box-shadow .15s ease, transform .15s ease, border-color .15s ease;
        }
        .status-card:hover {
            box-shadow: 0 2px 10px rgba(0,0,0,.1);
            transform: translateY(-2px);
        }
        .status-card.is-active {
            border-color: var(--status-card-color, #556ee6);
            box-shadow: 0 0 0 2px var(--status-card-color, #556ee6) inset;
        }
        .status-card__top,
        .status-card__bottom {
            display: block;
            width: 90%;
            height: 4px;
            border-radius: 4px;
            background-color: var(--status-card-color, #b0bec5);
        }
        .status-card__top {
            margin-bottom: .5rem;
        }
        .status-card__bottom {
            margin-top: .5rem;
        }
        .status-card__body {
            text-align: center;
        }
        .status-card__total {
            font-size: 1.6rem;
            font-weight: 700;
            line-height: 1;
            color: #2B2F33;
        }
        .status-card__label {
            display: block;
            margin-top: .25rem;
            font-size: .68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .03em;
            color: #5A5A5A;
        }
        .status-card__clear {
            font-size: .75rem;
            text-decoration: underline;
            cursor: pointer;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Monitor de operaciones de embarques.</span>
                    <span class="status-card__clear" id="clear-status-filter" style="display:none;">Quitar filtro de estatus</span>
                </div>
                <div class="card-body">
                    <div class="row status-cards-row" id="monitor-operation-status-cards"></div>
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
                                                   class="table table-bordered  table-condensed table-hover">
                                                <thead>
                                                <tr>
                                                    <th>Embarque</th>
                                                    <th>Cliente</th>
                                                    <th>Ciudad</th>
                                                    <th>Línea de transporte</th>
                                                    <th>Estatus de embarque</th>
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
                                            <th>Línea de transporte</th>
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
    <script>

        var monitorOperationHenkelStatusId = '';
    </script>
    @include('Shipments.MonitorShipments.MonitorOperation.MonDeOper.table')
    @include('Shipments.MonitorShipments.MonitorOperation.MonDeOper.table-status')

    <script>
        function reloadtable() {
            $('#table-monitortrafico').DataTable().ajax.reload();
        }
        function reloadtablestatus() {
            $('#table-monitor-status').DataTable().ajax.reload();
        }

        function renderMonitorOperationHenkelStatusCards(items) {
            let $container = $('#monitor-operation-status-cards');
            $container.empty();
            $.each(items, function (index, item) {
                let statusid = String(item.statusid);
                let isActive = statusid === String(monitorOperationHenkelStatusId);
                $container.append(
                    $('<div class="col-lg-1 col-md-2 col-sm-3 mb-3"></div>').append(
                        $('<div class="status-card"></div>')
                            .toggleClass('is-active', isActive)
                            .css('--status-card-color', item.color || '#b0bec5')
                            .append('<span class="status-card__top"></span>')
                            .append(
                                $('<div class="status-card__body"></div>')
                                    .append($('<div class="status-card__total"></div>').text(item.total))
                                    .append($('<span class="status-card__label"></span>').text(item.name))
                            )
                            .append('<span class="status-card__bottom"></span>')
                            .on('click', function () {
                                monitorOperationHenkelStatusId = (monitorOperationHenkelStatusId === statusid) ? '' : statusid;
                                loadMonitorOperationHenkelKpis();
                                reloadtable();
                                reloadtablestatus();
                            })
                    )
                );
            });
            $('#clear-status-filter').toggle(monitorOperationHenkelStatusId !== '');
        }

        function loadMonitorOperationHenkelKpis() {
            $.ajax({
                url: '{{ route("getMonitorOperationHenkelKpis") }}',
                success: function (r) {
                    renderMonitorOperationHenkelStatusCards(r);
                }
            });
        }

        $('#clear-status-filter').on('click', function () {
            monitorOperationHenkelStatusId = '';
            loadMonitorOperationHenkelKpis();
            reloadtable();
            reloadtablestatus();
        });

        $("#carrierid,#customerid").change(function () {
            reloadtable();
        });
        $("#carrierid-status,#customerid-status").change(function () {
            reloadtablestatus();
        });

        /* Recarga automatica al salir del campo de embarque, sin esperar a
           que se presione "Buscar", igual que en otros monitores. */
        $("#shipmentid").on('blur', function () {
            reloadtable();
        });
        $("#shipmentid-status").on('blur', function () {
            reloadtablestatus();
        });

        $(document).ready(function () {
            loadMonitorOperationHenkelKpis();
            setInterval(function () {
                reloadtable();
                reloadtablestatus();
                loadMonitorOperationHenkelKpis();
            }, 1000 * 60 * 5);
        });
    </script>

@endsection

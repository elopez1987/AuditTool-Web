@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Validación de inventario - {{ $inventoryhead->name }}
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        <div class="row">
                            <div class="col-md-12">
                                <a href="">
                                    @include('buttons.search')
                                </a>
                                <a>
                                    @include('buttons.reloadajax')
                                </a>
                                <a href="{{ url('inventoryheads/' . $inventoryhead->inventory_uuid) }}">
                                    @include('buttons.cancel')
                                </a>
                            </div>
                        </div>
                        <hr class="m-t-3">
                        <div class="row">
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="partnumber">Número de parte / Material</label>
                                    <input type="text"
                                           class="form-control"
                                           id="partnumber"
                                           name="partnumber"
                                           value="{{ old('partnumber') }}"
                                           placeholder="Buscar por SKU o descripción"
                                           autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-2 col-md-6 col-sm-12">
            <div class="card border-info">
                <div class="card-header bg-grey">
                    <h5 class="m-b-0 text-black-50">Total físico</h5>
                </div>
                <div class="card-body">
                    <h2 class="text-red" id="totalFisico" data-id="{{ $inventoryhead->id }}">-</h2>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-6 col-sm-12">
            <div class="card border-info">
                <div class="card-header bg-grey">
                    <h5 class="m-b-0 text-black-50">Total SAP</h5>
                </div>
                <div class="card-body">
                    <h2 class="text-red" id="totalSap" data-id="{{ $inventoryhead->id }}">-</h2>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-6 col-sm-12">
            <div class="card border-info">
                <div class="card-header bg-grey">
                    <h5 class="m-b-0 text-black-50">Total WMS</h5>
                </div>
                <div class="card-body">
                    <h2 class="text-red" id="totalWms" data-id="{{ $inventoryhead->id }}">-</h2>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="card border-danger">
                <div class="card-header bg-grey">
                    <h5 class="m-b-0 text-black-50">Errores físico vs SAP</h5>
                </div>
                <div class="card-body">
                    <h2 class="text-danger" id="errorsFisicoVsSap" data-id="{{ $inventoryhead->id }}">-</h2>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="card border-danger">
                <div class="card-header bg-grey">
                    <h5 class="m-b-0 text-black-50">Errores físico vs WMS</h5>
                </div>
                <div class="card-body">
                    <h2 class="text-danger" id="errorsFisicoVsWms" data-id="{{ $inventoryhead->id }}">-</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    CONCILIACIÓN FÍSICO vs SAP vs WMS
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-condensed table-hover"
                           id="inventoryvalidation"
                           width="100%">
                        <thead>
                        <tr>
                            <th>Sku</th>
                            <th>Descripción</th>
                            <th>Total de conteo</th>
                            <th>Total transitorias</th>
                            <th>Total Fisico</th>
                            <th>Total Wms</th>
                            <th>Total Sap</th>
                            <th style="background-color: #76ff03 !important;">Estatus fisico vs Sap</th>
                            <th style="background-color: #76ff03 !important;">Diferencia fisico vs Sap</th>
                            <th style="background-color: #40c4ff !important;">Estatus fisico vs Wms</th>
                            <th style="background-color: #40c4ff !important;">Diferencia fisico vs Wms</th>
                        </tr>
                        </thead>
                    </table>
                </div>
                <div class="card-footer"></div>
            </div>
        </div>
    </div>

    @include('layouts.Partials.Errors')
@endsection

@section('script')
    @include('Inventory.InventoryHead.ValidationStock.table-locations-errors')
    <script>
        function formatKpiNumber(value) {
            var number = Number(value || 0);
            if (Number.isNaN(number)) {
                return value;
            }
            return number.toLocaleString('es-MX');
        }

        function loadValidationSapKpis() {
            $.get('{{ route("getDataValidationSapKpis", $inventoryhead->id) }}')
                .done(function (data) {
                    $('#totalFisico').text(formatKpiNumber(data.totalFisico));
                    $('#totalSap').text(formatKpiNumber(data.totalSap));
                    $('#totalWms').text(formatKpiNumber(data.totalWms));
                    $('#errorsFisicoVsSap').text(formatKpiNumber(data.errorsFisicoVsSap));
                    $('#errorsFisicoVsWms').text(formatKpiNumber(data.errorsFisicoVsWms));
                })
                .fail(function () {
                    $('#totalFisico, #totalSap, #totalWms, #errorsFisicoVsSap, #errorsFisicoVsWms').text('-');
                });
        }

        function reloadtable() {
            $('#inventoryvalidation').DataTable().ajax.reload();
            loadValidationSapKpis();
        }

        $(document).ready(function () {
            loadValidationSapKpis();
        });
    </script>
@endsection

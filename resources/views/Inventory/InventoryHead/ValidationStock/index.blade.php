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

                    <div class="row mt-3">
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
                                            <th>Total fisico</th>
                                            <th>Total Wms</th>
                                            <th>Total Sap</th>
                                            <th>Estatus fisico vs Sap</th>
                                            <th>Estatus Sap vs Wms</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                                <div class="card-footer"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.Partials.Errors')
@endsection

@section('script')
    @include('Inventory.InventoryHead.ValidationStock.table-locations-errors')
    <script>
        function reloadtable() {
            $('#inventoryvalidation').DataTable().ajax.reload();
        }
    </script>
@endsection

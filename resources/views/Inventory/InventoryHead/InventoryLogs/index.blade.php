@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Logs de conteo - {{ $inventoryHead->name }}
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
                                <a href="{{ url('inventoryheads/' . $inventoryHead->inventory_uuid) }}">
                                    @include('buttons.cancel')
                                </a>
                            </div>
                        </div>
                        <hr class="m-t-3">
                        <div class="row">
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="inventory_card_id">Cédula (ID)</label>
                                    <input type="text"
                                           class="form-control"
                                           id="inventory_card_id"
                                           name="inventory_card_id"
                                           value="{{ old('inventory_card_id') }}"
                                           placeholder="inventory_card_id"
                                           autocomplete="off">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="location_id">Ubicación</label>
                                    <input type="text"
                                           class="form-control"
                                           id="location_id"
                                           name="location_id"
                                           value="{{ old('location_id') }}"
                                           placeholder="location_id"
                                           autocomplete="off">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="partnumber_name">Número de parte</label>
                                    <input type="text"
                                           class="form-control"
                                           id="partnumber_name"
                                           name="partnumber_name"
                                           value="{{ old('partnumber_name') }}"
                                           placeholder="partnumber_name"
                                           autocomplete="off">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="status">Estatus</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="">Todos</option>
                                        @foreach($statusOptions as $option)
                                            <option value="{{ $option['value'] }}"
                                                {{ old('status') === $option['value'] ? 'selected' : '' }}>
                                                {{ $option['label'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row" id="cards-totales">
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="card border-info">
                <div class="card-header bg-grey">
                    <h5 class="m-b-0 text-black-50">Total logs</h5>
                </div>
                <div class="card-body">
                    <h2 class="text-info" id="totalLogs" data-id="{{ $inventoryHead->id }}">-</h2>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="card border-info">
                <div class="card-header bg-grey">
                    <h5 class="m-b-0 text-black-50">RECIBIDO</h5>
                </div>
                <div class="card-body">
                    <h2 class="text-info" id="totalRecibido" data-id="{{ $inventoryHead->id }}">-</h2>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="card border-info">
                <div class="card-header bg-grey">
                    <h5 class="m-b-0 text-black-50">TERMINADO</h5>
                </div>
                <div class="card-body">
                    <h2 class="text-green" id="totalTerminado" data-id="{{ $inventoryHead->id }}">-</h2>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="card border-danger">
                <div class="card-header bg-grey">
                    <h5 class="m-b-0 text-black-50">ERROR</h5>
                </div>
                <div class="card-body">
                    <h2 class="text-danger" id="totalError" data-id="{{ $inventoryHead->id }}">-</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    DETALLE DE LOGS DE CONTEO
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-condensed table-hover"
                           id="table-inventory-logs"
                           width="100%">
                        <thead>
                        <tr>
                            <th>Transacción</th>
                            <th>Cédula ID</th>
                            <th>Ubicación</th>
                            <th>Número de parte</th>
                            <th>Conteo</th>
                            <th>Cantidad</th>
                            <th>Usuario</th>
                            <th>Servicio</th>
                            <th>Estatus</th>
                            <th>HTTP</th>
                            <th>Mensaje</th>
                            <th>Recibido</th>
                            <th>Procesado</th>
                            <th>Opciones</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @include('layouts.Partials.Errors')
@endsection

@section('script')
    @include('Inventory.InventoryHead.InventoryLogs.partials.table')
    <script>
        function formatKpiNumber(value) {
            var number = Number(value || 0);
            if (Number.isNaN(number)) {
                return value;
            }
            return number.toLocaleString('es-MX');
        }

        function loadInventoryLogKpis() {
            $.get('{{ route("getInventoryLogKpis", $inventoryHead->id) }}', {
                inventory_card_id: $('input[name=inventory_card_id]').val(),
                location_id: $('input[name=location_id]').val(),
                partnumber_name: $('input[name=partnumber_name]').val(),
                status: $('select[name=status]').val()
            })
                .done(function (data) {
                    $('#totalLogs').text(formatKpiNumber(data.totalLogs));
                    $('#totalRecibido').text(formatKpiNumber(data.totalRecibido));
                    $('#totalTerminado').text(formatKpiNumber(data.totalTerminado));
                    $('#totalError').text(formatKpiNumber(data.totalError));
                })
                .fail(function () {
                    $('#totalLogs, #totalRecibido, #totalTerminado, #totalError').text('-');
                });
        }

        function reloadtable() {
            $('#table-inventory-logs').DataTable().ajax.reload();
            loadInventoryLogKpis();
        }

        $(document).ready(function () {
            loadInventoryLogKpis();
        });
    </script>
@endsection

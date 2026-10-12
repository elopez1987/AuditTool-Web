@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Importar Existencia SAP
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ url('import-inventory-sap-excel') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <a>
                                    @include('buttons.save')
                                </a>
                                <a href="{{ url('inventoryheads/' . $inventoryHead->inventory_uuid) }}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="mt-3">
                        <div class="row">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="inventoryid">Inventario </label>
                                    <input type="text" class="form-control" required id="inventoryid" name="inventoryid"
                                           value="{!! $inventoryHead->id !!}" readonly>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="name">Nombre </label>
                                    <input type="text" class="form-control" required id="name" name="name" disabled
                                           value="{{ $inventoryHead->name }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-4">
                                <input type="file" name="file" id="file" class="form-control">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-footer">
                    @can('clear-stock-sap')
                        <form id="form-clear-stock-sap"
                              method="POST"
                              action="{{ route('clear-stock-sap', $inventoryHead->id) }}"
                              class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    id="clear-stock-sap"
                                    class="btn waves-effect waves-light btn-outline-danger btn-md"
                                    onclick="return confirm('¿Eliminar toda la existencia SAP importada de este inventario?');">
                                <img src="{{ asset('/ico/destroy1.png') }}" alt="" class="img-16 ml-1">
                                <span class="text-button"> Limpiar existencia SAP</span>
                            </button>
                        </form>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="card border-info">
                <div class="card-header bg-grey">
                    <h5 class="m-b-0 text-black-50">Números de parte</h5>
                </div>
                <div class="card-body">
                    <h2 class="text-red" id="totalPartNumbers" data-id="{{ $inventoryHead->id }}"></h2>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card border-info">
                <div class="card-header bg-grey">
                    <h5 class="m-b-0 text-black-50">Cantidad</h5>
                </div>
                <div class="card-body">
                    <h2 class="text-red" id="totalQuantitySap" data-id="{{ $inventoryHead->id }}"></h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Detalle de la existencia
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <a href="">
                                    @include('buttons.search')
                                </a>
                                <a>
                                    @include('buttons.reloadajax')
                                </a>
                            </div>
                        </div>
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
                        <div class="col-lg-8">
                            <table class="table table-bordered table-condensed table-hover"
                                   id="inventoryStockSapTable"
                                   width="100%">
                                <thead>
                                <tr>
                                    <th>Número de parte</th>
                                    <th>Descripción</th>
                                    <th>Cantidad</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="button"
                            id="export-sap-stock"
                            class="btn waves-effect waves-light btn-outline-info btn-md">
                        <img src="{{ asset('/ico/excel.png') }}" alt="" class="img-16 ml-2 mr-2">
                        Exportar Todo
                    </button>
                </div>
            </div>
        </div>
    </div>

    @include('layouts.Partials.Errors')
@endsection

@section('script')
    <script>
        function loadInventoryStockSapKpis() {
            $.get('{{ route("getInventoryStockSapKpis", $inventoryHead->id) }}', {
                partnumber: $('input[name=partnumber]').val()
            })
                .done(function (data) {
                    $('#totalPartNumbers').text(data.totalPartNumbers);
                    $('#totalQuantitySap').text(data.totalQuantity);
                })
                .fail(function () {
                    $('#totalPartNumbers, #totalQuantitySap').text('-');
                });
        }

        var oTable = $("#inventoryStockSapTable").DataTable({
            "paging": true,
            "searching": false,
            "ordering": true,
            "sScrollY": "450px",
            "sScrollX": true,
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "colReorder": true,
            "stateSave": true,
            "stateDuration": 0,
            "ajax": {
                url: '{{ route("getInventoryStockSap", $inventoryHead->id) }}',
                data: function (d) {
                    d.partnumber = $('input[name=partnumber]').val();
                }
            },
            "columns": [
                {data: 'inventorypartnumber.prtnum', name: 'inventorypartnumber.prtnum', defaultContent: ''},
                {data: 'inventorypartnumber.lngdsc', name: 'inventorypartnumber.lngdsc', defaultContent: ''},
                {data: 'quantity', name: 'quantity', defaultContent: ''},
            ],
            "dom": '<"row mb-3"<"col-sm-4"><"col-sm-4 d-flex justify-content-center"l><"col-sm-4 d-flex justify-content-end"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
            buttons: [],
            language: {
                "decimal": "",
                "emptyTable": "No hay información",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
                "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                "infoPostFix": "",
                "thousands": ",",
                "lengthMenu": "Mostrar _MENU_ Entradas",
                "loadingRecords": "Cargando...",
                "processing": "Procesando...",
                "search": "Buscar:",
                "zeroRecords": "Sin resultados encontrados",
                "paginate": {
                    "first": "Primero",
                    "last": "Ultimo",
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            },
        });

        function reloadtable() {
            oTable.ajax.reload(null, false);
            loadInventoryStockSapKpis();
        }

        function exportStockSapAll() {
            var $btn = $('#export-sap-stock');
            $btn.prop('disabled', true);

            var partnumber = $('input[name=partnumber]').val() || '';
            var url = '{{ route("export-sap-stock", $inventoryHead->id) }}'
                + '?partnumber=' + encodeURIComponent(partnumber);

            window.location.href = url;

            setTimeout(function () {
                $btn.prop('disabled', false);
            }, 3000);
        }

        $('#search').on('submit', function (e) {
            e.preventDefault();
            reloadtable();
        });

        $(document).ready(function () {
            loadInventoryStockSapKpis();

            $('#export-sap-stock').on('click', function (e) {
                e.preventDefault();
                exportStockSapAll();
            });
        });
    </script>
@endsection

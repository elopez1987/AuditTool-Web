@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Importar Inventario Wms
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('import-inventory-sap-excel')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <a>
                                    @include('buttons.save')
                                </a>
                                <a href="{{url('inventoryheads/' . $inventoryHead->inventory_uuid)}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="mt-3">
                        <div class="row">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="name">Inventario </label>
                                    <input type="text" class="form-control" required id="inventoryid" name="inventoryid" value="{!! $inventoryHead->id !!}" readonly>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="name">Nombre </label>
                                    <input type="text" class="form-control" required id="name" name="name" disabled
                                           value="{{$inventoryHead->name}}">
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
                    <div class="row">
                        <div class="col-lg-6">
                            <table class="table table-bordered table-condensed table-hover" id="inventoryStockSapTable" width="100%">
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
            </div>
        </div>
    </div>


    @include('layouts.Partials.Errors')
@endsection

@section('script')
    <script>
        function loadInventoryStockSapKpis() {
            $.get('{{ route("getInventoryStockSapKpis", $inventoryHead->id) }}')
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
                    d.name = $('input[name=name]').val();
                }
            },
            "columns": [
                {data: 'inventorypartnumber.prtnum',name: 'inventorypartnumber.prtnum'},
                {data: 'inventorypartnumber.lngdsc',name: 'inventorypartnumber.lngdsc'},
                {data: 'quantity',name: 'quantity'},
                // {data: 'btn'},
            ],
            "dom": '<"row mb-3"<"col-sm-4"B><"col-sm-4 d-flex justify-content-center"l><"col-sm-4 d-flex justify-content-end"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],

            buttons: [
                {
                    "extend": "colvis",
                    "text": "<img src=\"{{asset('/pics/adbuscar.png')}}\"  alt=''><span  style='font-size: 11px;'> Mostar/Ocultar</span>",
                    "className": "btn btn-outline-info btn-md  btn-bold",
                    columns: ':not(:first):not(:last)'
                },
                {
                    "extend": "csv",
                    "text": "<img src=\"{{asset('/ico/csv.png')}}\"  alt='' class='img-16'><span  style='font-size: 11px;'> Csv</span>",
                    "className": "btn btn-outline-info btn-md  btn-bold",
                },
                {
                    "extend": "excel",
                    "text": "<img src=\"{{asset('/pics/adexportar.png')}}\"  alt=''><span  style='font-size: 11px;'> Excel</span>",
                    "className": "btn btn-outline-info btn-md  btn-bold",
                }
            ],

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
        $('#search').on('submit', function (e) {
            oTable.draw();
            e.preventDefault();
        });

        $(document).ready(function () {
            loadInventoryStockSapKpis();
        });
    </script>
@endsection


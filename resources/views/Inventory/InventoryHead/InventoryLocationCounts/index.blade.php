@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Inventario Fiscal - Ubicaciones
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
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
                                        <a href="{{url('inventoryheads/' . $inventoryHead->inventory_uuid)}}">
                                            @include('buttons.cancel')
                                        </a>
                                    </div>
                                </div>
                                <hr class="m-t-3">
                                <div class="row">
                                    <x-text-input
                                            name="nameinventory"
                                            label="Nombre del inventario"
                                            uppercase
                                            readonly
                                            value="{{$inventoryHead->name}}"
                                    />
                                    <x-text-input
                                            name="aislename"
                                            label="Nombre del pasillo"
                                            uppercase
                                    />
                                    <x-text-input
                                            name="cardname"
                                            label="Cédula"
                                            uppercase
                                    />
                                    <x-text-input
                                            name="locationname"
                                            label="Ubicación"
                                            uppercase
                                    />
                                    <x-text-input
                                            name="partnumberid"
                                            label="Número de parte"
                                            uppercase
                                    />
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <table width="100%" id="table-locations" class="table table-bordered  table-condensed table-hover">
                                <thead>
                                    <tr>
                                        <th>Pasillo</th>
                                        <th>Cédula</th>
                                        <th>Ubicación</th>
                                        <th>Sku 01</th>
                                        <th>Descripción</th>
                                        <th>Cantidad fisico</th>
                                        <th>Cantidad wms</th>
                                        <th>Unidad de medida</th>
                                        <th>Estatus de ubicación</th>
{{--                                        <th>Opciones</th>--}}
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

@section('script')
    <script>
        var oTable = $("#table-locations").DataTable({
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
                url: '{{ route("getLocationCountsInventory", $inventoryHead->id) }}',
                data: function (d) {
                    d.aislename = $('input[name=aislename]').val();
                    d.cardname = $('input[name=cardname]').val();
                    d.locationname = $('input[name=locationname]').val();
                    d.partnumberid = $('input[name=partnumberid]').val();
                }
            },
            "columns": [
                {data: 'aisle_name'},
                {data: 'card_name'},
                {data: 'location_name'},
                {data: 'part_number1'},
                {data: 'description01'},
                {data: 'total_piezas_fisico'},
                {data: 'total_piezas_wms'},
                {data: 'unitmeasureid'},
                {data: 'status_comparacion'},
            ],
            "dom": '<"row mb-3"<"col-sm-4"B><"col-sm-4 d-flex justify-content-center"l><"col-sm-4 d-flex justify-content-end"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',
            "lengthMenu": [[10, 25, 50, 100,200,300,400], [10, 25, 50,100,200,300,400]],

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
    </script>

    <script>
        function reloadtable() {
            $('#table-cards').DataTable().ajax.reload();
        }
    </script>
@endsection

@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Detalle de inventario - {{$inventory->id}} - {{$inventory->description}}
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-lg-12">
                                <a href="{{url('inventoryhead/' . $inventory->id)}}">
                                    <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md">
                                        <img src="{{asset('/ico/adback.png')}}" alt="" class="img-16 ml-1">
                                        <span style="font-size: 11px;"> Regresar</span>
                                    </button>
                                </a>
                                <a>
                                    <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md"
                                            onclick="reloadtable();">
                                        <img src="{{asset('/ico/reloadpage.png')}}" alt="" class="img-16 ml-1">
                                        <span style="font-size: 11px;"> Refrescar</span>
                                    </button>
                                </a>
                            </div>
                        </div>
                        <hr class="mb-2">
                        <div class="row">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="cardid">Cédula de conteo</label>
                                    <input type="text" class="form-control" id="cardid" name="cardid">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="aisleid">Pasillo de conteo</label>
                                    <input type="text" class="form-control" id="aisleid" name="aisleid">
                                </div>
                            </div>
                        </div>
                    </form>
                    <hr class="mb-2">
                    <div class="row">
                        <div class="col-lg-8">
                            <table id="table-adjustment" class="table table-condensed table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>Inventario</th>
                                    <th>Area</th>
                                    <th>Pasillo</th>
                                    <th>Cédula</th>
                                    <th>Cantidad de errores</th>
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
@endsection

@section('script')

    <script>
        $(document).ready(function () {
            let oTable = $("#table-adjustment").DataTable({
                "paging": true,
                "searching": true,
                "ordering": true,
                "sScrollY": "450px",
                // "sScrollX": true,
                "processing": true,
                "serverSide": true,
                "responsive": true,
                "colReorder": true,
                "stateSave": true,
                "stateDuration": 0,
                "ajax": {
                    url: '../getCardWithErrors',
                    data: function (d) {
                        d.inventoryid = {!! $inventory->id !!};
                        d.cardid = $('input[name=cardid]').val();
                        d.aisleid = $('input[name=aisleid]').val();
                    }
                },
                "columns": [
                    {data: 'inventoryid'},
                    {data: 'areaid'},
                    {data: 'aisleid'},
                    {data: 'cardid'},
                    {data: 'totalerrores'},
                    {data: 'btn'},
                ],
                "dom": '<"row mb-3"<"col-sm-4"B><"col-sm-4 d-flex justify-content-center"l><"col-sm-4 d-flex justify-content-end"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',
                "lengthMenu": [[1,5,10, 30, 50, 100, 150, 200, 250, -1], [1,5,10, 30, 50, 100, 150, 200, 250, "Todos"]],
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
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ Registros",
                    "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
                    "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "Mostrar _MENU_ Registros",
                    "loadingRecords": "Cargando...",
                    "processing": '<i class="fa fa-spinner fa-spin fa-4x fa-fw text-info"></i><span class="sr-only">Cargando ...</span> ',
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
        });
    </script>
    <script>
        function reloadtable() {
            $('#table-adjustment').DataTable().ajax.reload();
        }
    </script>
@endsection


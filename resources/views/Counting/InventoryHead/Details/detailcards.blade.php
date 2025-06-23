@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Detalle de pasillo.
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-lg-12">
                                @if($inventory->inventorytype == 'APP')
                                    <a href="{{url('inventoryhead/' . $inventory->id)}}">
                                        <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md">
                                            <img src="{{asset('/ico/adback.png')}}" alt="" class="img-16 ml-1">
                                            <span style="font-size: 11px;"> Regresar</span>
                                        </button>
                                    </a>
                                @else
                                    <a href="{{url('inventoryheaddron/' . $inventory->id)}}">
                                        <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md">
                                            <img src="{{asset('/ico/adback.png')}}" alt="" class="img-16 ml-1">
                                            <span style="font-size: 11px;"> Regresar</span>
                                        </button>
                                    </a>
                                @endif
                                <a>
                                    @include('buttons.reloadajax')
                                </a>
                            </div>
                        </div>
                        <hr class="mb-2">
                        <div class="row">
                            <div class="col-lg-2 col-md-4 col-sm-12" id="sello">
                                <div class="form-group">
                                    <label for="inventoryid">Número de inventario:</label>
                                    <input type="text" class="form-control" readonly id="inventoryid" name="inventoryid"
                                           value="{{$inventory->id}}">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-4 col-sm-12" id="sello">
                                <div class="form-group">
                                    <label for="description">Descripción:</label>
                                    <input type="text" class="form-control" readonly id="description" name="description"
                                           value="{{$inventory->description}}">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-4 col-sm-12" id="sello">
                                <div class="form-group">
                                    <label for="aisleid">Número de pasillo:</label>
                                    <input type="text" class="form-control" id="aisleid" name="aisleid">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-4 col-sm-12" id="sello">
                                <div class="form-group">
                                    <label for="cardid">Número de cédula:</label>
                                    <input type="text" class="form-control" id="cardid" name="cardid">
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-lg-12">
                            <table width="100%" id="table-cards"
                                   class="table table-bordered table-striped table-condensed">
                                <thead>
                                <tr>
                                    <th>Pasillo</th>
                                    <th>Cédula</th>
                                    <th>Ubicaciones</th>
                                    <th>F.Inicio conteo 1</th>
                                    <th>F.Final conteo 1</th>
                                    <th>Responsable conteo 1</th>
                                    <th>F.Inicio conteo 2</th>
                                    <th>F.Final conteo 2</th>
                                    <th>Responsable conteo 2</th>
                                    <th>Unidad de medida</th>
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
            let oTable = $("#table-cards").DataTable({
                "paging": true,
                "searching": true,
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
                    url: '../getDataCardsInventory',
                    data: function (d) {
                        d.inventoryid = $('input[name=inventoryid]').val();
                        d.aisleid = $('input[name=aisleid]').val();
                        d.cardid = $('input[name=cardid]').val();
                    }
                },
                "columns": [
                    // {data: 'recordid'},
                    {data: 'aisleid'},
                    {data: 'cardid'},
                    {data: 'locations'},
                    {data: 'startdate01'},
                    {data: 'finishdate01'},
                    {data: 'user01'},
                    {data: 'startdate02'},
                    {data: 'finishdate02'},
                    {data: 'user02'},
                    {data: 'unitmeasureid'},
                    {data: 'btn'},

                ],
                "columnDefs": [
                    {"width": "100px", "targets": 0},
                    {"width": "100px", "targets": 1},
                    {"width": "100px", "targets": 2},
                    {"width": "150px", "targets": 3},
                    {"width": "150px", "targets": 4},
                    {"width": "250px", "targets": 5},
                    {"width": "150px", "targets": 6},
                    {"width": "150px", "targets": 7},
                    {"width": "250px", "targets": 8},
                    {"width": "150px", "targets": 9},
                    {"width": "150px", "targets": 10},
                ],
                "dom": '<"row mb-3"<"col-sm-4"B><"col-sm-4 d-flex justify-content-center"l><"col-sm-4 d-flex justify-content-end"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',
                "lengthMenu": [[5, 10, 30, 50, 100, 150, 200, 250, -1], [5, 10, 30, 50, 100, 150, 200, 250, "All"]],
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
            $('#table-cards').DataTable().ajax.reload();
        }
    </script>
    <script>
        $("#aisleid").blur(function () {
            reloadtable();
            let aisleid = document.getElementById("aisleid").value;
            sessionStorage.setItem("aisleid", aisleid);
        });
    </script>
    <script>
        $(document).ready(function () {
            document.getElementById("aisleid").value = sessionStorage.getItem('aisleid');
            reloadtable();
        });
    </script>
@endsection


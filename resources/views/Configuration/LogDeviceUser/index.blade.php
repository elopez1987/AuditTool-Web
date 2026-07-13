@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Log de dispositivos por usuario.
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <form method="GET" id="search" role="form">
                                {{csrf_field()}}
                                <div class="row">
                                    <div class="col-md-12">
                                        <a>
                                          @include('buttons.search')
                                        </a>
                                        <a href="{{route('home')}}">
                                            @include('buttons.cancel')
                                        </a>
                                    </div>
                                </div>
                                <div class="card-input">
                                    <div class="row">
                                        <div class="col-lg-4 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="serialnumber">Número de serie</label>
                                                <input type="text" class="form-control" id="serialnumber"
                                                       name="serialnumber"
                                                       aria-describedby="name">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <hr class="mt-4">
                    <div class="row">
                        <div class="col-lg-6">
                            <table width="100%" id="table-devices" class="table table-condensed table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th>Número de serie</th>
                                        <th>Usurio</th>
                                        <th>Fecha de registro</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-center">
                    Kardex de inicios.
                </div>
            </div>
        </div>
    </div>

@endsection

@section('css')
@endsection



@section('script')
    <script>
        $(document).ready(function () {
            let oTable = $("#table-devices").DataTable({
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
                    url: 'getDevicesForUser',
                    data: function (d) {
                        d.serialnumber = $('input[name=serialnumber]').val();
                    }
                },
                "columns": [
                    {data: 'serialnumber'},
                    {data: 'user',},
                    {data: 'created_at'},
                ],
                "columnDefs": [
                    {"width": "200px"},
                    {"width": "200px"},
                    {"width": "200px"},
                ],

                "dom": '<"row mb-3"<"col-sm-4"B><"col-sm-4 d-flex justify-content-center"l><"col-sm-4 d-flex justify-content-end"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',
                "lengthMenu": [[1,5,10, 30, 50, 100, -1], [1,5,10, 30, 50, 100, "Todos"]],
                buttons: [
                    {
                        "extend": "colvis",
                        "text": "<img src=\"{{asset('/pics/adbuscar.png')}}\"  alt='' class='img-16 ml-2 mr-2'><span> Mostar/Ocultar</span>",
                        "className": "btn btn-outline-info btn-md  btn-bold",
                        columns: ':not(:first):not(:last)'
                    },
                    {
                        "extend": "csv",
                        "text": "<img src=\"{{asset('/ico/csv.png')}}\"  alt='' class='img-16 ml-2 mr-2'><span> Csv</span>",
                        "className": "btn btn-outline-info btn-md  btn-bold",
                    },
                    {
                        "extend": "excel",
                        "text": "<img src=\"{{asset('/pics/adexportar.png')}}\"  alt='' class='img-16 ml-2 mr-2'><span> Excel</span>",
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
                    "processing": '<img src=\"{{asset('/img/loading-truck.gif')}}\" >',
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
            //
            // $('#table-devices').on('blur', function () {
            //     $('#table-devices tfoot input').val(''); // Limpia el footer
            //     oTable.draw(); // Redibuja la tabla
            // });
        });
    </script>


@endsection

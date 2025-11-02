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
                                            <button id="searchData" type="button"
                                                    class="btn waves-effect waves-light btn-outline-info btn-md">
                                                <img src="{{asset('/ico/reloadpage.png')}}" alt="" class="img-16 ml-1">
                                                <span style="font-size: 11px;"> Buscar</span>
                                            </button>
                                        </a>
                                        <a>
                                            <button id="clearinput" type="button"
                                                    class="btn waves-effect waves-light btn-outline-info btn-md">
                                                <img src="{{asset('/ico/closemodal.png')}}" alt="" class="img-16 ml-1">
                                                <span style="font-size: 11px;"> Limpiar filtros</span>
                                            </button>
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
                    <div class="row">
                        <div class="col-lg-6">
                            <table width="100%" id="table-devices"
                                   class="table table-condensed table-hover table-bordered">
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
    <link rel="stylesheet" href="{{asset('/theme/assets/libs/sweetalert2/dist/sweetalert2.min.css')}}">
@endsection



@section('script')
    <script src="{{asset('/theme/assets/libs/sweetalert2/dist/sweetalert2.all.min.js')}}"></script>
    <script>
        $('#searchData').on('click', function () {
            let serialnumber = $('#serialnumber').val();

            if (serialnumber != '') {

                $("#table-devices").dataTable().fnDestroy();
                let oTable = $("#table-devices").DataTable({
                    bDestroy: true,
                    "paging": true,
                    "searching": true,
                    "ordering": true,
                    "sScrollY": "350px",
                    "sScrollX": true,
                    "processing": true,
                    "serverSide": true,
                    "responsive": true,
                    "colReorder": true,
                    "stateSave": true,
                    "stateDuration": 0,
                    "ajax": {
                        url: 'getDevicesForUser',
                        dataType: 'json',
                        data: function (d) {
                            d.serialnumber = $('input[name=serialnumber]').val();
                        }
                    },

                    "columns": [
                        {data: 'serialnumber'},
                        {data: 'user'},
                        {data: 'created_at'},
                    ],
                    "columnDefs": [
                        {"width": "150px", "targets": 0},
                        {"width": "280px", "targets": 1},
                        {"width": "280px", "targets": 2},
                    ],
                    "dom": '<"row mb-3"<"col-sm-4"B><"col-sm-4 d-flex justify-content-center"l><"col-sm-4 d-flex justify-content-end"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',
                    "lengthMenu": [[1, 5, 10, 30, 50, 100, 150, 200, 250, -1], [1, 5, 10, 30, 50, 100, 150, 200, 250, "Todos"]],
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
                        "processing": '<i class="fa fa-spinner fa-spin fa-4x fa-fw text-info"></i><span class="sr-only">Loading...</span> ',
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
            } else {
                Swal.fire({
                    type: 'error',
                    title: 'Oops...',
                    text: 'Inserte un valor de busqueda.!',
                })
            }
        });
    </script>
    <script>
        $('#clearinput').on('click', function () {
            $('input[type="text"]').val('');

            $("#table-devices").dataTable().fnDestroy();
            $('#table-devices tbody').empty();
        });
    </script>
@endsection

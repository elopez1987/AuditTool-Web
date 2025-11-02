@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Kardex de tracto
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-8">
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
                                        <div class="col-lg-3 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="folionumber">Número de folio</label>
                                                <input type="text" class="form-control numeric" id="folionumber"
                                                       name="folionumber"
                                                       aria-describedby="name">
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="barcode">Código de barra</label>
                                                <input type="text" class="form-control numeric" id="barcode"
                                                       name="barcode"
                                                       aria-describedby="name">
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="trucknumber">Placa de tracto</label>
                                                <input type="text" class="form-control" id="trucknumber"
                                                       name="trucknumber"
                                                       aria-describedby="name">
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="">Fecha de busqueda</label>
                                                <div class='input-group mb-3'>
                                                    <input type='text' id="daterange" name="daterange"
                                                           class="form-control daterange"/>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">
                                                            <span class="ti-calendar"></span>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-lg-12">
                            <table width="100%" id="table-kardex"
                                   class="table table-condensed table-hover table-bordered">
                                <thead>
                                <tr>
                                    <th>Bodega</th>
                                    <th>Número de folio</th>
                                    <th>Codigo de barra</th>
                                    <th>Linea de transporte</th>
                                    <th>Placa de tracto</th>
                                    <th>Economico de tracto</th>
                                    <th>Operador</th>
                                    <th>Número de celular</th>
                                    <th>Fecha de ingreso</th>
                                    <th>Fecha de salida</th>
                                    <th>Usuario de entrada</th>
                                    <th>Usuario de salida</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-center">
                    Kardex.
                </div>
            </div>
        </div>
    </div>

@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('theme/assets/libs/daterangepicker/daterangepicker.css')}}">
@endsection



@section('script')
    <script src="{{asset('/theme/assets/libs/daterangepicker/daterangepicker.js')}}"></script>
    <script src="{{asset('/theme/assets/libs/moment/moment.js')}}"></script>
    <script>
        $('.daterange').daterangepicker();
    </script>
    <script>
        $(function () {
            let start_date = moment().subtract(3, 'M');
            let end_date = moment();
            $('#daterange span').html(start_date.format('YYYY-MM-DD') + ' - ' + end_date.format('YYYY-MM-DD'));
            $('#daterange').daterangepicker({
                startDate: start_date,
                endDate: end_date
            }, function (start_date, end_date) {
                $('#daterange span').html(start_date.format('YYYY-MM-DD') + ' - ' + end_date.format('YYYY-MM-DD'));
            });
        });

        $('#searchData').on('click', function () {
            let barcode = $('#barcode').val();
            let folionumber = $('#folionumber').val();
            let trucknumber = $('#trucknumber').val();
            let startDate = $('#daterange').data('daterangepicker').startDate.format('YYYY-MM-DD');
            let endDate = $('#daterange').data('daterangepicker').endDate.format('YYYY-MM-DD');

            if (folionumber !== '' || barcode !== '' || trucknumber !== '' || startDate !== '') {
                $("#table-kardex").dataTable().fnDestroy();
                let oTable = $("#table-kardex").DataTable({
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
                        url: 'getTruckTrace',
                        dataType: 'json',
                        data: function (d) {
                            d.folionumber = $('input[name=folionumber]').val();
                            d.barcode = $('input[name=barcode]').val();
                            d.trucknumber = $('input[name=trucknumber]').val();
                            d.startDate = startDate;
                            d.endDate = endDate;
                        }
                    },

                    "columns": [
                        {data: 'warehouse'},
                        {data: 'id'},
                        {data: 'barcode'},
                        {data: 'carriername'},
                        {data: 'trucknumber'},
                        {data: 'econumber'},
                        {data: 'operatorname'},
                        {data: 'phone'},
                        {data: 'entrydate'},
                        {data: 'finishdate'},
                        {data: 'creador'},
                        {data: 'usersalida'},
                        // {data: 'btn'},
                    ],
                    "columnDefs": [
                        {"width": "150px", "targets": 0},
                        {"width": "280px", "targets": 1},
                        {"width": "280px", "targets": 2},
                        {"width": "280px", "targets": 3},
                        {"width": "280px", "targets": 4},
                        {"width": "280px", "targets": 5},
                        {"width": "280px", "targets": 6},
                        {"width": "280px", "targets": 7},
                        {"width": "280px", "targets": 8},
                        {"width": "280px", "targets": 9},
                        {"width": "280px", "targets": 10},
                        {"width": "280px", "targets": 11},
                    ],
                    "dom": '<"row mb-3"<"col-sm-4"B><"col-sm-4 d-flex justify-content-center"l><"col-sm-4 d-flex justify-content-end"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',
                    "lengthMenu": [[1, 5, 10, 30, 50, 100, 150, 200, 250, -1], [1, 5, 10, 30, 50, 100, 150, 200, 250, "Todos"]],
                    buttons: [
                        {
                            "extend": "colvis",
                            "text": "<img src=\"{{asset('/pics/adbuscar.png')}}\"  alt=''><span  style='font-size: 0.675rem !important;'> Mostar/Ocultar</span>",
                            "className": "btn btn-outline-info btn-md  btn-bold",
                            columns: ':not(:first):not(:last)'
                        },
                        {
                            "extend": "csv",
                            "text": "<img src=\"{{asset('/ico/csv.png')}}\"  alt='' class='img-16'><span  style='font-size: 0.675rem !important;'> Csv</span>",
                            "className": "btn btn-outline-info btn-md  btn-bold",
                        },
                        {
                            "extend": "excel",
                            "text": "<img src=\"{{asset('/pics/adexportar.png')}}\"  alt=''><span  style='font-size: 0.675rem !important;'> Excel</span>",
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
            $("#table-kardex").dataTable().fnDestroy();
            $('#table-kardex tbody').empty();
        });
    </script>
@endsection

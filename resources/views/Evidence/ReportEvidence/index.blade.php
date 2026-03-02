@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Kardex de movimientos
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
                                                <label for="shipmentid">Embarque / Referencia</label>
                                                <input type="text" class="form-control" id="shipmentid"
                                                       name="shipmentid"
                                                       aria-describedby="name">
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="workday">F.Trabajo</label>
                                                <input type="text" class="form-control workday" id="workday"
                                                       name="workday"
                                                       aria-describedby="name">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="partnumberid">Sku</label>
                                                <input type="text" class="form-control" id="partnumberid"
                                                       name="partnumberid"
                                                       aria-describedby="name">
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="lpnid">Lpn</label>
                                                <input type="text" class="form-control" id="lpnid"
                                                       name="lpnid"
                                                       aria-describedby="name">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <table id="table-kardex"
                                   class="table table-condensed table-hover table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>Embarque</th>
                                    <th>Cliente</th>
                                    <th>Destino</th>
                                    <th>Sku</th>
                                    <th>Descrición</th>
                                    <th>Cantidad</th>
                                    <th>Lpn</th>
                                    <th>F.Verificación</th>
                                    <th>Usuario verificador</th>
                                    {{--                                    <th>F.Carga</th>--}}
                                    {{--                                    <th>Usuario de carga</th>--}}
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>

                </div>
                <div class="card-footer text-center">
                    Kardex de embarques.
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
            let partnumberid = $('#partnumberid').val();
            let lpnid = $('#lpnid').val();
            let workday = $('#workday').val();
            let shipmentid = $('#shipmentid').val();

            if (partnumberid != '' || lpnid != '' || workday != '' || shipmentid != '') {
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
                        url: 'getKardexContainer',
                        dataType: 'json',
                        data: function (d) {
                            d.shipmentid = $('input[name=shipmentid]').val();
                            d.partnumberid = $('input[name=partnumberid]').val();
                            d.workday = $('input[name=workday]').val();
                            d.lpnid = $('input[name=lpnid]').val();
                        }
                    },

                    "columns": [
                        {data: 'shipmentid'},
                        {data: 'customer'},
                        {data: 'city'},
                        {data: 'partnumberid'},
                        {data: 'description'},
                        {data: 'quantity'},
                        {data: 'lpnid'},
                        {data: 'adddatecheck'},
                        {data: 'scanusercheck'},
                    ],
                    "columnDefs": [
                        {"width": "150px", "targets": 0},
                        {"width": "280px", "targets": 1},
                        {"width": "280px", "targets": 2},
                        {"width": "120px", "targets": 3},
                        {"width": "250px", "targets": 4},
                        {"width": "120px", "targets": 5},
                        {"width": "220px", "targets": 6},
                        {"width": "220px", "targets": 7},
                        {"width": "220px", "targets": 8},
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

            $("#table-kardex").dataTable().fnDestroy();
            $('#table-kardex tbody').empty();
        });
    </script>
@endsection

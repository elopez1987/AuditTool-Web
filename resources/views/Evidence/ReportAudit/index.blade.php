@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Kardex de Auditoria
                </div>
                <div class="card-body">

                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs customtab" role="tablist">
                        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#view01" role="tab"><span
                                    class="hidden-sm-up"><i class="ti-home"></i></span> <span class="hidden-xs-down">Consulta por ID. Container</span></a>
                        </li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#view02" role="tab"><span
                                    class="hidden-sm-up"><i class="ti-search"></i></span> <span class="hidden-xs-down">Consulta por Master</span></a>
                        </li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div class="tab-pane active" id="view01" role="tabpanel">
                            <div class="p-20">
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
                                                            <label for="lpnid">Lpn / ID.Container</label>
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
                                <div class="row mt-4">
                                    <div class="col-lg-12">
                                        <table id="table-audit"
                                               class="table table-condensed table-hover table-bordered table-striped">
                                            <thead>
                                            <tr>
                                                <th>Embarque / Referencia</th>
                                                <th>Sku</th>
                                                <th>Descrición</th>
                                                <th>Cantidad</th>
                                                <th>Lpn</th>
                                                <th>Usuario</th>
                                                <th>F.Creación</th>
                                                <th>Evidencia</th>
                                                <th>Detalle</th>
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane  p-20" id="view02" role="tabpanel">
                            <div class="row">
                                <div class="col-lg-6">
                                    <form method="GET" id="searchMaster" role="form">
                                        {{csrf_field()}}
                                        <div class="row">
                                            <div class="col-md-12">
                                                <a>
                                                    <button id="searchDataMaster" type="button"
                                                            class="btn waves-effect waves-light btn-outline-info btn-md">
                                                        <img src="{{asset('/ico/reloadpage.png')}}" alt="" class="img-16 ml-1">
                                                        <span style="font-size: 11px;"> Buscar</span>
                                                    </button>
                                                </a>
                                                <a>
                                                    <button id="clearinputMaster" type="button"
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
                                                        <label for="masterid">Etiqueta Master</label>
                                                        <input type="text" class="form-control" id="masterid"
                                                               name="masterid"
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
                                    <table width="100%" id="table-master" class="table table-condensed table-hover table-bordered table-striped">
                                        <thead>
                                        <tr>
                                            <th>Master</th>
                                            <th>Usuario creador</th>
                                            <th>Fecha de creación</th>
                                            <th>Usuario de cierre</th>
                                            <th>Fecha de cierre</th>
                                            <th>Detalle</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-center">
                    Kardex de auditoria.
                </div>
            </div>
        </div>
    </div>

@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('/theme/assets/libs/sweetalert2/dist/sweetalert2.min.css')}}">
    <link rel="stylesheet" href="{{asset('/theme/assets/extra-libs/prism/prism.css')}}">
    <link rel="stylesheet" href="{{asset('/theme/assets/libs/magnific-popup/dist/magnific-popup.css')}}">
@endsection



@section('script')
    <script src="{{asset('/theme/assets/libs/sweetalert2/dist/sweetalert2.all.min.js')}}"></script>
    <script src="{{asset('theme/assets/extra-libs/prism/prism.js')}}"></script>
    <script src="{{asset('theme/assets/libs/magnific-popup/dist/jquery.magnific-popup.min.js')}}"></script>
    <script src="{{asset('theme/assets/libs/magnific-popup/meg.init.js')}}"></script>


    <script>
        $('#searchData').on('click', function () {
            let partnumberid = $('#partnumberid').val();
            let lpnid = $('#lpnid').val();
            let shipmentid = $('#shipmentid').val();

            if (partnumberid != '' || lpnid != '' || shipmentid != '') {
                $("#table-audit").dataTable().fnDestroy();
                let oTable = $("#table-audit").DataTable({
                    bDestroy: true,
                    "paging": true,
                    "searching": true,
                    "ordering": true,
                    "sScrollY": "250px",
                    "sScrollX": true,
                    "processing": true,
                    "serverSide": true,
                    "responsive": true,
                    "colReorder": true,
                    "stateSave": true,
                    "stateDuration": 0,
                    "ajax": {
                        url: 'getDetailContainerID',
                        dataType: 'json',
                        data: function (d) {
                            d.shipmentid = $('input[name=shipmentid]').val();
                            d.partnumberid = $('input[name=partnumberid]').val();
                            // d.workday = $('input[name=workday]').val();
                            d.lpnid = $('input[name=lpnid]').val();
                        }
                    },

                    "columns": [
                        {data: 'shipmentid'},
                        {data: 'partnumberid'},
                        {data: 'description'},
                        {data: 'quantity'},
                        {data: 'lpnid'},
                        {data: 'user'},
                        {data: 'created_at'},
                        {data: 'img'},
                        {data: 'btn'},
                    ],
                    "columnDefs": [
                        {"width": "150px", "targets": 0},
                        {"width": "280px", "targets": 1},
                        {"width": "280px", "targets": 2},
                        {"width": "220px", "targets": 3},
                        {"width": "250px", "targets": 4},
                        {"width": "320px", "targets": 5},
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
        $('#searchDataMaster').on('click', function () {
            let masterid = $('#masterid').val();


            if (masterid != '' ) {

                $("#table-master").dataTable().fnDestroy();
                let oTable = $("#table-master").DataTable({
                    bDestroy: true,
                    "paging": true,
                    "searching": true,
                    "ordering": true,
                    "sScrollY": "250px",
                    "sScrollX": true,
                    "processing": true,
                    "serverSide": true,
                    "responsive": true,
                    "colReorder": true,
                    "stateSave": true,
                    "stateDuration": 0,
                    "ajax": {
                        url: 'getMasterLabel',
                        dataType: 'json',
                        data: function (d) {
                            d.masterid = $('input[name=masterid]').val();
                        }
                    },

                    "columns": [
                        {data: 'masterid'},
                        {data: 'createuser'},
                        {data: 'createdate'},
                        {data: 'closeuser'},
                        {data: 'closedate'},
                        {data: 'btn'},
                    ],
                    "columnDefs": [
                        {"width": "150px", "targets": 0},
                        {"width": "280px", "targets": 1},
                        {"width": "280px", "targets": 2},
                        {"width": "120px", "targets": 3},
                        {"width": "250px", "targets": 4},
                        {"width": "250px", "targets": 5},
                    ],
                    "dom": '<"top"B>lrt<"bottom"p><"clear"i>',
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
            $("#table-audit").dataTable().fnDestroy();
            $('#table-audit tbody').empty();
        });
        $('#clearinputMaster').on('click', function () {
            $('input[type="text"]').val('');
            $("#table-master").dataTable().fnDestroy();
            $('#table-master tbody').empty();
        });
    </script>
@endsection

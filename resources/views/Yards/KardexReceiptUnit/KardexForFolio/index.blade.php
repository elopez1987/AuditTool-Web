@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Kardex de unidad
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form method="GET" id="search" role="form">
                                {{csrf_field()}}
                                <div class="row">
                                    <div class="col-md-12">
                                        <a>
                                            <button id="searchData" type="button"
                                                    class="btn waves-effect waves-light btn-outline-info btn-md">
                                                <img src="{{asset('/ico/reloadpage.png')}}" alt="" class="img-16 ml-1">
                                                <span class="text-button"> Buscar</span>
                                            </button>
                                        </a>
                                        <a>
                                            <button id="clearinput" type="button"
                                                    class="btn waves-effect waves-light btn-outline-info btn-md">
                                                <img src="{{asset('/ico/closemodal.png')}}" alt="" class="img-16 ml-1">
                                                <span class="text-button"> Limpiar filtros</span>
                                            </button>
                                        </a>
                                        <a href="{{route('home')}}">
                                            @include('buttons.cancel')
                                        </a>
                                    </div>
                                </div>

                                <div class="card-input">

                                    <div class="row">
                                        <div class="col-lg-2 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="warehouseid">Bodega </label>
                                                <select name="warehouseid" id="warehouseid"  class="form-control select2">
                                                    <option value="">Seleccione una bodega.</option>
                                                    @foreach($warehouses as $warehouse)
                                                        <option value="{{$warehouse->warehouseid}}"> {{$warehouse->name}} </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="carrierid">Lineas de transporte </label>
                                                <select name="carrierid" id="carrierid"  class="form-control select2">
                                                    <option value="">Seleccione una linea de transporte.</option>
                                                    @foreach($carriers as $model)
                                                        <option value="{{$model->id}}"> {{$model->name}} </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="razonstatusid">Concepto de ajuste</label>
                                                <select name="razonstatusid" id="razonstatusid"  class="form-control select2">
                                                    <option value="">Seleccione un concepto de ajuste.</option>
                                                    @foreach($concepts as $model)
                                                        <option value="{{$model->id}}"> {{$model->name}} </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="ecobox">Eco. Caja</label>
                                                <input type="text" class="form-control" id="ecobox" name="ecobox" aria-describedby="name">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-2 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="folionumber">Número de folio</label>
                                                <input type="text" class="form-control numeric" id="folionumber" name="folionumber" aria-describedby="name">
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="barcode">Código de barra</label>
                                                <input type="text" class="form-control numeric" id="barcode" name="barcode" aria-describedby="name">
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="shipmentid">Número de embarque</label>
                                                <input type="text" class="form-control" id="shipmentid" name="shipmentid" aria-describedby="name">
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="boxnumber">Placa de caja</label>
                                                <input type="text" class="form-control" id="boxnumber" name="boxnumber" aria-describedby="name">
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-6 col-sm-12">
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
                                   class="table table-condensed table-hover table-bordered ">
                                <thead>
                                <tr>
                                    <th>Bodega</th>
                                    <th>Número de folio</th>
                                    <th>Codigo de barra</th>
                                    <th>Linea de transporte</th>
                                    <th>Placa de caja</th>
                                    <th>Economico de caja</th>
                                    <th>Placa de tracto</th>
                                    <th>Operador</th>
                                    <th>Embarque</th>
                                    <th>Sellos</th>
                                    <th>Concepto de ajuste</th>
                                    <th>Fecha de registro</th>
                                    <th>Opciones</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-center">
                    Kardex de unidad.
                </div>
            </div>
        </div>
    </div>

@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('/theme/assets/libs/sweetalert2/dist/sweetalert2.min.css')}}">
    <link rel="stylesheet" href="{{asset('theme/assets/libs/daterangepicker/daterangepicker.css')}}">
@endsection



@section('script')
    <script src="{{asset('/theme/assets/libs/sweetalert2/dist/sweetalert2.all.min.js')}}"></script>
    <script src="{{asset('/theme/assets/libs/daterangepicker/daterangepicker.js')}}"></script>
    <script src="{{asset('/theme/assets/libs/moment/moment.js')}}"></script>

    <script>
        $(function () {
            let start_date = moment().subtract(3, 'M');
            let end_date = moment().add(2,'days');
            $('#daterange span').html(start_date.format('YYYY-MM-DD') + ' - ' + end_date.format('YYYY-MM-DD'));
            $('#daterange').daterangepicker({
                locale: {
                    cancelLabel: 'Clear'
                },
                startDate: start_date,
                endDate: end_date
            }, function (start_date, end_date) {
                $('#daterange span').html(start_date.format('YYYY-MM-DD') + ' - ' + end_date.format('YYYY-MM-DD'));
            });
        });

        $('#searchData').on('click', function () {
            let serialnumber = $('#serialnumber').val();
            let shipmentid = $('#shipmentid').val();
            let barcode = $('#barcode').val();

            if (serialnumber !== '' || shipmentid !== '') {
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
                        url: 'getDataReceiptUnitToKardex',
                        dataType: 'json',
                        data: function (d) {
                            d.folionumber = $('input[name=folionumber]').val();
                            d.barcode = $('input[name=barcode]').val();
                            d.shipmentid = $('input[name=shipmentid]').val();
                            d.boxnumber = $('input[name=boxnumber]').val();
                            d.ecobox = $('input[name=ecobox]').val();
                            d.razonstatusid = $('select[name=razonstatusid]').val();
                            d.carrierid = $('select[name=carrierid]').val();
                            d.warehouseid = $('select[name=warehouseid]').val();
                            d.startDate = $('#daterange').data('daterangepicker').startDate.format('YYYY-MM-DD');
                            d.endDate = $('#daterange').data('daterangepicker').endDate.format('YYYY-MM-DD');
                        }
                    },
                    "columns": [
                        {data: 'warehouse.name'},
                        {data: 'id'},
                        {data: 'barcode'},
                        {data: 'carrier.name'},
                        {data: 'boxnumber'},
                        {data: 'econumber'},
                        {data: 'trucknumber'},
                        {data: 'operatorname'},
                        {data: 'shipmentid'},
                        {data: 'containerseal'},
                        {data: 'reason.name'},
                        {data: 'entrydate'},
                        {data: 'btn'},
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
                        {"width": "280px", "targets": 12},
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
        $(function () {
            let start_date = moment().subtract(3, 'M');
            let end_date = moment().add(2,'days');
            $('#daterange').daterangepicker({
                locale: {
                    format: 'DD-MM-YYYY',
                    cancelLabel: 'Limpiar',
                    applyLabel: 'Aplicar',
                    separator: ' - '
                },
                startDate: start_date,
                endDate: end_date,
                autoUpdateInput: false
            });
            
            $('#daterange').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
            });
            
            $('#daterange').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });
            
            // Establecer valor inicial
            $('#daterange').val(start_date.format('DD-MM-YYYY') + ' - ' + end_date.format('DD-MM-YYYY'));
        });

        $('#searchData').on('click', function () {
            let serialnumber = $('#serialnumber').val();
            let shipmentid = $('#shipmentid').val();
            let barcode = $('#barcode').val();
            if (serialnumber !== '' || shipmentid !== '') {
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
                        url: 'getDataReceiptUnitToKardex',
                        dataType: 'json',
                        data: function (d) {
                            d.folionumber = $('input[name=folionumber]').val();
                            d.barcode = $('input[name=barcode]').val();
                            d.shipmentid = $('input[name=shipmentid]').val();
                            d.boxnumber = $('input[name=boxnumber]').val();
                            d.ecobox = $('input[name=ecobox]').val();
                            d.razonstatusid = $('select[name=razonstatusid]').val();
                            d.warehouseid = $('select[name=warehouseid]').val();
                            d.carrierid = $('select[name=carrierid]').val();
                            
                            // Enviar fechas vacías si no hay selección
                            let daterangeValue = $('#daterange').val();
                            if (daterangeValue && daterangeValue.trim() !== '') {
                                d.startDate = $('#daterange').data('daterangepicker').startDate.format('YYYY-MM-DD');
                                d.endDate = $('#daterange').data('daterangepicker').endDate.format('YYYY-MM-DD');
                            } else {
                                d.startDate = '';
                                d.endDate = '';
                            }
                        }
                    },
                    "columns": [
                        {data: 'warehouse.name'},
                        {data: 'id'},
                        {data: 'barcode'},
                        {data: 'carrier.name'},
                        {data: 'boxnumber'},
                        {data: 'econumber'},
                        {data: 'trucknumber'},
                        {data: 'operatorname'},
                        {data: 'shipmentid'},
                        {data: 'containerseal'},
                        {data: 'reason.name'},
                        {data: 'entrydate'},
                        {data: 'btn'},
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
                        {"width": "280px", "targets": 12},
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

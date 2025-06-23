@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Detalle de skus
                </div>
                <div class="card-body">
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
                            @can('inventoryhead.addstock')
                                <a href="" data-target="#modal-addsku" data-toggle="modal">
                                    <button type="submit" class="btn waves-effect waves-light btn-outline-info btn-md">
                                        <img src="{{asset('/ico/adagregar1.png')}}" alt=""
                                             class="img-16 ml-1">
                                        <span style="font-size: 11px;"> Agregar Sku</span>
                                    </button>
                                </a>
                                @include('Counting.InventoryHeadDron.addsku')
                            @endcan
                            @can('importinventorystock')
                                <a href="" data-target="#modal-importinventorystock" data-toggle="modal"
                                   data-placement="top">
                                    <button type="button"
                                            class="btn waves-effect waves-light btn-outline-info btn-md"
                                            data-toggle="tooltip" data-placement="top" title="Importar archivo">
                                        <img src="{{asset('/ico/uploadcsv.png')}}" alt="" class="img-16">
                                        <span style="font-size: 0.675rem !important;;"> Importar existencia </span>
                                    </button>
                                </a>
                                @include('Counting.InventoryHead.Imports.importstocksku')
                            @endcan
                        </div>
                    </div>
                    <hr class="mb-2">
                    <div class="row">
                        <div class="col-lg-2 col-md-4 col-sm-12" id="sello">
                            <div class="form-group">
                                <label for="inventoryid">Número de inventario:</label>
                                <input type="text" class="form-control" readonly value="{{$inventory->id}}"
                                       id="inventoryid" name="inventoryid">
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-12" id="sello">
                            <div class="form-group">
                                <label for="description">Descripción:</label>
                                <input type="text" class="form-control" readonly value="{{$inventory->description}}"
                                       id="description" name="description">
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-12" id="sello">
                            <div class="form-group">
                                <label for="partnumberid">Material:</label>
                                <input type="text" class="form-control" id="partnumberid" name="partnumberid">
                            </div>
                        </div>
                    </div>
                    @if($inventory->inventorytype == 'APP')
                        <div class="row">
                            <div class="col-lg-10">
                                <table width="80%" id="table-skuinventory"
                                       class="table table-bordered table-striped table-condensed">
                                    <thead>
                                    <tr>
                                        <th>Material</th>
                                        <th>Descripción</th>
                                        <th>Cantidad Wms</th>
                                        <th>Total Conteo 01</th>
                                        <th>Total Conteo 02</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    @else
                        <div class="row">
                            <div class="col-lg-10">
                                <table width="80%" id="table-skiinventorydron"
                                       class="table table-bordered table-striped table-condensed">
                                    <thead>
                                    <tr>
                                        <th>Material</th>
                                        <th>Descripción</th>
                                        <th>Cantidad Wms Piezas</th>
                                        <th>Cantidad Inventariada</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    @endif

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
        $(document).ready(function () {
            let oTable = $("#table-skuinventory").DataTable({
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
                    url: '../getInventorySku',
                    data: function (d) {
                        d.inventoryid = $('input[name=inventoryid]').val();
                        d.partnumberid = $('input[name=partnumberid]').val();
                    }
                },
                "columns": [
                    {data: 'partnumberid'},
                    {data: 'description'},
                    {data: 'totalstock'},
                    {data: 'tconteo01'},
                    {data: 'tconteo02'},
                ],
                "columnDefs": [
                    {"width": "150px", "targets": 0},
                    {"width": "350px", "targets": 1},
                    {"width": "150px", "targets": 2},
                    {"width": "150px", "targets": 3},
                    {"width": "150px", "targets": 4},
                ],
                "dom": '<"row mb-3"<"col-sm-4"B><"col-sm-4 d-flex justify-content-center"l><"col-sm-4 d-flex justify-content-end"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',
                "lengthMenu": [[10, 30, 50, 100, 150, 200, 500, 1000], [10, 30, 50, 100, 150, 200, 500, 1000]],
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
            $('#table-skuinventory').DataTable().ajax.reload();
        }
    </script>
    <script>
        $("#partnumberid").blur(function () {
            reloadtable();
            let partnumberid = document.getElementById("partnumberid").value;
            sessionStorage.setItem("partnumberid", partnumberid);
        });
    </script>
    <script>
        $(document).ready(function () {
            document.getElementById("partnumberid").value = sessionStorage.getItem('partnumberid');
            reloadtable();
        });
    </script>

    <script>
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#savesku').click(function (e) {
                e.preventDefault();
                $.ajax({
                    data: {
                        inventoryid: $("#inventoryid").val(),
                        totalbox: $("#totalbox").val(),
                        partnumberid: $("#partnumberid").val(),
                        locationid: $("#locationid").val(),
                        totallpns: $("#lpns").val(),
                    },
                    url: '{!! route('addstockforinventory') !!}',
                    type: "POST",
                    dataType: 'json',
                    success: data => {
                        $('#totalbox').val('');
                        $('#locationid').val('');
                        $('#lpns').val('');
                        $('#partnumberid').val('');
                        Swal.fire({
                            type: 'success',
                            text: ` Ubicación agregada : ${data.locationid}`,
                            width: 250,
                        })
                        $('#modal-addsku').modal('hide')
                    },
                });
            });
        });
    </script>
@endsection


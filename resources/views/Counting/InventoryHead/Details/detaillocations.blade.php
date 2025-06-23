@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Detalle de ubicaciones
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <a href="{{url('inventoryhead/' . $inventory->id)}}">
                                <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md">
                                    <img src="{{asset('/ico/adback.png')}}" alt="" class="img-16 ml-1">
                                    <span class="text-button"> Regresar</span>
                                </button>
                            </a>
                            @can('inventoryhead.addlocation')
                                <a href="" data-target="#modal-addlocation" data-toggle="modal">
                                    <button type="submit"
                                            class="btn waves-effect waves-light btn-outline-info btn-md">
                                        <img src="{{asset('/ico/adagregar1.png')}}" alt=""
                                             class="img-16 ml-1">
                                        <span class="text-button"> Agregar</span>
                                    </button>
                                </a>
                                @include('Counting.InventoryHead.Details.addlocation')
                            @endcan
                            <a>
                                <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md"
                                        onclick="reloadtable();">
                                    <img src="{{asset('/ico/reloadpage.png')}}" alt="" class="img-16 ml-1">
                                    <span class="text-button"> Refrescar</span>
                                </button>
                            </a>
                        </div>
                    </div>
                    <hr class="mb-2">
                    <div class="row">
                        <div class="col-lg-2 col-md-4 col-sm-12">
                            <div class="form-group">
                                <label for="inventoryid">Número de inventario:</label>
                                <input type="text" class="form-control" readonly value="{{$inventory->id}}"  id="inventoryid" name="inventoryid">
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-12">
                            <div class="form-group">
                                <label for="description">Descripción:</label>
                                <input type="text" class="form-control" readonly value="{{$inventory->description}}"  id="description" name="description">
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-12">
                            <div class="form-group">
                                <label for="aisleidf">Pasillo:</label>
                                <input type="text" class="form-control"  id="aisleidf" name="aisleidf">
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-12">
                            <div class="form-group">
                                <label for="cardidf">Cédula:</label>
                                <input type="text" class="form-control"  id="cardidf" name="cardidf">
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-12">
                            <div class="form-group">
                                <label for="locationidf">Ubicación:</label>
                                <input type="text" class="form-control"  id="locationidf" name="locationidf">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-10">
                            <table id="table-locationinventory"
                                   class="table table-bordered table-striped table-condensed">
                                <thead>
                                <tr>
                                    <th>Area</th>
                                    <th>Pasillo</th>
                                    <th>Cédula</th>
                                    <th>Ubicación</th>
                                    <th>Estatus conteo 01</th>
                                    <th>Estatus conteo 02</th>
                                    <th>Estatus conteo 03</th>
                                    <th>Unidad de medida</th>
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

@section('css')
    <link rel="stylesheet" href="{{asset('/theme/assets/libs/sweetalert2/dist/sweetalert2.min.css')}}">
@endsection


@section('script')
    <script src="{{asset('/theme/assets/libs/sweetalert2/dist/sweetalert2.all.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            let oTable = $("#table-locationinventory").DataTable({
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
                    url: '../getLocationInventory',
                    data: function (d) {
                        d.inventoryid = $('input[name=inventoryid]').val();
                        d.cardid = $('input[name=cardidf]').val();
                        d.locationid = $('input[name=locationidf]').val();
                        d.aisleid = $('input[name=aisleidf]').val();
                    }
                },
                "columns": [
                    {data: 'areaid'},
                    {data: 'aisleid'},
                    {data: 'cardid'},
                    {data: 'locationid'},
                    {
                        data: 'status1',
                        render: function (data, type) {
                            if (type === 'display') {
                                return data;
                            }
                        },
                        "createdCell": function (td, cellData, rowData) {
                            if (rowData.statusid01 === 'TO') {
                                $(td).css({
                                    'background-color': '#00e676',
                                    'color': 'black',
                                });
                            }
                            if(rowData.statusid01 === 'PN'){
                                $(td).css({
                                    'background-color': '#ff1744',
                                    'color': 'white',
                                });
                            }
                        }
                    },
                    {
                        data: 'status2',
                        render: function (data, type) {
                            if (type === 'display') {
                                return data;
                            }
                        },
                        "createdCell": function (td, cellData, rowData, row, col) {
                            if (rowData.statusid02 === 'TO') {
                                $(td).css({
                                    'background-color': '#00e676',
                                    'color': 'black',
                                });
                            }
                            if(rowData.statusid02 === 'PN'){
                                $(td).css({
                                    'background-color': '#ff1744',
                                    'color': 'white',
                                });
                            }
                        }
                    },
                    {
                        data: 'status3',
                        render: function (data, type) {
                            if (type === 'display') {
                                return data;
                            }
                        },
                        "createdCell": function (td, cellData, rowData, row, col) {
                            if (rowData.statusid03 === 'TO') {
                                $(td).css({
                                    'background-color': '#00e676',
                                    'color': 'black',
                                });
                            }
                            if(rowData.statusid03 === 'PN'){
                                $(td).css({
                                    'background-color': '#ff1744',
                                    'color': 'white',
                                });
                            }
                        }
                    },
                    {data: 'unitmeasureid'},
                ],
                "columnDefs": [
                    {"width": "150px", "targets": 0},
                    {"width": "150px", "targets": 1},
                    {"width": "150px", "targets": 2},
                    {"width": "150px", "targets": 3},
                    {"width": "150px", "targets": 4, "defaultContent": "-"},
                    {"width": "150px", "targets": 5, "defaultContent": "-"},
                    {"width": "150px", "targets": 6 ,"defaultContent": "-"},
                    {"width": "150px", "targets": 7},
                ],
                "dom": '<"row mb-3"<"col-sm-4"B><"col-sm-4 d-flex justify-content-center"l><"col-sm-4 d-flex justify-content-end"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',
                "lengthMenu": [[1,5,10, 30, 50, 100, 150, 200, 500, 1000], [1,5,10, 30, 50, 100, 150, 200, 500, 1000]],
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
            $('#table-locationinventory').DataTable().ajax.reload();
        }
    </script>
    <script>
        $("#cardidf,#locatioinidf").blur(function () {
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
            $('#savelocation').click(function (e) {
                e.preventDefault();
                $.ajax({
                    data: {
                        inventoryid: $("#inventoryid").val(),
                        areaid: $("#areaid").val(),
                        aisleid: $("#aisleid").val(),
                        cardid: $("#cardid").val(),
                        locationid: $("#locationid").val(),
                        type: $("#type").val(),
                        unitmeasureid: $("#unitmeasureid").val(),
                    },
                    url: '{!! route('addlocationinventory') !!}',
                    type: "POST",
                    dataType: 'json',
                    success: data => {
                        $('#areaid').val('');
                        $('#aisleid').val('');
                        $('#cardid').val('');
                        $('#locationid').val('');
                        $('#type').val('');
                        $('#unitmeasureid').val('');
                        Swal.fire({
                            type: 'success',
                            text: ` Existencia agregada con exito.`,
                            width: 250,
                        })
                        reloadtable();
                        $('#modal-addlocation').modal('hide')
                    },
                });
            });
        });
    </script>
@endsection


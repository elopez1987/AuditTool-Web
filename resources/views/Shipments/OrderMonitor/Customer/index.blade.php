@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Monitor de tráfico y facturación
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form" class="r-separator">
                        {{csrf_field()}}
                        <div class="row mb-3">
                            <div class="col-md-8">
                                <a href="">
                                    @include('buttons.search')
                                </a>
                                <a>
                                    @include('buttons.reloadajax')
                                </a>
                            </div>
                        </div>
                        <hr class="m-t-0">

                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header"></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <table id="table-customer"
                                   class="table table-bordered  table-condensed  table-hover tablegeneral" width="100%">
                                <thead>
                                    <tr>
                                        <th >Fecha</th>
                                        <th >Turno</th>
                                        <th >Fecha de carga</th>
                                        <th >Salida maxima</th>
                                        <th >Estatus</th>
                                        <th >Embarque</th>
                                        <th >Linea transportista</th>
                                        <th >Cliente</th>
                                        <th >Destino</th>
                                        <th >Fecha de descarga</th>
                                        <th >F.Entrada</th>
                                        <th >D. Negocio</th>
                                        <th >% de avance</th>
                                        <th >Horas</th>
                                        <th >Total de cajas</th>
                                        <th >Total de pallets</th>
                                        <th >Total peso</th>
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

@endsection

@section('script')
    <script>
            var oTable = $("#table-customer").DataTable({
                "paging": true,
                "searching": true,
                "ordering": true,
                "sScrollY": "450px",
                "sScrollX": true,
                "processing": true,
                "serverSide": true,
                "colReorder": true,
                "stateSave": true,
                "stateDuration": 0,
                "ajax": {
                    url: '{{route('monitor-customer-service')}}',
                    data: function (d) {
                        d.statusid = $('select[name="statusid[]"]').val();
                        d.warehouseid = $('select[name=warehouseid]').val();
                        d.turnid = $('select[name=turnid]').val();
                        d.shipmentid = $('input[name=shipmentid]').val();
                        d.workday = $('input[name=workday]').val();
                    }
                },
                "columns": [
                    {data: 'fecha'},
                    {data: 'turno'},
                    {data: 'loadingdate'},
                    {data: 'maxsalida'},
                    {data: 'status'},
                    {data: 'shipmentid'},
                    {data: 'carriername'},
                    {data: 'cliente'},
                    {data: 'city'},
                    {data: 'downloaddate'},
                    {data: 'fentrada'},
                    {data: 'dvnegocio'},
                    {data: 'armado'},
                    {data: 'diferencia_horas'},
                    {data: 'cajas'},
                    {data: 'pallets'},
                    {data: 'peso'},

                ],
                "columnDefs": [
                    {"width": "150px", "targets": 0,searchable: false,orderable: false},
                    {"width": "180px", "targets": 1,searchable: false,orderable: false},
                    {"width": "180px", "targets": 2},
                    {"width": "180px", "targets": 3},
                    {"width": "180px", "targets": 4},
                    {"width": "200px", "targets": 5},
                    {"width": "280px", "targets": 6},
                    {"width": "280px", "targets": 7},
                    {"width": "280px", "targets": 8},
                    {"width": "280px", "targets": 9},
                    {"width": "250px", "targets": 10},
                    {"width": "180px", "targets": 11},
                    {"width": "180px", "targets": 12,searchable: false,orderable: false},
                    {"width": "180px", "targets": 13},
                    {"width": "280px", "targets": 14,searchable: false,orderable: false},
                    {"width": "180px", "targets": 15,searchable: false,orderable: false},
                    {"width": "280px", "targets": 16,searchable: false,orderable: false},
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
                        "extend": "copy",
                        "text": "<img src=\"{{asset('/pics/adcopiar.png')}}\"  alt=''><span  style='font-size: 11px;'> Copiar</span>",
                        "className": "btn btn-outline-info btn-md  btn-bold",
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
    </script>


@endsection

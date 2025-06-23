@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Detalle de inventario - {{$inventoryhead->description}}
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="card">
                                <div class="d-flex flex-row">
                                    <div class="p-10 bg-success">
                                        <a href="{{url('detaillocationscount/' . $inventoryhead->id)}}">
                                            <h3 class="text-white  m-b-0"><i class="ti-arrow-right"></i></h3>
                                        </a>
                                    </div>
                                    <div class="p-10">
                                        <h3 class="text-success m-b-0">{{$locations->totalubicaciones}}</h3>
                                        <span class="text-muted">Total de ubicaciones</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="card">
                                <div class="d-flex flex-row">
                                    <div class="p-10 bg-success">
                                        <a href="{{url('detailskucount/' . $inventoryhead->id)}}">
                                            <h3 class="text-white  m-b-0"><i class="ti-arrow-right"></i></h3>
                                        </a>
                                    </div>
                                    <div class="p-10">
                                        <h3 class="text-success m-b-0">{{$totales->totalskus}}</h3>
                                        <span class="text-muted">Total de Sku's</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="card">
                                <div class="d-flex flex-row">
                                    <div class="p-10 bg-success">
                                        <a href="">
                                            <h3 class="text-white  m-b-0"><i class="ti-alert"></i></h3>
                                        </a>
                                    </div>
                                    <div class="p-10">
                                        <h3 class="text-success m-b-0">{{number_format(round($totales->totalcajas,3),3)}}</h3>
                                        <span class="text-muted">Total de cajas</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="card">
                                <div class="d-flex flex-row">
                                    <div class="p-10 bg-success">
                                        <a href="{{url('detailcardscount/' . $inventoryhead->id)}}">
                                            <h3 class="text-white  m-b-0"><i class="ti-arrow-right"></i></h3>
                                        </a>
                                    </div>
                                    <div class="p-10">
                                        <h3 class="text-success m-b-0">{{$locations->pasillos}}</h3>
                                        <span class="text-muted">Pasillos</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{--    <div class="row">--}}
    {{--        <div class="col-lg-12">--}}
    {{--            <div class="row">--}}
    {{--                <div class="col-lg-12">--}}
    {{--                    <div class="card">--}}
    {{--                        <div class="card-header"></div>--}}
    {{--                        <div class="card-body">--}}
    {{--                            <div class="row">--}}
    {{--                                <div class="col-lg-3">--}}
    {{--                                    <a href="{{url('exportcounttrans01/' . $inventoryhead->id)}}">--}}
    {{--                                        <button type="submit"--}}
    {{--                                                class="btn waves-effect waves-light btn-outline-info btn-md">--}}
    {{--                                            <img src="{{asset('/ico/Excel_2013_23480.png')}}" alt=""--}}
    {{--                                                 class="img-16 ml-1">--}}
    {{--                                            <span style="font-size: 11px;"> Exportar transitorias 01</span>--}}
    {{--                                        </button>--}}
    {{--                                    </a>--}}
    {{--                                </div>--}}

    {{--                                <div class="col-lg-3">--}}
    {{--                                    <a href="{{url('exportcountchep/' . $inventoryhead->id)}}">--}}
    {{--                                        <button type="submit"--}}
    {{--                                                class="btn waves-effect waves-light btn-outline-info btn-md">--}}
    {{--                                            <img src="{{asset('/ico/Excel_2013_23480.png')}}" alt=""--}}
    {{--                                                 class="img-16 ml-1">--}}
    {{--                                            <span style="font-size: 11px;"> Exportar conteo de tarimas</span>--}}
    {{--                                        </button>--}}
    {{--                                    </a>--}}
    {{--                                </div>--}}
    {{--                            </div>--}}
    {{--                            <div class="row mt-2">--}}
    {{--                                <div class="col-lg-3">--}}
    {{--                                </div>--}}
    {{--                                <div class="col-lg-3">--}}
    {{--                                    <a href="{{url('exportcounttrans02/' . $inventoryhead->id)}}">--}}
    {{--                                        <button type="submit"--}}
    {{--                                                class="btn waves-effect waves-light btn-outline-info btn-md">--}}
    {{--                                            <img src="{{asset('/ico/Excel_2013_23480.png')}}" alt=""--}}
    {{--                                                 class="img-16 ml-1">--}}
    {{--                                            <span style="font-size: 11px;"> Exportar transitorias 02</span>--}}
    {{--                                        </button>--}}
    {{--                                    </a>--}}
    {{--                                </div>--}}
    {{--                                <div class="col-lg-3">--}}
    {{--                                </div>--}}
    {{--                                <div class="col-lg-3">--}}
    {{--                                </div>--}}
    {{--                            </div>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--    </div>--}}
    <div class="row">
        <div class="col-lg-9">
            <div class="card">
                <div class="card-header">
                    Conteo de pasillos
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-condensed table-striped tablegeneral">
                        <thead>
                        <th>No. de Pasillo</th>
                        <th>Total de Cédulas</th>
                        <th>Total de ubicaciones</th>
                        <th>Ubicaciones contadas 01</th>
                        <th>Faltantes conteo 01</th>
                        <th>% de Avance 01</th>
                        <th>Ubicaciones contadas 02</th>
                        <th>Faltante conteo 02</th>
                        <th>% de Avance 02</th>
                        </thead>
                        <tbody>
                        @foreach($contados as $contado)
                            <tr>
                                <td>{{$contado->aisleid}}</td>
                                <td>{{$contado->cedulas}}</td>
                                <td>{{$contado->totalubicaciones}}</td>
                                <td>{{$contado->contados01}}</td>
                                <td>{{$contado->totalubicaciones - $contado->contados01}}</td>
                                <td style="font-weight: 700;color: red"> {{ intval(100 / $contado->totalubicaciones * $contado->contados01 )}}
                                    %
                                </td>
                                <td>{{$contado->contados02}}</td>
                                <td>{{$contado->totalubicaciones - $contado->contados02}}</td>
                                <td style="font-weight: 700;color: red"> {{ intval(100 / $contado->totalubicaciones * $contado->contados02 )}}
                                    %
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card">
                <div class="card-header"> % de Avance</div>
                <div class="card-body">
                    <ul class="list-style-none">
                        <li class="m-t-20">
                            <div class="d-flex align-items-center">
                                <i class="mdi mdi-apps display-5 text-muted"></i>
                                <div class="m-l-10">
                                    <h5 class="m-b-0">Total de ubicaciones</h5>
                                    <span class="font-20">{{$locations->totalubicaciones}} Ubicaciones</span></div>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 100%"
                                     aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </li>
                        <li class="m-t-20">
                            <div class="d-flex align-items-center">
                                <i class="mdi mdi-apps display-5 text-muted"></i>
                                <div class="m-l-10">
                                    <h5 class="m-b-0">Avance del primer conteo</h5>
                                    @if($totalContados[0]->totalstatus01 != 0)
                                        <span class="font-20">{{$totalContados[0]->totalstatus01}} Ubicaciones --  {{intval(100 / $locations->totalubicaciones * $totalContados[0]->totalstatus01)}} %</span>
                                    @endif
                                </div>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-orange" role="progressbar"
                                     @if($totalContados[0]->totalstatus01 != 0)
                                         style="width: {{intval(100 / $locations->totalubicaciones * $totalContados[0]->totalstatus01)}}%"
                                     aria-valuenow="{{intval(100 / $locations->totalubicaciones * $totalContados[0]->totalstatus01)}}"
                                     @endif
                                     aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                        </li>
                        <li class="m-t-20 m-b-20">
                            <div class="d-flex align-items-center">
                                <i class="mdi mdi-apps display-5 text-muted"></i>
                                <div class="m-l-10">
                                    <h5 class="m-b-0">Avance del segundo conteo</h5>
                                    @if($totalContados[0]->totalstatus02 != 0)
                                        <span class="font-20">{{$totalContados[0]->totalstatus02}} Ubicaciones -- {{intval(100 / $locations->totalubicaciones * $totalContados[0]->totalstatus02)}} %</span>
                                    @endif
                                </div>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-info" role="progressbar"
                                     @if($totalContados[0]->totalstatus02 != 0)
                                         style="width: {{intval(100 / $locations->totalubicaciones * $totalContados[0]->totalstatus02)}}%"
                                     aria-valuenow="{{intval(100 / $locations->totalubicaciones * $totalContados[0]->totalstatus01)}}"
                                     @endif
                                     aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-9">
            <div class="card">
                <div class="card-header">
                    Errores de conteo -
                    <a>
                        @include('buttons.reloadajax')
                    </a>
                </div>
                <div class="card-body">
                    <table id="table-locationerros" class="table table-bordered table-condensed table-striped">
                        <thead>
                        <tr>
                            <th>Inventario</th>
                            <th>Area</th>
                            <th>Pasillo</th>
                            <th>Cedula</th>
                            <th>Ubicación</th>
                            <th>Sku 01</th>
                            <th>Cantidad 01</th>
                            <th>Sku 02</th>
                            <th>Cantidad 02</th>
                            <th>Sku</th>
                            <th>Cantidad</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card">
                <div class="card-header">
                    Errores y Ajustes
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="w-75 c-cost">
                            <div class="d-inline-block align-middle">
                                <i class="mdi mdi-wallet text-success display-5 "></i>
                            </div>
                            <div class="d-inline-block align-middle m-l-10">
                                <span class="text-muted font-14">Errores de conteo</span>
                                <h2 class="text-danger font-bold m-b-0">{{$totalErrors[0]->totalSku + $totalErrors[0]->totalQuantity }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="row m-t-40">
                        <div class="w-75 c-cost">
                            <div class="d-inline-block align-middle">
                                <i class="mdi mdi-wallet text-success display-5 "></i>
                            </div>
                            <div class="d-inline-block align-middle m-l-10">
                                <span class="text-muted font-14">% de Error</span>
                                @if(!empty($locations->totalubicaciones))
                                    <h2 class="text-danger font-bold m-b-0">
                                        {{number_format(floatval( (100 / $locations->totalubicaciones) * ($totalErrors[0]->totalSku + $totalErrors[0]->totalQuantity)),2) }}
                                        %</h2>
                                @endif
                            </div>
                        </div>
                    </div>
                    <hr class="mt-4">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            @can('inventoryhead.adjustment')
                                                <a href="{{url('adjustmentinventory/' . $inventoryhead->id)}}">
                                                    <button type="submit"
                                                            class="btn waves-effect waves-light btn-outline-info btn-block btn-md">
                                                        <img src="{{asset('/ico/adedit.png')}}" alt=""
                                                             class="img-16 ml-1">
                                                        <span style="font-size: 11px;"> Ajustar 3er Conteo</span>
                                                    </button>
                                                </a>
                                            @endcan
                                        </div>
                                        <div class="col-lg-6">
                                            @can('inventoryhead.exportadjustment')
                                                <a href="{{url('exportadjustmentinventory/' . $inventoryhead->id)}}">
                                                    <button type="submit"
                                                            class="btn waves-effect waves-light btn-outline-info btn-block btn-md">
                                                        <img src="{{asset('/ico/Excel_2013_23480.png')}}" alt=""
                                                             class="img-16 ml-1">
                                                        <span style="font-size: 11px;"> Exportar ajustes</span>
                                                    </button>
                                                </a>
                                            @endcan
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-lg-6">
                                            @can('inventoryhead.exportinventorycount')
                                                <a href="{{url('exportinventorycount/' . $inventoryhead->id)}}">
                                                    <button type="submit"
                                                            class="btn waves-effect waves-light btn-outline-info btn-block btn-md">
                                                        <img src="{{asset('/ico/Excel_2013_23480.png')}}" alt=""
                                                             class="img-16 ml-1">
                                                        <span style="font-size: 11px;"> Exportar conteos</span>
                                                    </button>
                                                </a>
                                            @endcan
                                        </div>
                                        <div class="col-lg-6">
{{--                                            @can('inventoryhead.exportadjustment')--}}
{{--                                                <a href="{{url('exportadjustmentinventory/' . $inventoryhead->id)}}">--}}
{{--                                                    <button type="submit"--}}
{{--                                                            class="btn waves-effect waves-light btn-outline-info btn-block btn-md">--}}
{{--                                                        <img src="{{asset('/ico/Excel_2013_23480.png')}}" alt=""--}}
{{--                                                             class="img-16 ml-1">--}}
{{--                                                        <span style="font-size: 11px;"> Exportar ajustes</span>--}}
{{--                                                    </button>--}}
{{--                                                </a>--}}
{{--                                            @endcan--}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')

    <script>
        function reloadtable() {
            $('#table-locationerros').DataTable().ajax.reload();
        }
    </script>

    <script>
        let oTable = $(".tablegeneral").DataTable({
            "paging": true,
            "searching": true,
            "ordering": true,
            "serverSide": false,
            "sScrollY": "150px",
            "sScrollX": true,
            "processing": true,
            "autoFill": true,
            "colReorder": true,
            "stateSave": true,
            "stateDuration": 0,
            "dom": '<"row mb-3"<"col-sm-4"B><"col-sm-4 d-flex justify-content-center"l><"col-sm-4 d-flex justify-content-end"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',
            "lengthMenu": [[5,10, 30, 50, 100, 150, 200, 250, -1], [5,10, 30, 50, 100, 150, 200, 250, "Todos"]],
            "columnDefs": [
                {"width": "150px", "targets": 0},
                {"width": "150px", "targets": 1},
                {"width": "150px", "targets": 2},
                {"width": "150px", "targets": 3},
                {"width": "150px", "targets": 4},
                {"width": "150px", "targets": 5},
                {"width": "150px", "targets": 6},
                {"width": "150px", "targets": 7},
                {"width": "150px", "targets": 8},
            ],
            buttons: [
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
                "processing": "Procesando...",
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
    </script>
    <script>
        $(document).ready(function () {
            let oTable = $("#table-locationerros").DataTable({
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
                    url: '../getLocationWithError',
                    data: function (d) {
                        d.inventoryid = {!! $inventoryhead->id !!};
                    }
                },
                "columns": [
                    {data: 'id'},
                    {data: 'areaid'},
                    {data: 'aisleid'},
                    {data: 'cardid'},
                    {data: 'locationid'},
                    {data: 'partnumber01'},
                    {data: 'quantity01'},
                    {data: 'partnumber02'},
                    {data: 'quantity02'},
                    {data: 'sku'},
                    {data: 'quantity'},
                ],
                "columnDefs": [
                    {"width": "150px", "targets": 0},
                    {"width": "120px", "targets": 1},
                    {"width": "120px", "targets": 2},
                    {"width": "150px", "targets": 3},
                    {"width": "150px", "targets": 4},
                    {"width": "150px", "targets": 5},
                    {"width": "150px", "targets": 6},
                    {"width": "150px", "targets": 7},
                    {"width": "100px", "targets": 8},
                    {"width": "100px", "targets": 9},
                    {"width": "150px", "targets": 10},
                    // {"width": "150px", "targets": 11},
                ],
                "dom": '<"top"B>ltr<"bottom"><"clear"i>',
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
@endsection


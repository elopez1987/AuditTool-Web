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
                                        {{--                                        <a href="{{url('detaillocationscount/' . $inventoryhead->id)}}">--}}
                                        {{--                                            <h3 class="text-white  m-b-0"><i class="ti-arrow-right"></i></h3>--}}
                                        {{--                                        </a>--}}
                                    </div>
                                    <div class="p-10">
                                        <h3 class="text-success m-b-0">{{$totalLocations}}</h3>
                                        <span class="text-muted font-12 font-bold">Total de ubicaciones</span>
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
                                        <span class="text-muted font-12 font-bold">Total de Sku's</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="card">
                                <div class="d-flex flex-row">
                                    <div class="p-10 bg-success">
                                        {{--                                        <a href="">--}}
                                        {{--                                            <h3 class="text-white  m-b-0"><i class="ti-alert"></i></h3>--}}
                                        {{--                                        </a>--}}
                                    </div>
                                    <div class="p-10">
                                        <h3 class="text-success m-b-0">{{number_format(round($totales->totalcajas,2),2)}}</h3>
                                        <span class="text-muted font-bold font-12">Total de cajas</span>
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
                                        <h3 class="text-success m-b-0">{{$totalPasillos}}</h3>
                                        <span class="text-muted font-bold font-12">Pasillos</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                        <th>Ubicaciones contadas</th>
                        <th>Faltantes conteo</th>
                        <th>% de Avance</th>
                        </thead>
                        <tbody>
                        @foreach($totalContados as $contado)
                            <tr>
                                <td>{{$contado->aisleid}}</td>
                                <td>{{$contado->totalcedulas}}</td>
                                <td>{{$contado->totallocations}}</td>
                                <td>{{$contado->contados}}</td>
                                <td>{{$contado->totallocations - $contado->contados}}</td>
                                <td style="font-weight: 700;color: red"> {{ intval(100 / $contado->totallocations * $contado->contados )}}
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
                                    <span class="font-20">{{$totalLocations}} Ubicaciones</span></div>
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
                                    <h5 class="m-b-0">Avance General</h5>
                                    @if($totalCount != 0)
                                        <span class="font-20">{{$totalCount}} Ubicaciones --  {{intval(100 / $totalLocations * $totalCount)}} %</span>
                                    @endif
                                </div>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-orange" role="progressbar"
                                     @if($totalCount != 0) style="width: {{intval(100 / $totalLocations * $totalCount)}}%"
                                     aria-valuenow="{{intval(100 / $totalLocations * $totalCount)}}"
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
                </div>
                <div class="card-body">
                    <table id="tableerrors" class="table table-bordered table-condensed table-striped tablegeneral">
                        <thead>
                        <tr>
                            <th>Area</th>
                            <th>Pasillo</th>
                            <th>Cedula</th>
                            <th>Ubicación</th>
                            <th>Número de parte</th>
                            <th>Descripción</th>
                            <th>Cantidad</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($errorLocations as $error)
                            <tr>
                                <td>{{$error->areaid}}</td>
                                <td>{{$error->aisleid}}</td>
                                <td>{{$error->cardid}}</td>
                                <td>{{$error->locationid}}</td>
                                <td>{{$error->partnumberid}}</td>
                                <td>{{$error->description}}</td>
                                <td>{{$error->quantity}}</td>
                            </tr>
                        @endforeach
                        </tbody>
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
                        <div class="col-lg-6">
                            <div class="w-75 c-cost">
                                <div class="d-inline-block align-middle m-l-10">
                                    <span class="text-muted font-14">Errores de conteo</span>
                                    <h2 class="text-danger font-bold m-b-0">{{$totalErrors}}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="w-75 c-cost">
                                <div class="d-inline-block align-middle m-l-10">
                                    <span class="text-muted font-14">% de Error</span>
                                    {{--                                @if(!empty($locations->totalubicaciones))--}}
                                    <h2 class="text-danger font-bold m-b-0">
                                        {{number_format(floatval( (100 / $totalLocations) * ($totalErrors)),2) }}
                                        %
                                    </h2>
                                    {{--                                @endif--}}
                                </div>
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
                                                        <img src="{{asset('/ico/adexportar.png')}}" alt=""
                                                             class="img-16 ml-1">
                                                        <span style="font-size: 11px;"> Exportar ajustes</span>
                                                    </button>
                                                </a>
                                            @endcan
                                        </div>
                                    </div>
                                    {{--                                    <div class="row mt-3">--}}
                                    {{--                                        <div class="col-lg-6">--}}
                                    {{--                                            @can('inventoryhead.exportinventorycount')--}}
                                    {{--                                                <a href="{{url('exportinventorycount/' . $inventoryhead->id)}}">--}}
                                    {{--                                                    <button type="submit"--}}
                                    {{--                                                            class="btn waves-effect waves-light btn-outline-info btn-block btn-md">--}}
                                    {{--                                                        <img src="{{asset('/ico/Excel_2013_23480.png')}}" alt=""--}}
                                    {{--                                                             class="img-16 ml-1">--}}
                                    {{--                                                        <span style="font-size: 11px;"> Exportar conteos</span>--}}
                                    {{--                                                    </button>--}}
                                    {{--                                                </a>--}}
                                    {{--                                            @endcan--}}
                                    {{--                                        </div>--}}
                                    {{--                                    </div>--}}
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
            "lengthMenu": [[5, 10, 30, 50, 100, 150, 200, 250, -1], [5, 10, 30, 50, 100, 150, 200, 250, "Todos"]],
            "columnDefs": [
                {"width": "150px", "targets": 0},
                {"width": "150px", "targets": 1},
                {"width": "150px", "targets": 2},
                {"width": "150px", "targets": 3},
                {"width": "150px", "targets": 4},
                {"width": "150px", "targets": 5},
                // {"width": "150px", "targets": 6},
            ],
            buttons: [
                {
                    "extend": "copy",
                    "text": "<img src=\"{{asset('/pics/adcopiar.png')}}\"  alt=''><span  style='font-size: 0.675rem !important;'> Copiar</span>",
                    "className": "btn btn-outline-info btn-md  btn-bold",
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

@endsection


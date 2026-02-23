@extends('layouts.app')

@section('content')
    @foreach($centers as $center)
        @php
            $centerZones = $zonesByCenter->get($center->centerid, collect());
        @endphp
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header font-18 font-weight-bold">
                        Ocupación - {{$center->description}}
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12 col-lg-12">
                                <div class="card">
                                    <div class="card-body">

                                        <div class="row mb-3" id="cards-total">
                                            <div class="col-md-6 col-sm-12 col-lg-4">
                                                <div class="card border-left-success shadow h-100 py-2">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-center justify-content-center">
                                                            <div class="text-center">
                                                                <span class="font-14 font-weight-bold text-uppercase">Capacidad de Patio</span>
                                                                <h2 class="font-medium m-b-0" style="font-size: 42px !important;font-weight: 700 !important;color: #00c853">{{$totalLocations}}</h2>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-sm-12 col-lg-4">
                                                <div class="card border-left-danger shadow h-100 py-2">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-center justify-content-center">
                                                            <div class="text-center">
                                                                <span class="font-14 font-weight-bold text-uppercase">Unidades en Patio</span>
                                                                <h2 class="font-medium m-b-0" style="font-size: 42px !important;font-weight: 700 !important;color: #ff1744">{{$locationsBusy}}</h2>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-sm-12 col-lg-4">
                                                <div class="card border-left-primary shadow h-100 py-2">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-center justify-content-center">
                                                            <div class="text-center">
                                                                <span class="font-14 font-weight-bold text-uppercase">Ocupación</span>
                                                                @php
                                                                    $porcentajeOcupacion = $totalLocations > 0 ? round((100 * $locationsBusy) / $totalLocations) : 0;
                                                                @endphp
                                                                <h2 class="font-medium m-b-0" style="font-size: 42px !important;font-weight: 700 !important;color: #3d5afe">
                                                                    {{ $porcentajeOcupacion }} %
                                                                </h2>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-4">
                                            <div class="col-md-12">
                                                <table id="table-capacity-{{$center->centerid}}" class="table table-bordered table-condensed table-capacity font-14">
                                                    <thead>
                                                    <tr>
                                                        <th class="text-white"
                                                            style="font-size: 13px !important;font-weight: 600; background-color: #78909c;color: #ffffff !important;">
                                                            C.Distribución
                                                        </th>
                                                        <th class="text-white"
                                                            style="font-size: 13px !important;font-weight: 600; background-color: #78909c;color: #ffffff !important;">Zona
                                                        </th>
                                                        <th class="text-white"
                                                            style="font-size: 13px !important;font-weight: 600; background-color: #00c853;color: #ffffff !important;">
                                                            Capacidad
                                                        </th>
                                                        <th class="text-white"
                                                            style="font-size: 13px !important;font-weight: 600; background-color: #ff1744;color: #ffffff !important;">Ocupado
                                                        </th>
                                                        <th class="text-white"
                                                            style="font-size: 13px !important;font-weight: 600; background-color: #ffea00;color: black !important;">Libre
                                                        </th>
                                                        <th class="text-white"
                                                            style="font-size: 13px !important;font-weight: 600; background-color: #3d5afe;color: #ffffff !important;">Estatus
                                                        </th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($centerZones as $zone)
                                                        @php
                                                            $ocupado = $locationfill->get($zone->id, 0);
                                                            $libre = $zone->capacity > 0 ? $zone->capacity - $ocupado : 0;
                                                            $porcentaje = $zone->capacity > 0 ? round((100 / $zone->capacity) * $ocupado) : 0;
                                                        @endphp
                                                        <tr class="table-row-hover">
                                                            <td style="font-size: 13px !important; font-weight: 500;">{{$zone->center->description}}</td>
                                                            <td style="font-size: 13px !important; font-weight: 500;">{{$zone->name}}</td>
                                                            <td style="font-size: 13px !important; font-weight: 600; color: #00c853;text-align: center">{{$zone->capacity}}</td>
                                                            <td style="font-size: 13px !important; font-weight: 600; color: #ff1744;text-align: center">{{$ocupado}}</td>
                                                            <td style="font-size: 13px !important; font-weight: 600; color: #00e676;text-align: center">{{$libre}}</td>
                                                            <td style="font-size: 13px !important;">
                                                                @if($zone->capacity > 0)
                                                                    @if($porcentaje > $center->capacityyard)
                                                                        <span class="badge badge-danger font-weight-bold" style="font-size: 13px !important; background-color: #ff1744;text-align: center">{{$porcentaje}}%</span>
                                                                    @else
                                                                        <span class="badge badge-success font-weight-bold" style="font-size: 13px !important; background-color: #00e676; color: #000;text-align: center">{{$porcentaje}}%</span>
                                                                    @endif
                                                                @else
                                                                    <span class="badge badge-primary font-weight-bold" style="font-size: 13px !important; background-color: #3d5afe;text-align: center">0%</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <hr class="m-t-5 m-b-5" style="border-color: #1a237e">

                                        <div class="row mt-4">
                                            @foreach($centerZones as $zone)
                                                <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div id="chart-zone-{{$zone->id}}" style="min-height: 300px;"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                        <div class="row mt-3">
                                            <div class="col-lg-2 col-sm-12 col-xs-12">
                                                <a href="{{route('layoutinventoryforzone')}}">
                                                    <button type="submit" class="btn waves-effect waves-light btn-outline-info btn-md">
                                                        <img src="{{asset('/ico/adbuscar.png')}}" alt="" class="img-16 ml-1">
                                                        <span style="font-size: 0.675rem "> Ver detalles</span>
                                                    </button>
                                                </a>
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
    @endforeach


@endsection

@section('css')
    <style>
        .highcharts-credits {
            display: none !important;
        }
        .table-capacity {
            border: 2px solid #dee2e6 !important;
        }
        .table-capacity tbody td {
            font-size: 14px !important;
            vertical-align: middle;
            border: 1px solid #dee2e6 !important;
        }
        .table-capacity thead th {
            border: 1px solid #dee2e6 !important;
            text-align: center;
            vertical-align: middle;
        }
        .table-capacity tbody tr:hover {
            background-color: #f8f9fa;
            transition: background-color 0.2s ease;
        }
        .table-capacity tbody tr {
            transition: background-color 0.2s ease;
        }
        .border-left-success {
            border-left: 4px solid #28a745 !important;
        }
        .border-left-danger {
            border-left: 4px solid #dc3545 !important;
        }
        .border-left-primary {
            border-left: 4px solid #007bff !important;
        }
        .border-left-info {
            border-left: 4px solid #17a2b8 !important;
        }
    </style>
@endsection

@section('script')
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script>
        $(document).ready(function () {
            @foreach($centers as $center)
            @php
                $centerZones = $zonesByCenter->get($center->centerid, collect());
            @endphp

            // Gráficas de Pastel por Zona - {{$center->description}}
            @foreach($centerZones as $zone)
            @php
                $ocupado = $locationfill->get($zone->id, 0);
                $libre = $zone->capacity > 0 ? $zone->capacity - $ocupado : 0;
                $porcentaje = $zone->capacity > 0 ? round((100 / $zone->capacity) * $ocupado) : 0;
            @endphp
            Highcharts.chart('chart-zone-{{$zone->id}}', {
                chart: {
                    type: 'pie',
                    backgroundColor: '#ffffff',
                    style: {
                        fontFamily: 'inherit'
                    }
                },
                title: {
                    text: '{{$zone->name}}',
                    style: {
                        fontSize: '14px',
                        fontWeight: 'bold'
                    }
                },
                subtitle: {
                    text: 'Capacidad: {{$zone->capacity}} | Ocupación: {{$porcentaje}}%',
                    style: {
                        fontSize: '11px'
                    }
                },
                tooltip: {
                    pointFormat: '<b>{point.y}</b> ubicaciones ({point.percentage:.1f}%)'
                },
                accessibility: {
                    point: {
                        valueSuffix: '%'
                    }
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b><br>{point.y} ({point.percentage:.1f}%)',
                            style: {
                                fontSize: '11px',
                                fontWeight: 'bold'
                            }
                        },
                        showInLegend: true
                    }
                },
                series: [{
                    name: 'Ubicaciones',
                    colorByPoint: true,
                    data: [{
                        name: 'Ocupado',
                        y: {{$ocupado}},
                        color: '#ff1744',
                        sliced: {{$porcentaje > $center->capacityyard ? 'true' : 'false'}},
                        selected: {{$porcentaje > $center->capacityyard ? 'true' : 'false'}}
                    }, {
                        name: 'Libre',
                        y: {{$libre}},
                        color: '#00e676'
                    }]
                }],
                credits: {
                    enabled: false
                },
                exporting: {
                    enabled: true,
                    buttons: {
                        contextButton: {
                            menuItems: ['downloadPNG', 'downloadJPEG', 'downloadPDF', 'downloadSVG']
                        }
                    }
                }
            });
            @endforeach

            // DataTable para {{$center->description}}
            $("#table-capacity-{{$center->centerid}}").DataTable({
                "paging": true,
                "searching": true,
                "ordering": true,
                "sScrollY": "300px",
                "dom": '<"row mb-3"<"col-sm-4"B><"col-sm-4 d-flex justify-content-center"l><"col-sm-4 d-flex justify-content-end"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',
                "lengthMenu": [[25, 50, -1], [25, 50, "All"]],
                buttons: [
                    {
                        "extend": "colvis",
                        "text": "<img src=\"{{asset('/pics/adbuscar.png')}}\"  alt=''><span  style='font-size: 0.675rem !important;'> Mostar/Ocultar</span>",
                        "className": "btn btn-outline-info btn-md  btn-bold",
                        columns: ':not(:first):not(:last)'
                    },
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
            @endforeach
        })
    </script>

    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/highcharts-3d.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    <script src="https://code.highcharts.com/modules/cylinder.js"></script>
    <script src="https://code.highcharts.com/modules/funnel3d.js"></script>
    <script src="https://code.highcharts.com/highcharts-more.js"></script>
    <script src="https://code.highcharts.com/modules/dumbbell.js"></script>
    <script src="https://code.highcharts.com/modules/lollipop.js"></script>

    <script src="{{asset('/theme/assets/libs/daterangepicker/daterangepicker.js')}}"></script>
    <script src="{{asset('/theme/assets/libs/moment/moment.js')}}"></script>


@endsection

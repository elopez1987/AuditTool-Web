@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="card">
            <div class="card-body" id="filtros">

            </div>
        </div>
    </div>

    <div class="row ">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row" id="cards-total">
                        <div class="col-md-6 col-sm-12 col-lg-3">
                            <div class="card border-left-danger shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <div class="text-center">
                                            <span class="font-14 font-weight-bold text-uppercase">Total Unidades en Patio</span>
                                            <h2 class="font-medium m-b-0"
                                                style="font-size: 42px !important;font-weight: 700 !important;color: #ff1744">{{$locationsBusy}}</h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @foreach($unitsByStatus as $status)
                            @if($status->unitstatus)
                                <div class="col-md-6 col-sm-12 col-lg-3">
                                    <div class="card border-left-info shadow h-100 py-2">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <div class="text-center">
                                                    <span class="font-14 font-weight-bold text-uppercase">{{$status->unitstatus->name}}</span>
                                                    <h2 class="font-medium m-b-0"
                                                        style="font-size: 42px !important;font-weight: 700 !important;color: #17a2b8">{{$status->total}}</h2>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6" id="chart-line"></div>
                <div class="col-lg-3" id="chart-reason"></div>
                <div class="col-lg-3" id="chart-origen"></div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-4" id="chart-capacidad"></div>
                <div class="col-lg-4" id="chart-categoria"></div>
                <div class="col-lg-4" id="chart-unitstatu"></div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-4" id="chart-status"></div>
                <div class="col-lg-4" id="chart-zonas"></div>
                <div class="col-lg-4" id="chart-estadias">
                    <div class="card shadow-sm">
                        <div class="card-body p-3">
                            <h5 class="card-title font-weight-bold mb-2">Top 10 Unidades - Mayor Estadía</h5>
                            <p class="card-subtitle text-muted mb-3" style="font-size: 12px;">Unidades ordenadas por tiempo en patio</p>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="bg-primary text-white">
                                        <tr>
                                            <th style="font-size: 12px;">Folio de Unidad</th>
                                            <th style="font-size: 12px;">Línea de Transporte</th>
                                            <th style="font-size: 12px;" class="text-right">Estadía</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($topEstadias as $estadia)
                                            <tr>
                                                <td style="font-size: 14px !important;" class="font-weight-bold">#{{ $estadia->id }}</td>
                                                <td style="font-size: 14px !important;">{{ $estadia->carrier ? $estadia->carrier->name : 'Sin Asignar' }}</td>
                                                <td style="font-size: 12px !important;" class="text-right"><span class="badge badge-warning " style="font-size: 14px !important;" >{{ $estadia->estadia_formato }}</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('css')
    <style>
        .border-left-danger {
            border-left: 4px solid #dc3545 !important;
        }

        .border-left-info {
            border-left: 4px solid #17a2b8 !important;
        }
    </style>
@endsection

@section('script')
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

    <script>
        $(document).ready(function () {
            // Gráfico de barras horizontales - Unidades por Línea de Transporte
            Highcharts.chart('chart-line', {
                chart: {
                    type: 'bar',
                    backgroundColor: '#ffffff',
                    style: {
                        fontFamily: 'inherit'
                    }
                },
                title: {
                    text: 'Unidades en Patio por Línea de Transporte',
                    style: {
                        fontSize: '18px',
                        fontWeight: 'bold'
                    }
                },
                subtitle: {
                    text: 'Distribución de unidades activas por carrier'
                },
                xAxis: {
                    categories: [
                        @foreach($unitsByCarrier as $carrier)
                            '{{ $carrier->carrier ? $carrier->carrier->name : "Sin Asignar" }}',
                        @endforeach
                    ],
                    title: {
                        text: 'Líneas de Transporte'
                    },
                    labels: {
                        style: {
                            fontSize: '12px'
                        }
                    }
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Cantidad de Unidades',
                        align: 'high'
                    },
                    labels: {
                        overflow: 'justify'
                    }
                },
                tooltip: {
                    valueSuffix: ' unidades'
                },
                plotOptions: {
                    bar: {
                        dataLabels: {
                            enabled: true,
                            style: {
                                fontSize: '12px',
                                fontWeight: 'bold'
                            }
                        },
                        colorByPoint: true
                    }
                },
                legend: {
                    enabled: false
                },
                credits: {
                    enabled: false
                },
                series: [{
                    name: 'Unidades',
                    data: [
                        @foreach($unitsByCarrier as $carrier)
                                {{ $carrier->total }},
                        @endforeach
                    ],
                    colors: [
                        '#3d5afe', '#00c853', '#ff1744', '#ffc107', '#17a2b8',
                        '#6c757d', '#dc3545', '#28a745', '#007bff', '#fd7e14',
                        '#6f42c1', '#e83e8c', '#20c997', '#343a40', '#f8f9fa'
                    ]
                }],
                exporting: {
                    enabled: true,
                    buttons: {
                        contextButton: {
                            menuItems: ['downloadPNG', 'downloadJPEG', 'downloadPDF', 'downloadSVG', 'separator', 'downloadCSV', 'downloadXLS']
                        }
                    }
                }
            });

            // Gráfico Donut - Unidades por Razón de Estatus
            Highcharts.chart('chart-reason', {
                chart: {
                    type: 'pie',
                    backgroundColor: '#ffffff',
                    style: {
                        fontFamily: 'inherit'
                    }
                },
                title: {
                    text: 'Unidades por Concepto de Entrada',
                    style: {
                        fontSize: '18px',
                        fontWeight: 'bold'
                    }
                },
                subtitle: {
                    text: 'Distribución por motivo'
                },
                tooltip: {
                    pointFormat: '<b>{point.y}</b> unidades ({point.percentage:.1f}%)'
                },
                accessibility: {
                    point: {
                        valueSuffix: '%'
                    }
                },
                plotOptions: {
                    pie: {
                        innerSize: '60%',
                        depth: 45,
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b><br>{point.y} ({point.percentage:.1f}%)',
                            style: {
                                fontSize: '11px',
                                fontWeight: 'bold'
                            },
                            distance: 10
                        },
                        showInLegend: true
                    }
                },
                legend: {
                    align: 'right',
                    verticalAlign: 'middle',
                    layout: 'vertical',
                    itemStyle: {
                        fontSize: '12px'
                    }
                },
                series: [{
                    name: 'Unidades',
                    colorByPoint: true,
                    data: [
                            @foreach($unitsByReason as $reason)
                        {
                            name: '{{ $reason->reason ? $reason->reason->name : "Sin Asignar" }}',
                            y: {{ $reason->total }},
                            @if($loop->first)
                            sliced: true,
                            selected: true
                            @endif
                        },
                        @endforeach
                    ],
                    colors: [
                        '#3d5afe', '#00c853', '#ff1744', '#ffc107', '#17a2b8',
                        '#6c757d', '#dc3545', '#28a745', '#007bff', '#fd7e14',
                        '#6f42c1', '#e83e8c', '#20c997', '#343a40', '#adb5bd'
                    ]
                }],
                credits: {
                    enabled: false
                },
                exporting: {
                    enabled: true,
                    buttons: {
                        contextButton: {
                            menuItems: ['downloadPNG', 'downloadJPEG', 'downloadPDF', 'downloadSVG', 'separator', 'downloadCSV', 'downloadXLS']
                        }
                    }
                }
            });

            // Gráfico Pie Monocromático - Unidades por Tipo
            Highcharts.chart('chart-origen', {
                chart: {
                    type: 'pie',
                    backgroundColor: '#ffffff',
                    style: {
                        fontFamily: 'inherit'
                    }
                },
                title: {
                    text: 'Unidades por Origen',
                    style: {
                        fontSize: '18px',
                        fontWeight: 'bold'
                    }
                },
                subtitle: {
                    text: 'Distribución por origen de unidad'
                },
                tooltip: {
                    pointFormat: '<b>{point.y}</b> unidades ({point.percentage:.1f}%)'
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
                            format: '<b>{point.name}</b><br>{point.percentage:.1f}%',
                            style: {
                                fontSize: '11px',
                                fontWeight: 'bold',
                                color: 'black'
                            },
                            distance: 5
                        },
                        showInLegend: true
                    }
                },
                legend: {
                    align: 'center',
                    verticalAlign: 'bottom',
                    layout: 'horizontal',
                    itemStyle: {
                        fontSize: '11px'
                    }
                },
                colors: [
                    '#1a237e', '#283593', '#303f9f', '#3949ab', '#3f51b5',
                    '#5c6bc0', '#7986cb', '#9fa8da', '#c5cae9', '#e8eaf6'
                ],
                series: [{
                    name: 'Unidades',
                    data: [
                            @foreach($unitsByType as $type)
                        {
                            name: '{{ $type->unittype ? $type->unittype->description : "Sin Asignar" }}',
                            y: {{ $type->total }},
                            @if($loop->first)
                            sliced: true,
                            selected: true
                            @endif
                        },
                        @endforeach
                    ]
                }],
                credits: {
                    enabled: false
                },
                exporting: {
                    enabled: true,
                    buttons: {
                        contextButton: {
                            menuItems: ['downloadPNG', 'downloadJPEG', 'downloadPDF', 'downloadSVG', 'separator', 'downloadCSV', 'downloadXLS']
                        }
                    }
                }
            });

            // Gráfico Pie - Unidades por Camión (Top 10)
            Highcharts.chart('chart-capacidad', {
                chart: {
                    type: 'pie',
                    backgroundColor: '#ffffff',
                    style: {
                        fontFamily: 'inherit'
                    }
                },
                title: {
                    text: 'Unidades por Capacidad',
                    style: {
                        fontSize: '18px',
                        fontWeight: 'bold'
                    }
                },
                subtitle: {
                    text: 'Distribución por camión'
                },
                tooltip: {
                    pointFormat: '<b>{point.y}</b> unidades ({point.percentage:.1f}%)'
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
                                fontSize: '10px',
                                fontWeight: 'bold'
                            },
                            distance: 10
                        },
                        showInLegend: true
                    }
                },
                legend: {
                    align: 'right',
                    verticalAlign: 'middle',
                    layout: 'vertical',
                    itemStyle: {
                        fontSize: '10px'
                    }
                },
                series: [{
                    name: 'Unidades',
                    colorByPoint: true,
                    data: [
                            @foreach($unitsByTruck as $truck)
                        {
                            name: '{{ $truck->truck ? $truck->truck->name : "Sin Asignar" }}',
                            y: {{ $truck->total }},
                            @if($loop->first)
                            sliced: true,
                            selected: true
                            @endif
                        },
                        @endforeach
                    ],
                    colors: [
                        '#3d5afe', '#00c853', '#ff1744', '#ffc107', '#17a2b8',
                        '#6c757d', '#dc3545', '#28a745', '#007bff', '#fd7e14'
                    ]
                }],
                credits: {
                    enabled: false
                },
                exporting: {
                    enabled: true,
                    buttons: {
                        contextButton: {
                            menuItems: ['downloadPNG', 'downloadJPEG', 'downloadPDF', 'downloadSVG', 'separator', 'downloadCSV', 'downloadXLS']
                        }
                    }
                }
            });

            // Gráfico Semi Circle Donut - Unidades por Categoría de Camión
            Highcharts.chart('chart-categoria', {
                chart: {
                    type: 'pie',
                    backgroundColor: '#ffffff',
                    style: {
                        fontFamily: 'inherit'
                    }
                },
                title: {
                    text: 'Unidades por Categoría de Camión',
                    style: {
                        fontSize: '18px',
                        fontWeight: 'bold'
                    }
                },
                subtitle: {
                    text: 'Distribución por categoría'
                },
                tooltip: {
                    pointFormat: '<b>{point.y}</b> unidades ({point.percentage:.1f}%)'
                },
                accessibility: {
                    point: {
                        valueSuffix: '%'
                    }
                },
                plotOptions: {
                    pie: {
                        startAngle: -90,
                        endAngle: 90,
                        center: ['50%', '75%'],
                        size: '110%',
                        innerSize: '50%',
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b><br>{point.y} ({point.percentage:.1f}%)',
                            style: {
                                fontSize: '11px',
                                fontWeight: 'bold'
                            },
                            distance: -30,
                            color: 'white'
                        }
                    }
                },
                legend: {
                    align: 'center',
                    verticalAlign: 'top',
                    layout: 'horizontal',
                    itemStyle: {
                        fontSize: '11px'
                    }
                },
                series: [{
                    name: 'Unidades',
                    colorByPoint: true,
                    data: [
                            @foreach($unitsByTruckCategory as $category)
                        {
                            name: '{{ $category->truckcategory ? $category->truckcategory->description : "Sin Asignar" }}',
                            y: {{ $category->total }}
                        },
                        @endforeach
                    ],
                    colors: [
                        '#3d5afe', '#00c853', '#ff1744', '#ffc107', '#17a2b8',
                        '#6c757d', '#dc3545', '#28a745', '#007bff', '#fd7e14'
                    ]
                }],
                credits: {
                    enabled: false
                },
                exporting: {
                    enabled: true,
                    buttons: {
                        contextButton: {
                            menuItems: ['downloadPNG', 'downloadJPEG', 'downloadPDF', 'downloadSVG', 'separator', 'downloadCSV', 'downloadXLS']
                        }
                    }
                }
            });

            // Gráfico de Barras Horizontales - Unidades por Estatus
            Highcharts.chart('chart-unitstatu', {
                chart: {
                    type: 'bar',
                    backgroundColor: '#ffffff',
                    style: {
                        fontFamily: 'inherit'
                    }
                },
                title: {
                    text: 'Unidades por Estatus',
                    style: {
                        fontSize: '18px',
                        fontWeight: 'bold'
                    }
                },
                subtitle: {
                    text: 'Distribución por estatus de unidad'
                },
                xAxis: {
                    categories: [
                        @foreach($unitsByStatus as $status)
                                @if($status->unitstatus)
                            '{{ $status->unitstatus->name }}',
                        @endif
                        @endforeach
                    ],
                    title: {
                        text: 'Estatus'
                    },
                    labels: {
                        style: {
                            fontSize: '12px'
                        }
                    }
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Cantidad de Unidades',
                        align: 'high'
                    },
                    labels: {
                        overflow: 'justify'
                    }
                },
                tooltip: {
                    valueSuffix: ' unidades'
                },
                plotOptions: {
                    bar: {
                        dataLabels: {
                            enabled: true,
                            style: {
                                fontSize: '12px',
                                fontWeight: 'bold'
                            }
                        },
                        colorByPoint: true
                    }
                },
                legend: {
                    enabled: false
                },
                credits: {
                    enabled: false
                },
                series: [{
                    name: 'Unidades',
                    data: [
                        @foreach($unitsByStatus as $status)
                                @if($status->unitstatus)
                                {{ $status->total }},
                        @endif
                        @endforeach
                    ],
                    colors: [
                        '#3d5afe', '#00c853', '#ff1744', '#ffc107', '#17a2b8',
                        '#6c757d', '#dc3545', '#28a745', '#007bff', '#fd7e14'
                    ]
                }],
                exporting: {
                    enabled: true,
                    buttons: {
                        contextButton: {
                            menuItems: ['downloadPNG', 'downloadJPEG', 'downloadPDF', 'downloadSVG', 'separator', 'downloadCSV', 'downloadXLS']
                        }
                    }
                }
            });

            // Gráfico Pie - Unidades por Estatus General
            Highcharts.chart('chart-status', {
                chart: {
                    type: 'pie',
                    backgroundColor: '#ffffff',
                    style: {
                        fontFamily: 'inherit'
                    }
                },
                title: {
                    text: 'Unidades por Estatus de Proceso',
                    style: {
                        fontSize: '18px',
                        fontWeight: 'bold'
                    }
                },
                subtitle: {
                    text: 'Distribución por estatus del sistema'
                },
                tooltip: {
                    pointFormat: '<b>{point.y}</b> unidades ({point.percentage:.1f}%)'
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
                            },
                            distance: 15
                        },
                        showInLegend: true
                    }
                },
                legend: {
                    align: 'right',
                    verticalAlign: 'middle',
                    layout: 'vertical',
                    itemStyle: {
                        fontSize: '11px'
                    }
                },
                series: [{
                    name: 'Unidades',
                    colorByPoint: true,
                    data: [
                            @foreach($unitsByGeneralStatus as $genStatus)
                        {
                            name: '{{ $genStatus->status ? $genStatus->status->name : "Sin Asignar" }}',
                            y: {{ $genStatus->total }},
                            @if($loop->first)
                            sliced: true,
                            selected: true
                            @endif
                        },
                        @endforeach
                    ],
                    colors: [
                        '#3d5afe', '#00c853', '#ff1744', '#ffc107', '#17a2b8',
                        '#6c757d', '#dc3545', '#28a745', '#007bff', '#fd7e14'
                    ]
                }],
                credits: {
                    enabled: false
                },
                exporting: {
                    enabled: true,
                    buttons: {
                        contextButton: {
                            menuItems: ['downloadPNG', 'downloadJPEG', 'downloadPDF', 'downloadSVG', 'separator', 'downloadCSV', 'downloadXLS']
                        }
                    }
                }
            });

            // Gráfico de Barras Horizontales - Unidades por Zona
            Highcharts.chart('chart-zonas', {
                chart: {
                    type: 'bar',
                    backgroundColor: '#ffffff',
                    style: {
                        fontFamily: 'inherit'
                    }
                },
                title: {
                    text: 'Unidades por Zona de Almacenamiento',
                    style: {
                        fontSize: '18px',
                        fontWeight: 'bold'
                    }
                },
                subtitle: {
                    text: 'Distribución de unidades por zona'
                },
                xAxis: {
                    categories: [
                        @foreach($unitsByZone as $zone)
                            '{{ $zone->zone ? $zone->zone->name : "Sin Asignar" }}',
                        @endforeach
                    ],
                    title: {
                        text: 'Zonas'
                    },
                    labels: {
                        style: {
                            fontSize: '11px'
                        }
                    }
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Cantidad de Unidades',
                        align: 'high'
                    },
                    labels: {
                        overflow: 'justify'
                    }
                },
                tooltip: {
                    valueSuffix: ' unidades'
                },
                plotOptions: {
                    bar: {
                        dataLabels: {
                            enabled: true,
                            style: {
                                fontSize: '11px',
                                fontWeight: 'bold'
                            }
                        },
                        colorByPoint: true
                    }
                },
                legend: {
                    enabled: false
                },
                credits: {
                    enabled: false
                },
                series: [{
                    name: 'Unidades',
                    data: [
                        @foreach($unitsByZone as $zone)
                                {{ $zone->total }},
                        @endforeach
                    ],
                    colors: [
                        '#3d5afe', '#00c853', '#ff1744', '#ffc107', '#17a2b8',
                        '#6c757d', '#dc3545', '#28a745', '#007bff', '#fd7e14',
                        '#6f42c1', '#e83e8c', '#20c997', '#343a40', '#adb5bd'
                    ]
                }],
                exporting: {
                    enabled: true,
                    buttons: {
                        contextButton: {
                            menuItems: ['downloadPNG', 'downloadJPEG', 'downloadPDF', 'downloadSVG', 'separator', 'downloadCSV', 'downloadXLS']
                        }
                    }
                }
            });
        });
    </script>

@endsection

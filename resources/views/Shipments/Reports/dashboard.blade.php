@extends('layouts.app')

@section('css')
    <style>
        .kpi-total {
            text-align: center;
        }
        .kpi-total .kpi-value {
            font-size: 2.6rem;
            font-weight: 700;
            color: #2B2F33;
        }
        .kpi-total .kpi-label {
            font-size: .8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .03em;
            color: #5A5A5A;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Dashboard de embarques
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-lg-12">
                                <a href="">
                                    @include('buttons.search')
                                </a>
                                @include('buttons.reloadajax')
                                <a href="{{route('menushipment')}}">
                                    @include('buttons.cancel')
                                </a>
                            </div>
                        </div>
                        <hr class="m-t-1">
                        <div class="row">
                            <x-text-input
                                    name="startdate"
                                    type="date"
                                    label="Fecha de trabajo (inicio)"
                            />
                            <x-text-input
                                    name="enddate"
                                    type="date"
                                    label="Fecha de trabajo (fin)"
                            />
                            <x-select2
                                    name="cliente"
                                    label="Cliente"
                                    :options="$clientes"
                            />
                            <x-select2
                                    name="destino"
                                    label="Destino"
                                    :options="$destinos"
                            />
                            <x-select2
                                    name="linea"
                                    label="Línea de transporte"
                                    :options="$lineas"
                            />
                            <x-select2
                                    name="estatusdearribo"
                                    label="Estatus de arribo"
                                    :options="$estatusArribo"
                            />
                            <x-select2
                                    name="estatusdedespacho"
                                    label="Estatus de despacho"
                                    :options="$estatusDespacho"
                            />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 mb-3">
            <div class="card">
                <div class="card-body kpi-total">
                    <div class="kpi-value" id="kpi-total-shipments">-</div>
                    <div class="kpi-label">Cantidad de embarques</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 col-md-12 mb-3">
            <div class="card">
                <div class="card-body">
                    <div id="chart-workday"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12 mb-3">
            <div class="card">
                <div class="card-body">
                    <div id="chart-client"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12 mb-3">
            <div class="card">
                <div class="card-body">
                    <div id="chart-destination"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12 mb-3">
            <div class="card">
                <div class="card-body">
                    <div id="chart-carrier"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12 mb-3">
            <div class="card">
                <div class="card-body">
                    <div id="chart-arrival-status"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12 mb-3">
            <div class="card">
                <div class="card-body">
                    <div id="chart-dispatch-status"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>

    <script>
        // Misma paleta usada en las gráficas de home.blade.php y del
        // dashboard de incidencias.
        const shipmentsPalette = [
            '#d0df00', '#63666a', '#b5bd00', '#97999b',
            '#e3e935', '#d0d0ce', '#000000'
        ];

        let chartWorkday, chartClient, chartDestination, chartCarrier,
            chartArrivalStatus, chartDispatchStatus;

        function baseExporting() {
            return {
                enabled: true,
                buttons: {
                    contextButton: {
                        menuItems: ['downloadPNG', 'downloadJPEG', 'downloadPDF', 'downloadSVG', 'separator', 'downloadCSV', 'downloadXLS']
                    }
                }
            };
        }

        function makeBarChart(containerId, title, subtitle) {
            return Highcharts.chart(containerId, {
                chart: {
                    type: 'bar',
                    backgroundColor: '#ffffff',
                    style: {fontFamily: 'inherit'}
                },
                title: {text: title, style: {fontSize: '12px'}},
                subtitle: {text: subtitle},
                colors: shipmentsPalette,
                xAxis: {
                    categories: [],
                    title: {text: null},
                    labels: {style: {fontSize: '11px'}}
                },
                yAxis: {
                    min: 0,
                    title: {text: 'Embarques', align: 'high'},
                    labels: {overflow: 'justify'}
                },
                tooltip: {valueSuffix: ' embarques'},
                plotOptions: {
                    bar: {
                        dataLabels: {enabled: true, style: {fontSize: '11px', fontWeight: 'bold'}},
                        colorByPoint: true
                    }
                },
                legend: {enabled: false},
                credits: {enabled: false},
                series: [{name: 'Embarques', data: []}],
                exporting: baseExporting()
            });
        }

        function makePieChart(containerId, title, subtitle) {
            return Highcharts.chart(containerId, {
                chart: {
                    type: 'pie',
                    backgroundColor: '#ffffff',
                    style: {fontFamily: 'inherit'}
                },
                title: {text: title, style: {fontSize: '12px'}},
                subtitle: {text: subtitle},
                colors: shipmentsPalette,
                tooltip: {pointFormat: '<b>{point.y}</b> embarques ({point.percentage:.1f}%)'},
                accessibility: {point: {valueSuffix: '%'}},
                plotOptions: {
                    pie: {
                        innerSize: '60%',
                        allowPointSelect: true,
                        cursor: 'pointer',
                        colorByPoint: true,
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b><br>{point.y} ({point.percentage:.1f}%)',
                            style: {fontSize: '11px', fontWeight: 'bold'}
                        },
                        showInLegend: true
                    }
                },
                legend: {
                    align: 'right',
                    verticalAlign: 'middle',
                    layout: 'vertical',
                    itemStyle: {fontSize: '11px'}
                },
                series: [{name: 'Embarques', data: []}],
                credits: {enabled: false},
                exporting: baseExporting()
            });
        }

        function makeLineChart(containerId, title, subtitle) {
            return Highcharts.chart(containerId, {
                chart: {
                    type: 'line',
                    backgroundColor: '#ffffff',
                    style: {fontFamily: 'inherit'}
                },
                title: {text: title, style: {fontSize: '12px'}},
                subtitle: {text: subtitle},
                colors: shipmentsPalette,
                xAxis: {categories: [], labels: {style: {fontSize: '11px'}}},
                yAxis: {min: 0, title: {text: 'Embarques'}},
                tooltip: {valueSuffix: ' embarques'},
                legend: {enabled: false},
                credits: {enabled: false},
                series: [{name: 'Embarques', data: [], marker: {enabled: true}}],
                exporting: baseExporting()
            });
        }

        function setBarData(chart, rows) {
            chart.xAxis[0].setCategories(rows.map(r => r.label));
            chart.series[0].setData(rows.map(r => parseFloat(r.total) || 0));
        }

        function setPieData(chart, rows) {
            chart.series[0].setData(rows.map((r, index) => ({
                name: r.label,
                y: parseFloat(r.total) || 0,
                sliced: index === 0,
                selected: index === 0
            })));
        }

        function setLineData(chart, rows) {
            chart.xAxis[0].setCategories(rows.map(r => r.label));
            chart.series[0].setData(rows.map(r => parseFloat(r.total) || 0));
        }

        function getDashboardShipmentFilterParams() {
            return {
                startdate: $('input[name=startdate]').val(),
                enddate: $('input[name=enddate]').val(),
                cliente: $('select[name=cliente]').val(),
                destino: $('select[name=destino]').val(),
                linea: $('select[name=linea]').val(),
                estatusdearribo: $('select[name=estatusdearribo]').val(),
                estatusdedespacho: $('select[name=estatusdedespacho]').val(),
            };
        }

        function loadDashboardShipment() {
            $.get('{{ route("getDatsForDashboardShipment") }}', getDashboardShipmentFilterParams())
                .done(function (data) {
                    $('#kpi-total-shipments').text(data.total_shipments || 0);
                    setLineData(chartWorkday, data.by_workday || []);
                    setBarData(chartClient, data.by_client || []);
                    setBarData(chartDestination, data.by_destination || []);
                    setBarData(chartCarrier, data.by_carrier || []);
                    setPieData(chartArrivalStatus, data.by_arrival_status || []);
                    setPieData(chartDispatchStatus, data.by_dispatch_status || []);
                });
        }

        function reloadtable() {
            loadDashboardShipment();
        }

        $('#search').on('submit', function (e) {
            loadDashboardShipment();
            e.preventDefault();
        });

        // Dashboard dinámico: cualquier cambio en los filtros actualiza las
        // gráficas automáticamente, sin necesidad de presionar "Buscar".
        $('#search').on('change', 'input, select', function () {
            loadDashboardShipment();
        });

        $(document).ready(function () {
            chartWorkday = makeLineChart('chart-workday', 'Embarques por fecha de trabajo', 'Cantidad de embarques por día');
            chartClient = makeBarChart('chart-client', 'Embarques por cliente', 'Distribución por cliente');
            chartDestination = makeBarChart('chart-destination', 'Embarques por destino', 'Distribución por destino');
            chartCarrier = makeBarChart('chart-carrier', 'Embarques por línea de transporte', 'Distribución por línea de transporte');
            chartArrivalStatus = makePieChart('chart-arrival-status', 'Embarques por estatus de arribo', 'Distribución por estatus de arribo');
            chartDispatchStatus = makePieChart('chart-dispatch-status', 'Embarques por estatus de despacho', 'Distribución por estatus de despacho');

            loadDashboardShipment();
        });
    </script>
@endsection

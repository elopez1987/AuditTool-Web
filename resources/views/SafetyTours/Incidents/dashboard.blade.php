@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Dashboard de incidencias
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
                                <a href="{{route('tours')}}">
                                    @include('buttons.cancel')
                                </a>
                            </div>
                        </div>
                        <hr class="m-t-1">
                        <div class="row">
                            <x-text-input
                                    name="name"
                                    label="Nombre de la incidencia"
                                    uppercase
                            />
                            <x-text-input
                                    name="startdate"
                                    type="date"
                                    label="Fecha inicio (creación)"
                            />
                            <x-text-input
                                    name="enddate"
                                    type="date"
                                    label="Fecha fin (creación)"
                            />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 col-md-12 mb-3">
            <div class="card">
                <div class="card-body">
                    <div id="chart-category"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12 mb-3">
            <div class="card">
                <div class="card-body">
                    <div id="chart-subcategory"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12 mb-3">
            <div class="card">
                <div class="card-body">
                    <div id="chart-priority"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12 mb-3">
            <div class="card">
                <div class="card-body">
                    <div id="chart-responsible-area"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12 mb-3">
            <div class="card">
                <div class="card-body">
                    <div id="chart-created-user"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12 mb-3">
            <div class="card">
                <div class="card-body">
                    <div id="chart-closed-user"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12 mb-3">
            <div class="card">
                <div class="card-body">
                    <div id="chart-by-date"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12 mb-3">
            <div class="card">
                <div class="card-body">
                    <div id="chart-by-status"></div>
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
        // Misma paleta usada en las gráficas de home.blade.php
        const incidentsPalette = [
            '#d0df00', '#63666a', '#b5bd00', '#97999b',
            '#e3e935', '#d0d0ce', '#000000'
        ];

        let chartCategory, chartSubcategory, chartPriority, chartResponsibleArea,
            chartCreatedUser, chartClosedUser, chartByDate, chartByStatus;

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
                colors: incidentsPalette,
                xAxis: {
                    categories: [],
                    title: {text: null},
                    labels: {style: {fontSize: '11px'}}
                },
                yAxis: {
                    min: 0,
                    title: {text: 'Incidencias', align: 'high'},
                    labels: {overflow: 'justify'}
                },
                tooltip: {valueSuffix: ' incidencias'},
                plotOptions: {
                    bar: {
                        dataLabels: {enabled: true, style: {fontSize: '11px', fontWeight: 'bold'}},
                        colorByPoint: true
                    }
                },
                legend: {enabled: false},
                credits: {enabled: false},
                series: [{name: 'Incidencias', data: []}],
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
                colors: incidentsPalette,
                tooltip: {pointFormat: '<b>{point.y}</b> incidencias ({point.percentage:.1f}%)'},
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
                series: [{name: 'Incidencias', data: []}],
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
                colors: incidentsPalette,
                xAxis: {categories: [], labels: {style: {fontSize: '11px'}}},
                yAxis: {min: 0, title: {text: 'Incidencias'}},
                tooltip: {valueSuffix: ' incidencias'},
                legend: {enabled: false},
                credits: {enabled: false},
                series: [{name: 'Incidencias', data: [], marker: {enabled: true}}],
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

        function getDashboardIncidentsFilterParams() {
            return {
                name: $('input[name=name]').val(),
                startdate: $('input[name=startdate]').val(),
                enddate: $('input[name=enddate]').val(),
            };
        }

        function loadDashboardIncidents() {
            $.get('{{ route("getDashboardIncidents") }}', getDashboardIncidentsFilterParams())
                .done(function (data) {
                    setBarData(chartCategory, data.by_category || []);
                    setBarData(chartSubcategory, data.by_subcategory || []);
                    setPieData(chartPriority, data.by_priority || []);
                    setBarData(chartResponsibleArea, data.by_responsible_area || []);
                    setBarData(chartCreatedUser, data.by_created_user || []);
                    setBarData(chartClosedUser, data.by_closed_user || []);
                    setLineData(chartByDate, data.by_date || []);
                    setPieData(chartByStatus, data.by_status || []);
                });
        }

        function reloadtable() {
            loadDashboardIncidents();
        }

        $('#search').on('submit', function (e) {
            loadDashboardIncidents();
            e.preventDefault();
        });

        $(document).ready(function () {
            chartCategory = makeBarChart('chart-category', 'Incidencias por categoría', 'Distribución por categoría de la incidencia');
            chartSubcategory = makeBarChart('chart-subcategory', 'Incidencias por subcategoría', 'Distribución por subcategoría');
            chartPriority = makePieChart('chart-priority', 'Incidencias por prioridad', 'Distribución por prioridad');
            chartResponsibleArea = makeBarChart('chart-responsible-area', 'Incidencias por área responsable', 'Distribución por área responsable');
            chartCreatedUser = makeBarChart('chart-created-user', 'Incidencias por usuario creador', 'Distribución por usuario que registró la incidencia');
            chartClosedUser = makeBarChart('chart-closed-user', 'Incidencias por usuario que cierra', 'Distribución por usuario que cerró la incidencia');
            chartByDate = makeLineChart('chart-by-date', 'Total de incidencias por fecha de creación', 'Incidencias registradas por día');
            chartByStatus = makePieChart('chart-by-status', 'Total de incidencias por estatus', 'Distribución por estatus');

            loadDashboardIncidents();
        });
    </script>
@endsection

@extends('layouts.app')

@section('css')
    <style>
        .chart-card-body {
            position: relative;
            height: 320px;
        }
    </style>
@endsection

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
                <div class="card-header">Incidencias por categoría</div>
                <div class="card-body chart-card-body">
                    <canvas id="chartCategory"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12 mb-3">
            <div class="card">
                <div class="card-header">Incidencias por subcategoría</div>
                <div class="card-body chart-card-body">
                    <canvas id="chartSubcategory"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12 mb-3">
            <div class="card">
                <div class="card-header">Incidencias por prioridad</div>
                <div class="card-body chart-card-body">
                    <canvas id="chartPriority"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12 mb-3">
            <div class="card">
                <div class="card-header">Incidencias por área responsable</div>
                <div class="card-body chart-card-body">
                    <canvas id="chartResponsibleArea"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12 mb-3">
            <div class="card">
                <div class="card-header">Incidencias por usuario creador</div>
                <div class="card-body chart-card-body">
                    <canvas id="chartCreatedUser"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12 mb-3">
            <div class="card">
                <div class="card-header">Incidencias por usuario que cierra</div>
                <div class="card-body chart-card-body">
                    <canvas id="chartClosedUser"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12 mb-3">
            <div class="card">
                <div class="card-header">Total de incidencias por fecha de creación</div>
                <div class="card-body chart-card-body">
                    <canvas id="chartByDate"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12 mb-3">
            <div class="card">
                <div class="card-header">Total de incidencias por estatus</div>
                <div class="card-body chart-card-body">
                    <canvas id="chartByStatus"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{asset('/theme/assets/libs/chart.js/dist/Chart.min.js')}}"></script>
    <script>
        const dashboardCharts = {};
        const dashboardPalette = [
            '#556ee6', '#34c38f', '#f46a6a', '#f1b44c', '#50a5f1',
            '#74788d', '#ff8acc', '#02a8b5', '#ff6c2f', '#8b5cf6',
        ];

        function colorForIndex(index) {
            return dashboardPalette[index % dashboardPalette.length];
        }

        function renderBarChart(canvasId, labels, data) {
            const ctx = document.getElementById(canvasId).getContext('2d');

            if (dashboardCharts[canvasId]) {
                dashboardCharts[canvasId].data.labels = labels;
                dashboardCharts[canvasId].data.datasets[0].data = data;
                dashboardCharts[canvasId].update();

                return;
            }

            dashboardCharts[canvasId] = new Chart(ctx, {
                type: 'horizontalBar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Incidencias',
                        data: data,
                        backgroundColor: '#556ee6',
                    }],
                },
                options: {
                    legend: {display: false},
                    scales: {
                        xAxes: [{ticks: {beginAtZero: true, precision: 0}}],
                    },
                },
            });
        }

        function renderLineChart(canvasId, labels, data) {
            const ctx = document.getElementById(canvasId).getContext('2d');

            if (dashboardCharts[canvasId]) {
                dashboardCharts[canvasId].data.labels = labels;
                dashboardCharts[canvasId].data.datasets[0].data = data;
                dashboardCharts[canvasId].update();

                return;
            }

            dashboardCharts[canvasId] = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Incidencias',
                        data: data,
                        borderColor: '#556ee6',
                        backgroundColor: 'rgba(85, 110, 230, .15)',
                        fill: true,
                        tension: 0.2,
                    }],
                },
                options: {
                    legend: {display: false},
                    scales: {
                        yAxes: [{ticks: {beginAtZero: true, precision: 0}}],
                    },
                },
            });
        }

        function renderPieChart(canvasId, labels, data, colors) {
            const ctx = document.getElementById(canvasId).getContext('2d');
            const backgroundColor = labels.map((label, index) => (colors && colors[index]) || colorForIndex(index));

            if (dashboardCharts[canvasId]) {
                dashboardCharts[canvasId].data.labels = labels;
                dashboardCharts[canvasId].data.datasets[0].data = data;
                dashboardCharts[canvasId].data.datasets[0].backgroundColor = backgroundColor;
                dashboardCharts[canvasId].update();

                return;
            }

            dashboardCharts[canvasId] = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: backgroundColor,
                    }],
                },
                options: {
                    legend: {position: 'bottom'},
                },
            });
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
                    renderBarChart('chartCategory', data.by_category.map(r => r.label), data.by_category.map(r => r.total));
                    renderBarChart('chartSubcategory', data.by_subcategory.map(r => r.label), data.by_subcategory.map(r => r.total));
                    renderPieChart('chartPriority', data.by_priority.map(r => r.label), data.by_priority.map(r => r.total));
                    renderBarChart('chartResponsibleArea', data.by_responsible_area.map(r => r.label), data.by_responsible_area.map(r => r.total));
                    renderBarChart('chartCreatedUser', data.by_created_user.map(r => r.label), data.by_created_user.map(r => r.total));
                    renderBarChart('chartClosedUser', data.by_closed_user.map(r => r.label), data.by_closed_user.map(r => r.total));
                    renderLineChart('chartByDate', data.by_date.map(r => r.label), data.by_date.map(r => r.total));
                    renderPieChart('chartByStatus', data.by_status.map(r => r.label), data.by_status.map(r => r.total), data.by_status.map(r => r.color || '#b0bec5'));
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
            loadDashboardIncidents();
        });
    </script>
@endsection

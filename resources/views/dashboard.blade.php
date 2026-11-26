@extends('layouts.app')

@section('content')
    @php
        $defaultStartDate = now()->startOfMonth()->toDateString();
        $defaultEndDate = now()->toDateString();
    @endphp

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Dashboard de seguimiento de recepción
                </div>
                <div class="card-body">
                    <div class="row" id="filtros">
                        <div class="col-lg-12">
                            <form id="receipt-dashboard-filters">
                                <div class="row align-items-end">
                                    <div class="col-lg-2 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="warehouseid">Bodega</label>
                                            <select class="form-control select2" id="warehouseid" name="warehouseid">
                                                <option value="">Todas las bodegas</option>
                                                @foreach($warehouses as $warehouse)
                                                    <option value="{{ $warehouse->warehouseid }}">{{ $warehouse->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="providerid">Ruta</label>
                                            <select class="form-control select2" id="providerid" name="providerid">
                                                <option value="">Todas las rutas</option>
                                                @foreach($providers as $provider)
                                                    <option value="{{ $provider->id }}">{{ $provider->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="operatorid">Operador</label>
                                            <select class="form-control select2" id="operatorid" name="operatorid">
                                                <option value="">Todos los operadores</option>
                                                @foreach($operators as $operator)
                                                    <option value="{{ $operator->id }}">{{ $operator->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="start_date">Fecha de inicio</label>
                                            <input class="form-control" id="start_date" name="start_date" type="date"
                                                   value="{{ $defaultStartDate }}" data-default-value="{{ $defaultStartDate }}" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="end_date">Fecha de fin</label>
                                            <input class="form-control" id="end_date" name="end_date" type="date"
                                                   value="{{ $defaultEndDate }}" data-default-value="{{ $defaultEndDate }}" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-6 col-sm-12 mb-3">
                                        <button class="btn btn-info mr-2" id="filter-dashboard" type="submit">
                                            Filtrar
                                        </button>
                                        <button class="btn btn-outline-secondary" id="clear-dashboard-filters" type="button">
                                            Borrar filtros
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="row" id="cards" aria-live="polite">
                        <div class="col-12">
                            <div class="alert alert-info">Cargando indicadores de recepción...</div>
                        </div>
                    </div>
                    <div class="row" id="graficas">
                        <div class="col-lg-12 mb-4">
                            <div class="card dashboard-chart-card">
                                <div class="card-body">
                                    <div id="daily-receipts-chart"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-4">
                            <div class="card dashboard-chart-card">
                                <div class="card-body">
                                    <div id="routes-chart"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-4">
                            <div class="card dashboard-chart-card">
                                <div class="card-body">
                                    <div id="operators-chart"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="card dashboard-chart-card">
                                <div class="card-body">
                                    <div id="incidences-chart"></div>
                                </div>
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
        .dashboard-metric-card {
            border-left: 4px solid #26c6da;
        }

        .dashboard-metric-label {
            color: #6c757d;
            font-size: .75rem;
            margin-bottom: .25rem;
            text-transform: uppercase;
        }

        .dashboard-metric-value {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0;
        }

        .dashboard-chart-card {
            height: 100%;
        }

        #daily-receipts-chart,
        #routes-chart,
        #operators-chart,
        #incidences-chart {
            min-height: 350px;
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
        (function () {
            const filtersForm = document.getElementById('receipt-dashboard-filters');
            const cardsContainer = document.getElementById('cards');
            const filterButton = document.getElementById('filter-dashboard');
            const clearFiltersButton = document.getElementById('clear-dashboard-filters');
            const dataUrl = @json(route('dashboard.receipt-metrics'));
            const charts = {};
            const quantityFormatter = new Intl.NumberFormat('es-MX', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            });
            const integerFormatter = new Intl.NumberFormat('es-MX');

            function escapeHtml(value) {
                const element = document.createElement('div');
                element.textContent = value ?? '';

                return element.innerHTML;
            }

            function renderCards(cards) {
                const metrics = [
                    {label: 'Total de viajes', value: integerFormatter.format(cards.total_trips)},
                    {label: 'Rutas distintas', value: integerFormatter.format(cards.total_routes)},
                    {label: 'Operadores distintos', value: integerFormatter.format(cards.total_operators)},
                    {label: 'Producto recibido', value: quantityFormatter.format(cards.total_received)},
                    {label: 'Producto rotativo', value: quantityFormatter.format(cards.total_rotary)},
                    {label: 'Producto definitivo', value: quantityFormatter.format(cards.total_definitive)},
                    {label: 'Producto de nota de cargo', value: quantityFormatter.format(cards.total_credit_note)},
                ];

                cardsContainer.innerHTML = metrics.map(function (metric) {
                    return `
                        <div class="col-xl col-lg-3 col-md-4 col-sm-6 mb-4">
                            <div class="card h-100 shadow-sm dashboard-metric-card">
                                <div class="card-body">
                                    <p class="dashboard-metric-label">${escapeHtml(metric.label)}</p>
                                    <p class="dashboard-metric-value">${escapeHtml(metric.value)}</p>
                                </div>
                            </div>
                        </div>
                    `;
                }).join('');
            }

            function renderChart(containerId, options) {
                if (charts[containerId]) {
                    charts[containerId].destroy();
                }

                charts[containerId] = Highcharts.chart(containerId, options);
            }

            function renderCharts(chartData) {
                renderChart('daily-receipts-chart', {
                    chart: {type: 'line'},
                    title: {text: 'Vales recibidos por día'},
                    xAxis: {
                        categories: chartData.daily_receipts.categories,
                        title: {text: 'Fecha'},
                    },
                    yAxis: {
                        allowDecimals: false,
                        min: 0,
                        title: {text: 'Vales recibidos'},
                    },
                    tooltip: {shared: true},
                    series: [{
                        name: 'Vales recibidos',
                        data: chartData.daily_receipts.data,
                    }],
                });

                renderChart('routes-chart', {
                    chart: {type: 'pie'},
                    title: {text: 'Distribución de rutas de recepción'},
                    tooltip: {pointFormat: '<b>{point.y}</b> viajes ({point.percentage:.1f}%)'},
                    plotOptions: {
                        pie: {
                            allowPointSelect: true,
                            cursor: 'pointer',
                            dataLabels: {enabled: true, format: '{point.name}: {point.percentage:.1f}%'},
                        },
                    },
                    series: [{
                        name: 'Viajes',
                        colorByPoint: true,
                        data: chartData.routes.map(function (route) {
                            return {name: route.name, y: Number(route.total)};
                        }),
                    }],
                });

                renderChart('operators-chart', {
                    chart: {type: 'scatter'},
                    title: {text: 'Dispersión de recibos por operador'},
                    xAxis: {
                        categories: chartData.operators.map(function (operator) {
                            return operator.name;
                        }),
                        title: {text: 'Operador'},
                    },
                    yAxis: {
                        allowDecimals: false,
                        min: 0,
                        title: {text: 'Viajes recibidos'},
                    },
                    tooltip: {
                        formatter: function () {
                            return `<b>${escapeHtml(this.point.name)}</b><br>Viajes: ${this.y}`;
                        },
                    },
                    series: [{
                        name: 'Viajes',
                        data: chartData.operators.map(function (operator, index) {
                            return {x: index, y: Number(operator.total), name: operator.name};
                        }),
                    }],
                });

                renderChart('incidences-chart', {
                    chart: {type: 'column'},
                    title: {text: 'Producto verificado por categoría de incidencia'},
                    xAxis: {
                        categories: chartData.incidences.map(function (incidence) {
                            return `${incidence.sku} - ${incidence.description}`;
                        }),
                        crosshair: true,
                        title: {text: 'SKU y descripción'},
                    },
                    yAxis: {
                        min: 0,
                        title: {text: 'Cantidad de producto'},
                    },
                    tooltip: {
                        formatter: function () {
                            const point = this.point;

                            return `
                                <b>${escapeHtml(point.sku)}</b><br>
                                ${escapeHtml(point.description)}<br>
                                Categoría: ${escapeHtml(point.category)}<br>
                                Cantidad: ${quantityFormatter.format(this.y)}
                            `;
                        },
                    },
                    series: [{
                        name: 'Producto',
                        data: chartData.incidences.map(function (incidence) {
                            return {
                                y: Number(incidence.total),
                                sku: incidence.sku,
                                description: incidence.description,
                                category: incidence.category,
                            };
                        }),
                    }],
                });
            }

            async function loadDashboard() {
                if (!filtersForm.reportValidity()) {
                    return;
                }

                filterButton.disabled = true;
                const searchParameters = new URLSearchParams(new FormData(filtersForm));

                try {
                    const response = await fetch(`${dataUrl}?${searchParameters.toString()}`, {
                        headers: {
                            Accept: 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    });

                    if (!response.ok) {
                        throw new Error('No fue posible obtener los indicadores del dashboard.');
                    }

                    const data = await response.json();
                    renderCards(data.cards);
                    renderCharts(data.charts);
                } catch (error) {
                    cardsContainer.innerHTML = `
                        <div class="col-12">
                            <div class="alert alert-danger mb-0">
                                No fue posible cargar el dashboard. Intente nuevamente.
                            </div>
                        </div>
                    `;
                } finally {
                    filterButton.disabled = false;
                }
            }

            filtersForm.addEventListener('submit', function (event) {
                event.preventDefault();
                loadDashboard();
            });
            clearFiltersButton.addEventListener('click', function () {
                filtersForm.reset();
                document.querySelectorAll('[data-default-value]').forEach(function (input) {
                    input.value = input.dataset.defaultValue;
                });
                $('.select2').trigger('change.select2');
                loadDashboard();
            });

            loadDashboard();
        })();
    </script>
@endsection
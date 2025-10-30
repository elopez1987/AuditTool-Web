@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="card">
            <div class="card-body" id="filtros">
                <h5 class="card-title mb-3">Filtros</h5>
                <form id="filter-form">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="warehouse_id">Bodega</label>
                                <select class="form-control select2" id="warehouse_id" name="warehouse_id">
                                    <option value="">Todas</option>
                                    @foreach($warehouses as $warehouse)
                                        <option value="{{$warehouse->warehouseid}}">{{$warehouse->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="carrier_id">Línea de Transporte</label>
                                <select class="form-control select2" id="carrier_id" name="carrier_id">
                                    <option value="">Todos</option>
                                    @foreach($carriers as $carrier)
                                        <option value="{{$carrier->id}}">{{$carrier->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="reason_id">Concepto de Entrada</label>
                                <select class="form-control select2" id="reason_id" name="reason_id">
                                    <option value="">Todos</option>
                                    @foreach($reasons as $reason)
                                        <option value="{{$reason->id}}">{{$reason->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="d-block">&nbsp;</label>
                                <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md"
                                        id="apply-filters">
                                    <i class="fas fa-filter"></i> <span class="text-button">Aplicar Filtros </span>
                                </button>
                                <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md"
                                        id="clear-filters">
                                    <i class="fas fa-times"></i> <span class="text-button"> Limpiar </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
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

    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <div id="chart-line"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div id="chart-reason"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div  id="chart-origen"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-body">
                        <div id="chart-capacidad"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div id="chart-categoria"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="col-lg-4" id="chart-unitstatu"></div>
                </div>
            </div>
        </div>
    </div>



    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div id="chart-status"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div id="chart-zonas"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div id="chart-estadias">
                        <div class="card shadow-sm">
                            <div class="card-body p-3">
                                <h5 class="card-title font-weight-bold mb-2">Top 10 Unidades - Mayor Estadía</h5>
                                <p class="card-subtitle text-muted mb-3" style="font-size: 12px;">Unidades ordenadas por
                                    tiempo en patio</p>
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
                                                <td style="font-size: 14px !important;" class="font-weight-bold">
                                                    #{{ $estadia->id }}</td>
                                                <td style="font-size: 14px !important;">{{ $estadia->carrier ? $estadia->carrier->name : 'Sin Asignar' }}</td>
                                                <td style="font-size: 12px !important;" class="text-right"><span
                                                            class="badge badge-warning "
                                                            style="font-size: 14px !important;">{{ $estadia->estadia_formato }}</span>
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
        // Variables globales para almacenar referencias a las gráficas
        let chartLine, chartReason, chartOrigen, chartCapacidad, chartCategoria, chartUnitstatu, chartStatus,
            chartZonas;

        $(document).ready(function () {
            // Función para aplicar filtros
            $('#apply-filters').on('click', function () {
                const $btn = $(this);
                const originalText = $btn.html();

                // Deshabilitar botón y mostrar loading
                $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Cargando...');

                const carrierId = $('#carrier_id').val();
                const reasonId = $('#reason_id').val();
                const warehouseId = $('#warehouse_id').val();

                console.log('Enviando filtros:', {carrierId, reasonId, warehouseId});

                $.ajax({
                    url: '{{ route("home.filter") }}',
                    method: 'GET',
                    data: {
                        carrier_id: carrierId,
                        reason_id: reasonId,
                        warehouse_id: warehouseId
                    },
                    success: function (data) {
                        console.log('Datos recibidos:', data);
                        console.log('Tipo de datos:', typeof data);
                        console.log('Es string?', typeof data === 'string');

                        // Si la respuesta es un string, intentar parsearlo
                        if (typeof data === 'string') {
                            try {
                                data = JSON.parse(data);
                                console.log('Datos parseados:', data);
                            } catch (e) {
                                console.error('Error al parsear JSON:', e);
                                alert('Error: Respuesta inválida del servidor');
                                return;
                            }
                        }

                        updateCharts(data);
                    },
                    error: function (xhr, status, error) {
                        console.error('Error al aplicar filtros:');
                        console.error('Status:', status);
                        console.error('Error:', error);
                        console.error('Response:', xhr.responseText);
                        alert('Error al aplicar los filtros: ' + error);
                    },
                    complete: function () {
                        // Restaurar botón
                        $btn.prop('disabled', false).html(originalText);
                    }
                });
            });

            // Función para limpiar filtros
            $('#clear-filters').on('click', function () {
                $('#filter-form')[0].reset();
                $('#apply-filters').click();
            });

            // Función para actualizar gráficas
            function updateCharts(data) {
                try {
                    console.log('Iniciando actualización de gráficas...');

                    // Actualizar cards totales
                    console.log('Actualizando cards...');
                    updateCards(data);

                    // Actualizar gráfica de carriers
                    console.log('Actualizando chart-line...', data.unitsByCarrier);
                    updateCarrierChart(data.unitsByCarrier);

                    // Actualizar gráfica de razones
                    console.log('Actualizando chart-reason...', data.unitsByReason);
                    updateReasonChart(data.unitsByReason);

                    // Actualizar gráfica de tipos
                    console.log('Actualizando chart-origen...', data.unitsByType);
                    updateTypeChart(data.unitsByType);

                    // Actualizar gráfica de camiones
                    console.log('Actualizando chart-capacidad...', data.unitsByTruck);
                    updateTruckChart(data.unitsByTruck);

                    // Actualizar gráfica de categorías
                    console.log('Actualizando chart-categoria...', data.unitsByTruckCategory);
                    updateTruckCategoryChart(data.unitsByTruckCategory);

                    // Actualizar gráfica de estatus de unidad
                    console.log('Actualizando chart-unitstatu...', data.unitsByStatus);
                    updateUnitStatusChart(data.unitsByStatus);

                    // Actualizar gráfica de estatus general
                    console.log('Actualizando chart-status...', data.unitsByGeneralStatus);
                    updateGeneralStatusChart(data.unitsByGeneralStatus);

                    // Actualizar gráfica de zonas
                    console.log('Actualizando chart-zonas...', data.unitsByZone);
                    updateZonesChart(data.unitsByZone);

                    // Actualizar tabla de estadías
                    console.log('Actualizando tabla de estadías...', data.topEstadias);
                    updateEstadiasTable(data.topEstadias);

                    console.log('Actualización completada exitosamente');
                } catch (error) {
                    console.error('Error al actualizar gráficas:', error);
                    alert('Error al actualizar las gráficas: ' + error.message);
                }
            }

            // Función para actualizar cards
            function updateCards(data) {
                // Actualizar total de unidades
                $('#cards-total').find('h2').first().text(data.locationsBusy || 0);

                // Actualizar cards por estatus
                let statusCardsHtml = '';
                if (data.unitsByStatus && Array.isArray(data.unitsByStatus)) {
                    data.unitsByStatus.forEach(function (status) {
                        if (status && status.unitstatus) {
                            statusCardsHtml += `
                                <div class="col-md-6 col-sm-12 col-lg-3">
                                    <div class="card border-left-info shadow h-100 py-2">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <div class="text-center">
                                                    <span class="font-14 font-weight-bold text-uppercase">${status.unitstatus.name}</span>
                                                    <h2 class="font-medium m-b-0" style="font-size: 42px !important;font-weight: 700 !important;color: #17a2b8">${status.total}</h2>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                        }
                    });
                }

                // Reconstruir los cards manteniendo el card de total
                let totalCard = $('#cards-total').find('.col-md-6').first().clone();
                $('#cards-total').empty().append(totalCard).append(statusCardsHtml);
            }

            // Gráfico de barras horizontales - Unidades por Línea de Transporte
            chartLine = Highcharts.chart('chart-line', {
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
            chartReason = Highcharts.chart('chart-reason', {
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
            chartOrigen = Highcharts.chart('chart-origen', {
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
            chartCapacidad = Highcharts.chart('chart-capacidad', {
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
            chartCategoria = Highcharts.chart('chart-categoria', {
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
            chartUnitstatu = Highcharts.chart('chart-unitstatu', {
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
            chartStatus = Highcharts.chart('chart-status', {
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
            chartZonas = Highcharts.chart('chart-zonas', {
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

            // Funciones para actualizar cada gráfica
            function updateCarrierChart(data) {
                if (!data || data.length === 0) {
                    chartLine.xAxis[0].setCategories([]);
                    chartLine.series[0].setData([]);
                    return;
                }
                const categories = data.map(item => item.carrier ? item.carrier.name : 'Sin Asignar');
                const values = data.map(item => parseFloat(item.total) || 0);

                chartLine.xAxis[0].setCategories(categories);
                chartLine.series[0].setData(values);
            }

            function updateReasonChart(data) {
                if (!data || data.length === 0) {
                    chartReason.series[0].setData([]);
                    return;
                }
                const chartData = data.map((item, index) => ({
                    name: item.reason ? item.reason.name : 'Sin Asignar',
                    y: parseFloat(item.total) || 0,
                    sliced: index === 0,
                    selected: index === 0
                }));

                chartReason.series[0].setData(chartData);
            }

            function updateTypeChart(data) {
                if (!data || data.length === 0) {
                    chartOrigen.series[0].setData([]);
                    return;
                }
                const chartData = data.map((item, index) => ({
                    name: item.unittype ? item.unittype.description : 'Sin Asignar',
                    y: parseFloat(item.total) || 0,
                    sliced: index === 0,
                    selected: index === 0
                }));

                chartOrigen.series[0].setData(chartData);
            }

            function updateTruckChart(data) {
                if (!data || data.length === 0) {
                    chartCapacidad.series[0].setData([]);
                    return;
                }
                const chartData = data.map((item, index) => ({
                    name: item.truck ? item.truck.name : 'Sin Asignar',
                    y: parseFloat(item.total) || 0,
                    sliced: index === 0,
                    selected: index === 0
                }));

                chartCapacidad.series[0].setData(chartData);
            }

            function updateTruckCategoryChart(data) {
                if (!data || data.length === 0) {
                    chartCategoria.series[0].setData([]);
                    return;
                }
                const chartData = data.map(item => ({
                    name: item.truckcategory ? item.truckcategory.description : 'Sin Asignar',
                    y: parseFloat(item.total) || 0
                }));

                chartCategoria.series[0].setData(chartData);
            }

            function updateUnitStatusChart(data) {
                if (!data || data.length === 0) {
                    chartUnitstatu.xAxis[0].setCategories([]);
                    chartUnitstatu.series[0].setData([]);
                    return;
                }
                const categories = data.filter(item => item.unitstatus).map(item => item.unitstatus.name);
                const values = data.filter(item => item.unitstatus).map(item => parseFloat(item.total) || 0);

                chartUnitstatu.xAxis[0].setCategories(categories);
                chartUnitstatu.series[0].setData(values);
            }

            function updateGeneralStatusChart(data) {
                if (!data || data.length === 0) {
                    chartStatus.series[0].setData([]);
                    return;
                }
                const chartData = data.map((item, index) => ({
                    name: item.status ? item.status.name : 'Sin Asignar',
                    y: parseFloat(item.total) || 0,
                    sliced: index === 0,
                    selected: index === 0
                }));

                chartStatus.series[0].setData(chartData);
            }

            function updateZonesChart(data) {
                if (!data || data.length === 0) {
                    chartZonas.xAxis[0].setCategories([]);
                    chartZonas.series[0].setData([]);
                    return;
                }
                const categories = data.map(item => item.zone ? item.zone.name : 'Sin Asignar');
                const values = data.map(item => parseFloat(item.total) || 0);

                chartZonas.xAxis[0].setCategories(categories);
                chartZonas.series[0].setData(values);
            }

            function updateEstadiasTable(data) {
                let html = '';
                if (data && Array.isArray(data) && data.length > 0) {
                    data.forEach(function (estadia) {
                        html += `
                            <tr>
                                <td style="font-size: 14px !important;" class="font-weight-bold">#${estadia.id}</td>
                                <td style="font-size: 14px !important;">${estadia.carrier ? estadia.carrier.name : 'Sin Asignar'}</td>
                                <td style="font-size: 12px !important;" class="text-right">
                                    <span class="badge badge-warning" style="font-size: 14px !important;">${estadia.estadia_formato}</span>
                                </td>
                            </tr>
                        `;
                    });
                } else {
                    html = '<tr><td colspan="3" class="text-center">No hay datos disponibles</td></tr>';
                }
                $('#chart-estadias tbody').html(html);
            }
        });
    </script>

@endsection

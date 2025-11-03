@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Dashboard Embarques
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="">Fecha de trabajo</label>
                                <div class='input-group mb-3'>
                                    <input type='text' id="daterange" name="daterange" class="form-control dateranges"/>
                                    <div class="input-group-append">
                                                        <span class="input-group-text">
                                                            <span class="ti-calendar"></span>
                                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col- col-md-6 col-sm-12 align-self-center">
                            <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                <button id="filterData"
                                        class="btn waves-effect waves-light btn-outline-info btn-sm">
                                    <img src="{{asset('/ico/filter.png')}}" alt="" class="img-16 ml-1">
                                    <span style="font-size: 11px;"> Aplicar Filtros</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="row align-items-stretch">
                        <div class="c-dashboardInfo col-lg-3 col-md-6">
                            <div class="wrap">
                                <h3 class="heading heading5 hind-font medium-font-weight c-dashboardInfo__title">Total de Embarques
                                    <svg
                                        class="MuiSvgIcon-root-19" focusable="false" viewBox="0 0 24 24"
                                        aria-hidden="true" role="presentation">
                                        <path fill="none" d="M0 0h24v24H0z"></path>
                                        <path
                                            d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z">
                                        </path>
                                    </svg>

                                </h3>
                                <span class="hind-font caption-12 c-dashboardInfo__count" id="totalembarques" style="font-size: 72px !important;"></span>
                            </div>
                        </div>
                        <div class="c-dashboardInfo col-lg-3 col-md-6">
                            <div class="wrap">
                                <h4 class="heading heading5 hind-font medium-font-weight c-dashboardInfo__title">
                                    Embarques con unidad
                                    <svg
                                        class="MuiSvgIcon-root-19" focusable="false" viewBox="0 0 24 24"
                                        aria-hidden="true" role="presentation">
                                        <path fill="none" d="M0 0h24v24H0z"></path>
                                        <path
                                            d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z">
                                        </path>
                                    </svg>
                                </h4>
                                <span class="hind-font caption-12 c-dashboardInfo__count" id="embarquesconunidad" style="font-size: 72px !important;">0</span>
                            </div>
                        </div>
                        <div class="c-dashboardInfo col-lg-3 col-md-6">
                            <div class="wrap">
                                <h4 class="heading heading5 hind-font medium-font-weight c-dashboardInfo__title">
                                    Tiempo de estadia promedio
                                    <svg
                                        class="MuiSvgIcon-root-19" focusable="false" viewBox="0 0 24 24"
                                        aria-hidden="true" role="presentation">
                                        <path fill="none" d="M0 0h24v24H0z"></path>
                                        <path
                                            d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z">
                                        </path>
                                    </svg>
                                </h4>
                                <span class="hind-font caption-12 c-dashboardInfo__count" id="estadia" style="font-size: 72px !important;">0</span>
                            </div>
                        </div>

                        <div class="c-dashboardInfo col-lg-3 col-md-6">
                            <div class="wrap">
                                <h4 class="heading heading5 hind-font medium-font-weight c-dashboardInfo__title">BackLog
                                    <svg
                                        class="MuiSvgIcon-root-19" focusable="false" viewBox="0 0 24 24"
                                        aria-hidden="true" role="presentation">
                                        <path fill="none" d="M0 0h24v24H0z"></path>
                                        <path
                                            d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z">
                                        </path>
                                    </svg>
                                </h4>
                                <span class="hind-font caption-12 c-dashboardInfo__count" id="totalbacklog" style="font-size: 72px !important;">0</span>
                            </div>
                        </div>
                    </div>

                    <div class="row align-items-stretch">
                        <div class="c-dashboardInfo col-lg-3 col-md-6">
                            <div class="wrap">
                                <figure class="highcharts-figure">
                                    <div id="container"></div>
                                </figure>
                            </div>
                        </div>
                        <div class="c-dashboardInfo col-lg-3 col-md-6">
                            <div class="wrap">
                                <figure class="highcharts-figure">
                                    <div id="container2"></div>
                                </figure>
                            </div>
                        </div>
                        <div class="c-dashboardInfo col-lg-3 col-md-6">
                            <div class="wrap">
                                <figure class="highcharts-figure">
                                    <div id="container3"></div>
                                </figure>
                            </div>
                        </div>

                        <div class="c-dashboardInfo col-lg-3 col-md-6">
                            <div class="wrap">
                                <figure class="highcharts-figure">
                                    <div id="container4"></div>
                                </figure>
                            </div>
                        </div>
                    </div>

                    <div class="row align-items-stretch">
                        <div class="c-dashboardInfo col-md-6">
                            <div class="wrap">
                                <table class="table table-bordered table-hover table-condensed table-vcenter js-dataTable-full">
                                    <thead>
                                    <tr>
                                        <th>Turno</th>
                                        <th>Capacidad</th>
                                        <th>Programados</th>
                                        <th>% de Uso</th>
                                        <th>Total de cajas</th>
                                        <th>Total de pallets</th>
                                        <th>Total de peso</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <div class="c-dashboardInfo  col-md-6">
                            <div class="wrap">
                                <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                                    <thead>
                                    <tr>
                                        <th>Turno</th>
                                        <th>Estatus</th>
                                        <th>Embarques</th>
                                        <th>Total de cajas</th>
                                        <th>Total de pallets</th>
                                        <th>Total de peso</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row align-items-stretch">
                        <div class="c-dashboardInfo col-md-12">
                            <div class="wrap">
                                <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                                    <thead>
                                    <tr>
                                        <th>Turno</th>
                                        <th>Embarque</th>
                                        <th>Linea de transporte</th>
                                        <th>Cliente</th>
                                        <th>Estatus de embarque</th>
                                        <th>Cita de carga</th>
                                        <th>Entrada de unidad</th>
                                        <th>Cita de entrega</th>
                                        <th>Maxima de salida</th>
                                    </tr>
                                    </thead>
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
    <link rel="stylesheet" href="{{asset('theme/assets/libs/daterangepicker/daterangepicker.css')}}">
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

    <script src="https://code.highcharts.com/modules/heatmap.js"></script>
    <script src="https://code.highcharts.com/modules/treemap.js"></script>

    <script src="{{asset('/theme/assets/libs/daterangepicker/daterangepicker.js')}}"></script>
    <script src="{{asset('/theme/assets/libs/moment/moment.js')}}"></script>

    <script>
        document.getElementById('filterData').addEventListener('click', function () {

            let from_date = $('#daterange').data('daterangepicker').startDate.format('YYYY-MM-DD');
            let to_date = $('#daterange').data('daterangepicker').endDate.format('YYYY-MM-DD');

            // var selectedValuesStatus = $('#statusid').select2('data').map(function (item) {
            //     return item.id;
            // });
            // var selectedValuesCarrier = $('#carrierid').select2('data').map(function (item) {
            //     return item.id;
            // });
            // var selectedValuesRason = $('#razonstatusid').select2('data').map(function (item) {
            //     return item.id;
            // });
            // var selectedValuesWarehouse = $('#warehouseid').select2('data').map(function (item) {
            //     return item.id;
            // });

            getTotalGeneral(from_date, to_date);
            // fetchDataFroCarrier(from_date, to_date, selectedValuesCarrier, selectedValuesStatus, selectedValuesRason,selectedValuesWarehouse);
            // fetchDataFroStatus(from_date, to_date, selectedValuesCarrier, selectedValuesStatus, selectedValuesRason,selectedValuesWarehouse);
            // getTotalUnits(from_date, to_date, selectedValuesCarrier, selectedValuesStatus, selectedValuesRason,selectedValuesWarehouse);
            // getForTypeTruck(from_date, to_date, selectedValuesCarrier, selectedValuesStatus, selectedValuesRason,selectedValuesWarehouse);
            // getTotalUnitForDays(from_date, to_date, selectedValuesCarrier, selectedValuesStatus, selectedValuesRason,selectedValuesWarehouse);
        });
    </script>

    <script>
        let start_date = moment().subtract(1, 'month').startOf('month');
        let end_date = moment().subtract(1, 'month').endOf('month');
        $('#daterange span').html(start_date.format('YYYY-MM-DD') + ' - ' + end_date.format('YYYY-MM-DD'));
        $('.dateranges').daterangepicker({
            ranges: {
                'Hoy': [moment(), moment()],
                'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Últimos 7 Dias': [moment().subtract(6, 'days'), moment()],
                'Últimos 30 Dias': [moment().subtract(29, 'days'), moment()],
                'Mes actual': [moment().startOf('month'), moment().endOf('month')],
                'Mes pasado': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                'Año actual': [moment().startOf('year'), moment().endOf('year')],
            }
        }, function (start_data, end_date) {
            $('#daterange span').html(start_date.format('YYYY-MM-DD') + ' - ' + end_date.format('YYYY-MM-DD'));
        });
    </script>
    <script>
        function getTotalGeneral(startDate, endDate) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '{{route('getDataDashboardShipments')}}',
                type: 'POST',
                dataType: 'json',
                data: {
                    startDate: startDate,
                    endDate: endDate,
                },
                success: function (response) {
                    console.log(response)
                    var totalEmbarques = response.totalshipments;
                    var totalFolios = response.totalfolios;

                    document.getElementById('totalembarques').innerText = totalEmbarques;
                    document.getElementById('embarquesconunidad').innerText = totalFolios;
                    // $('#embarquesconunidad').text(totalFolios);
                    // $('#estadia').text(estadia);
                    // $('#totalbacklog').text(totalbacklog);
                },
                error: function () {
                    console.error('Error al obtener los datos');
                }
            });
        }
    </script>
    <script>
        Highcharts.chart('container', {
            chart: {
                type: 'pie',
                custom: {},
                events: {
                    render() {
                        const chart = this,
                            series = chart.series[0];
                        let customLabel = chart.options.chart.custom.label;

                        if (!customLabel) {
                            customLabel = chart.options.chart.custom.label =
                                chart.renderer.label(
                                    'Total<br/>' +
                                    '<strong>2 877 820</strong>'
                                )
                                    .css({
                                        color: '#000',
                                        textAnchor: 'middle'
                                    })
                                    .add();
                        }

                        const x = series.center[0] + chart.plotLeft,
                            y = series.center[1] + chart.plotTop -
                                (customLabel.attr('height') / 2);

                        customLabel.attr({
                            x,
                            y
                        });
                        customLabel.css({
                            fontSize: `${series.center[2] / 12}px`
                        });
                    }
                }
            },
            accessibility: {
                point: {
                    valueSuffix: '%'
                }
            },
            title: {
                text: 'Estatus de arribo de unidad.'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.0f}%</b>'
            },
            legend: {
                enabled: false
            },
            plotOptions: {
                series: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    borderRadius: 8,
                    dataLabels: [{
                        enabled: true,
                        distance: 20,
                        format: '{point.name}'
                    }, {
                        enabled: true,
                        distance: -15,
                        format: '{point.percentage:.0f}%',
                        style: {
                            fontSize: '0.9em'
                        }
                    }],
                    showInLegend: true
                }
            },
            series: [{
                name: 'Registrations',
                colorByPoint: true,
                innerSize: '75%',


                // data: [{
                //     name: 'EV',
                //     y: 23.9
                // }, {
                //     name: 'Hybrids',
                //     y: 12.6
                // }, {
                //     name: 'Diesel',
                //     y: 37.0
                // }, {
                //     name: 'Petrol',
                //     y: 26.4
                // }]

            }]
        });
    </script>
{{--    <script>--}}
{{--        Highcharts.chart('container2', {--}}
{{--            chart: {--}}
{{--                type: 'pie'--}}
{{--            },--}}
{{--            title: {--}}
{{--                text: 'Estatus de despacho.'--}}
{{--            },--}}
{{--            accessibility: {--}}
{{--                announceNewData: {--}}
{{--                    enabled: true--}}
{{--                },--}}
{{--                point: {--}}
{{--                    valueSuffix: '%'--}}
{{--                }--}}
{{--            },--}}

{{--            plotOptions: {--}}
{{--                series: {--}}
{{--                    borderRadius: 5,--}}
{{--                    dataLabels: [{--}}
{{--                        enabled: true,--}}
{{--                        distance: 15,--}}
{{--                        format: '{point.name}'--}}
{{--                    }, {--}}
{{--                        enabled: true,--}}
{{--                        distance: '-30%',--}}
{{--                        filter: {--}}
{{--                            property: 'percentage',--}}
{{--                            operator: '>',--}}
{{--                            value: 5--}}
{{--                        },--}}
{{--                        format: '{point.y:.1f}%',--}}
{{--                        style: {--}}
{{--                            fontSize: '0.9em',--}}
{{--                            textOutline: 'none'--}}
{{--                        }--}}
{{--                    }]--}}
{{--                }--}}
{{--            },--}}

{{--            tooltip: {--}}
{{--                headerFormat: '<span style="font-size:11px">{series.name}</span><br>',--}}
{{--                pointFormat: '<span style="color:{point.color}">{point.name}</span>: ' +--}}
{{--                    '<b>{point.y:.2f}%</b> of total<br/>'--}}
{{--            },--}}

{{--            series: [--}}
{{--                {--}}
{{--                    name: 'Detalle',--}}
{{--                    colorByPoint: true,--}}
{{--                    data: [--}}
{{--                        {--}}
{{--                            name: 'Chrome',--}}
{{--                            y: 61.04,--}}
{{--                            drilldown: 'Chrome'--}}
{{--                        },--}}
{{--                        {--}}
{{--                            name: 'Safari',--}}
{{--                            y: 9.47,--}}
{{--                            drilldown: 'Safari'--}}
{{--                        },--}}
{{--                        {--}}
{{--                            name: 'Edge',--}}
{{--                            y: 9.32,--}}
{{--                            drilldown: 'Edge'--}}
{{--                        },--}}
{{--                        {--}}
{{--                            name: 'Firefox',--}}
{{--                            y: 8.15,--}}
{{--                            drilldown: 'Firefox'--}}
{{--                        },--}}
{{--                        {--}}
{{--                            name: 'Other',--}}
{{--                            y: 11.02,--}}
{{--                            drilldown: null--}}
{{--                        }--}}
{{--                    ]--}}
{{--                }--}}
{{--            ],--}}
{{--            drilldown: {--}}
{{--                series: [--}}
{{--                    {--}}
{{--                        name: 'Chrome',--}}
{{--                        id: 'Chrome',--}}
{{--                        data: [--}}
{{--                            [--}}
{{--                                'v97.0',--}}
{{--                                36.89--}}
{{--                            ],--}}
{{--                            [--}}
{{--                                'v96.0',--}}
{{--                                18.16--}}
{{--                            ],--}}
{{--                            [--}}
{{--                                'v95.0',--}}
{{--                                0.54--}}
{{--                            ],--}}
{{--                            [--}}
{{--                                'v94.0',--}}
{{--                                0.7--}}
{{--                            ],--}}
{{--                            [--}}
{{--                                'v93.0',--}}
{{--                                0.8--}}
{{--                            ],--}}
{{--                            [--}}
{{--                                'v92.0',--}}
{{--                                0.41--}}
{{--                            ],--}}
{{--                            [--}}
{{--                                'v91.0',--}}
{{--                                0.31--}}
{{--                            ],--}}
{{--                            [--}}
{{--                                'v90.0',--}}
{{--                                0.13--}}
{{--                            ],--}}
{{--                            [--}}
{{--                                'v89.0',--}}
{{--                                0.14--}}
{{--                            ],--}}
{{--                            [--}}
{{--                                'v88.0',--}}
{{--                                0.1--}}
{{--                            ],--}}
{{--                            [--}}
{{--                                'v87.0',--}}
{{--                                0.35--}}
{{--                            ],--}}
{{--                            [--}}
{{--                                'v86.0',--}}
{{--                                0.17--}}
{{--                            ],--}}
{{--                            [--}}
{{--                                'v85.0',--}}
{{--                                0.18--}}
{{--                            ],--}}
{{--                            [--}}
{{--                                'v84.0',--}}
{{--                                0.17--}}
{{--                            ],--}}
{{--                            [--}}
{{--                                'v83.0',--}}
{{--                                0.21--}}
{{--                            ],--}}
{{--                            [--}}
{{--                                'v81.0',--}}
{{--                                0.1--}}
{{--                            ],--}}
{{--                            [--}}
{{--                                'v80.0',--}}
{{--                                0.16--}}
{{--                            ],--}}
{{--                            [--}}
{{--                                'v79.0',--}}
{{--                                0.43--}}
{{--                            ],--}}
{{--                            [--}}
{{--                                'v78.0',--}}
{{--                                0.11--}}
{{--                            ],--}}
{{--                            [--}}
{{--                                'v76.0',--}}
{{--                                0.16--}}
{{--                            ],--}}
{{--                            [--}}
{{--                                'v75.0',--}}
{{--                                0.15--}}
{{--                            ],--}}
{{--                            [--}}
{{--                                'v72.0',--}}
{{--                                0.14--}}
{{--                            ],--}}
{{--                            [--}}
{{--                                'v70.0',--}}
{{--                                0.11--}}
{{--                            ],--}}
{{--                            [--}}
{{--                                'v69.0',--}}
{{--                                0.13--}}
{{--                            ],--}}
{{--                            [--}}
{{--                                'v56.0',--}}
{{--                                0.12--}}
{{--                            ],--}}
{{--                            [--}}
{{--                                'v49.0',--}}
{{--                                0.17--}}
{{--                            ]--}}
{{--                        ]--}}
{{--                    },--}}
{{--                    {--}}
{{--                        name: 'Safari',--}}
{{--                        id: 'Safari',--}}
{{--                        data: [--}}
{{--                            [--}}
{{--                                'v15.3',--}}
{{--                                0.1--}}
{{--                            ],--}}
{{--                            [--}}
{{--                                'v15.2',--}}
{{--                                2.01--}}
{{--                            ],--}}
{{--                            [--}}
{{--                                'v15.1',--}}
{{--                                2.29--}}
{{--                            ],--}}
{{--                            [--}}
{{--                                'v15.0',--}}
{{--                                0.49--}}
{{--                            ],--}}
{{--                            [--}}
{{--                                'v14.1',--}}
{{--                                2.48--}}
{{--                            ],--}}
{{--                            [--}}
{{--                                'v14.0',--}}
{{--                                0.64--}}
{{--                            ],--}}
{{--                            [--}}
{{--                                'v13.1',--}}
{{--                                1.17--}}
{{--                            ],--}}
{{--                            [--}}
{{--                                'v13.0',--}}
{{--                                0.13--}}
{{--                            ],--}}
{{--                            [--}}
{{--                                'v12.1',--}}
{{--                                0.16--}}
{{--                            ]--}}
{{--                        ]--}}
{{--                    },--}}
{{--                    {--}}
{{--                        name: 'Edge',--}}
{{--                        id: 'Edge',--}}
{{--                        data: [--}}
{{--                            [--}}
{{--                                'v97',--}}
{{--                                6.62--}}
{{--                            ],--}}
{{--                            [--}}
{{--                                'v96',--}}
{{--                                2.55--}}
{{--                            ],--}}
{{--                            [--}}
{{--                                'v95',--}}
{{--                                0.15--}}
{{--                            ]--}}
{{--                        ]--}}
{{--                    },--}}
{{--                    {--}}
{{--                        name: 'Firefox',--}}
{{--                        id: 'Firefox',--}}
{{--                        data: [--}}
{{--                            [--}}
{{--                                'v96.0',--}}
{{--                                4.17--}}
{{--                            ],--}}
{{--                            [--}}
{{--                                'v95.0',--}}
{{--                                3.33--}}
{{--                            ],--}}
{{--                            [--}}
{{--                                'v94.0',--}}
{{--                                0.11--}}
{{--                            ],--}}
{{--                            [--}}
{{--                                'v91.0',--}}
{{--                                0.23--}}
{{--                            ],--}}
{{--                            [--}}
{{--                                'v78.0',--}}
{{--                                0.16--}}
{{--                            ],--}}
{{--                            [--}}
{{--                                'v52.0',--}}
{{--                                0.15--}}
{{--                            ]--}}
{{--                        ]--}}
{{--                    }--}}
{{--                ]--}}
{{--            }--}}
{{--        });--}}

{{--    </script>--}}
{{--    <script>--}}
{{--        Highcharts.setOptions({--}}
{{--            colors: Highcharts.getOptions().colors.map(function (color) {--}}
{{--                return {--}}
{{--                    radialGradient: {--}}
{{--                        cx: 0.5,--}}
{{--                        cy: 0.3,--}}
{{--                        r: 0.7--}}
{{--                    },--}}
{{--                    stops: [--}}
{{--                        [0, color],--}}
{{--                        [1, Highcharts.color(color).brighten(-0.3).get('rgb')] // darken--}}
{{--                    ]--}}
{{--                };--}}
{{--            })--}}
{{--        });--}}

{{--        // Build the chart--}}
{{--        Highcharts.chart('container3', {--}}
{{--            chart: {--}}
{{--                plotBackgroundColor: null,--}}
{{--                plotBorderWidth: null,--}}
{{--                plotShadow: false,--}}
{{--                type: 'pie'--}}
{{--            },--}}
{{--            title: {--}}
{{--                text: 'Estatus de embarques.'--}}
{{--            },--}}
{{--            tooltip: {--}}
{{--                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'--}}
{{--            },--}}
{{--            accessibility: {--}}
{{--                point: {--}}
{{--                    valueSuffix: '%'--}}
{{--                }--}}
{{--            },--}}
{{--            plotOptions: {--}}
{{--                pie: {--}}
{{--                    allowPointSelect: true,--}}
{{--                    cursor: 'pointer',--}}
{{--                    dataLabels: {--}}
{{--                        enabled: true,--}}
{{--                        format: '<span style="font-size: 1.2em"><b>{point.name}</b>' +--}}
{{--                            '</span><br>' +--}}
{{--                            '<span style="opacity: 0.6">{point.percentage:.1f} ' +--}}
{{--                            '%</span>',--}}
{{--                        connectorColor: 'rgba(128,128,128,0.5)'--}}
{{--                    }--}}
{{--                }--}}
{{--            },--}}
{{--            series: [{--}}
{{--                name: 'Total',--}}
{{--                data: [--}}
{{--                    {name: 'Petrol', y: 938899},--}}
{{--                    {name: 'Diesel', y: 1229600},--}}
{{--                    {name: 'Electricity', y: 325251},--}}
{{--                    {name: 'Other', y: 238751}--}}
{{--                ]--}}
{{--            }]--}}
{{--        });--}}

{{--    </script>--}}
{{--    <script>--}}
{{--        Highcharts.chart('container4', {--}}
{{--            chart: {--}}
{{--                type: 'column'--}}
{{--            },--}}
{{--            title: {--}}
{{--                text: 'Detalle de clientes'--}}
{{--            },--}}

{{--            xAxis: {--}}
{{--                type: 'category',--}}
{{--                labels: {--}}
{{--                    autoRotation: [-45, -90],--}}
{{--                    style: {--}}
{{--                        fontSize: '13px',--}}
{{--                        fontFamily: 'Verdana, sans-serif'--}}
{{--                    }--}}
{{--                }--}}
{{--            },--}}
{{--            yAxis: {--}}
{{--                min: 0,--}}
{{--                title: {--}}
{{--                    text: 'Total de embarques.'--}}
{{--                }--}}
{{--            },--}}
{{--            legend: {--}}
{{--                enabled: false--}}
{{--            },--}}
{{--            tooltip: {--}}
{{--                pointFormat: 'Total: <b>{point.y:.1f}</b>'--}}
{{--            },--}}
{{--            series: [{--}}
{{--                name: 'Population',--}}
{{--                colors: [--}}
{{--                    '#9b20d9', '#9215ac', '#861ec9', '#7a17e6', '#7010f9', '#691af3',--}}
{{--                    '#6225ed', '#5b30e7', '#533be1', '#4c46db', '#4551d5', '#3e5ccf',--}}
{{--                    '#3667c9', '#2f72c3', '#277dbd', '#1f88b7', '#1693b1', '#0a9eaa',--}}
{{--                    '#03c69b', '#00f194'--}}
{{--                ],--}}
{{--                colorByPoint: true,--}}
{{--                groupPadding: 0,--}}
{{--                data: [--}}
{{--                    ['Tokyo', 37.33],--}}
{{--                    ['Delhi', 31.18],--}}
{{--                    ['Shanghai', 27.79],--}}
{{--                    ['Sao Paulo', 22.23],--}}
{{--                    ['Mexico City', 21.91],--}}
{{--                    ['Dhaka', 21.74],--}}
{{--                    ['Cairo', 21.32],--}}
{{--                    ['Beijing', 20.89],--}}
{{--                    ['Mumbai', 20.67],--}}
{{--                    ['Osaka', 19.11],--}}
{{--                    ['Karachi', 16.45],--}}
{{--                    ['Chongqing', 16.38],--}}
{{--                    ['Istanbul', 15.41],--}}
{{--                    ['Buenos Aires', 15.25],--}}
{{--                    ['Kolkata', 14.974],--}}
{{--                    ['Kinshasa', 14.970],--}}
{{--                    ['Lagos', 14.86],--}}
{{--                    ['Manila', 14.16],--}}
{{--                    ['Tianjin', 13.79],--}}
{{--                    ['Guangzhou', 13.64]--}}
{{--                ],--}}
{{--                dataLabels: {--}}
{{--                    enabled: true,--}}
{{--                    rotation: -90,--}}
{{--                    color: '#FFFFFF',--}}
{{--                    inside: true,--}}
{{--                    verticalAlign: 'top',--}}
{{--                    format: '{point.y:.1f}', // one decimal--}}
{{--                    y: 10, // 10 pixels down from the top--}}
{{--                    style: {--}}
{{--                        fontSize: '13px',--}}
{{--                        fontFamily: 'Verdana, sans-serif'--}}
{{--                    }--}}
{{--                }--}}
{{--            }]--}}
{{--        });--}}

{{--    </script>--}}

@endsection

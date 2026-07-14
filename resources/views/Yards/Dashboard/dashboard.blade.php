@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
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
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div id="categorydiv" class="">
                                <label>Linea de transporte</label>
                                <select name="carrierid" id="carrierid" class="form-control" multiple="multiple"></select>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div id="statusdiv" class="">
                                <label>Estatus</label>
                                <select name="statusid" id="statusid" class="form-control" multiple="multiple">
                                </select>
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
                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div id="razondiv" class="">
                                <label>Concepto de Entrada</label>
                                <select name="razonstatusid" id="razonstatusid" class="form-control"
                                        multiple="multiple">
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div id="warehosediv" class="">
                                <label>Bodega</label>
                                <select name="warehouseid" id="warehouseid" class="form-control"
                                        multiple="multiple">
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-3 col-md-6">
            <div class="card border-right border-info">
                <div class="card-body">
                    <div class="d-flex no-block align-items-center">
                        <div>
                            <span class="text-trax display-6"><i class="ti-pie-chart"></i></span>
                        </div>
                        <div class="ml-auto">
                            <h2 id="totalunit"></h2>
                            <h6 class="text-trax">Total de unidades</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card border-right border-info">
                <div class="card-body">
                    <div class="d-flex no-block align-items-center">
                        <div>
                            <span class="text-trax display-6"><i class="ti-pie-chart"></i></span>
                        </div>
                        <div class="ml-auto">
                            <h2 id="estadiatotal"></h2>
                            <h6 class="text-trax">Estadia promedio</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row ">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="card box-primary">
                                <div class="card-header">
                                    <h3 class="card-title text-trax" style="font-size: 12px;"> Total por tipo de
                                        entrada.</h3>
                                </div>
                                <div class="card-body no-padding">
                                    <figure class="highcharts-figure">
                                        <div id="ForType"></div>
                                    </figure>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="card box-primary">
                                <div class="card-header">
                                    <h3 class="card-title" style="font-size: 12px;"> Total por línea de transporte.</h3>
                                </div>
                                <div class="card-body no-padding">
                                    <figure class="highcharts-figure">
                                        <div id="ForCarrier"></div>
                                    </figure>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="card box-primary">
                                <div class="card-header">
                                    <h3 class="card-title text-trax" style="font-size: 12px;"> Total por estatus de proceso.</h3>
                                </div>
                                <div class="card-body no-padding">
                                    <figure class="highcharts-figure">
                                        <div id="ForStatus"></div>
                                    </figure>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-4">
                            <div class="card box-primary">
                                <div class="card-header">
                                    <h3 class="card-title text-trax" style="font-size: 12px;"> Total por tipo de unidad.</h3>
                                </div>
                                <div class="card-body no-padding">
                                    <figure class="highcharts-figure">
                                        <div id="ForTypeTruck"></div>
                                    </figure>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="card box-primary">
                                <div class="card-header">
                                    <h3 class="card-title text-trax" style="font-size: 12px;"> Total de registros por dia.</h3>
                                </div>
                                <div class="card-body no-padding">
                                    <figure class="highcharts-figure">
                                        <div id="getForTotalForDays"></div>
                                    </figure>
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

    <script src="{{asset('/theme/assets/libs/daterangepicker/daterangepicker.js')}}"></script>
    <script src="{{asset('/theme/assets/libs/moment/moment.js')}}"></script>

    @include('layouts.Selects.getCarriers')
    @include('layouts.Selects.getStatus')
    @include('layouts.Selects.getRasonCode')
    @include('layouts.Selects.getWarehouses')



    <script>
        // Función centralizada para cargar todas las gráficas
        function loadAllCharts(from_date, to_date, selectedValuesCarrier, selectedValuesStatus, selectedValuesRason, selectedValuesWarehouse) {
            fetchDataAndCreateChart(from_date, to_date, selectedValuesCarrier, selectedValuesStatus, selectedValuesRason, selectedValuesWarehouse);
            fetchDataFroCarrier(from_date, to_date, selectedValuesCarrier, selectedValuesStatus, selectedValuesRason, selectedValuesWarehouse);
            fetchDataFroStatus(from_date, to_date, selectedValuesCarrier, selectedValuesStatus, selectedValuesRason, selectedValuesWarehouse);
            getTotalUnits(from_date, to_date, selectedValuesCarrier, selectedValuesStatus, selectedValuesRason, selectedValuesWarehouse);
            getForTypeTruck(from_date, to_date, selectedValuesCarrier, selectedValuesStatus, selectedValuesRason, selectedValuesWarehouse);
            getTotalUnitForDays(from_date, to_date, selectedValuesCarrier, selectedValuesStatus, selectedValuesRason, selectedValuesWarehouse);
            getTotalEstadia(from_date, to_date, selectedValuesCarrier, selectedValuesStatus, selectedValuesRason, selectedValuesWarehouse);
        }

        // Evento del botón de filtros
        document.getElementById('filterData').addEventListener('click', function () {
            let from_date = $('#daterange').data('daterangepicker').startDate.format('YYYY-MM-DD');
            let to_date = $('#daterange').data('daterangepicker').endDate.format('YYYY-MM-DD');

            var selectedValuesStatus = $('#statusid').select2('data').map(function (item) {
                return item.id;
            });
            var selectedValuesCarrier = $('#carrierid').select2('data').map(function (item) {
                return item.id;
            });
            var selectedValuesRason = $('#razonstatusid').select2('data').map(function (item) {
                return item.id;
            });
            var selectedValuesWarehouse = $('#warehouseid').select2('data').map(function (item) {
                return item.id;
            });
            
            loadAllCharts(from_date, to_date, selectedValuesCarrier, selectedValuesStatus, selectedValuesRason, selectedValuesWarehouse);
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

        // Cargar gráficas al iniciar la página con el rango de fecha predeterminado
        $(document).ready(function() {
            // Dar tiempo a que se carguen los select2 (1 segundo)
            setTimeout(function() {
                let from_date = start_date.format('YYYY-MM-DD');
                let to_date = end_date.format('YYYY-MM-DD');
                
                console.log('Cargando gráficas iniciales con fechas:', from_date, 'a', to_date);
                
                // Cargar con filtros vacíos inicialmente
                loadAllCharts(from_date, to_date, [], [], [], []);
            }, 1000);
        });
    </script>
    <script>
        function getTotalUnits(startDate, endDate, carrierid, statusid, selectedValuesRason,selectedValuesWarehouse) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '{{route('getTotalUnits')}}',
                method: 'POST',
                data: {
                    startDate: startDate,
                    endDate: endDate,
                    carrierid: carrierid,
                    statusid: statusid,
                    razon: selectedValuesRason,
                    warehouseid : selectedValuesWarehouse
                },
                success: function (response) {
                    var data = JSON.parse(response)
                    document.getElementById('totalunit').innerText = data;
                },
                error: function(xhr, status, error) {
                    console.error('Error en getTotalUnits:', error);
                    document.getElementById('totalunit').innerText = '0';
                }
            });
        }
    </script>
    <script>
        function getTotalEstadia(startDate, endDate, carrierid, statusid, selectedValuesRason,selectedValuesWarehouse) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '{{route('getStadiaTotal')}}',
                method: 'POST',
                data: {
                    startDate: startDate,
                    endDate: endDate,
                    carrierid: carrierid,
                    statusid: statusid,
                    razon: selectedValuesRason,
                    warehouseid : selectedValuesWarehouse
                },
                success: function (response) {
                    var data = JSON.parse(response)
                    if (data && data.length > 0 && data[0].estadia) {
                        document.getElementById('estadiatotal').innerText = data[0].estadia;
                    } else {
                        document.getElementById('estadiatotal').innerText = '0 horas 0 minutos';
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error en getStadiaTotal:', error);
                    document.getElementById('estadiatotal').innerText = 'Error';
                }
            });
        }
    </script>
    <script>
        function getTotalUnitForDays(startDate, endDate, carrierid, statusid, selectedValuesRason,selectedValuesWarehouse) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '{{route('getTotalUnitForDays')}}',
                method: 'POST',
                data: {
                    startDate: startDate,
                    endDate: endDate,
                    carrierid: carrierid,
                    statusid: statusid,
                    razon: selectedValuesRason,
                    warehouseid:selectedValuesWarehouse
                },
                success: function (response) {
                    var data = JSON.parse(response)
                    var fechas = data.map(function (item) {
                        return item.fecha
                    });
                    var total = data.map(function (item) {
                        return parseInt(item.total)
                    });
                    Highcharts.chart('getForTotalForDays', {
                        colors: [
                            '#d0df00', '#63666a', '#b5bd00', '#97999b',
                            '#e3e935', '#d0d0ce', '#000000'
                        ],
                        chart: {
                            type: 'line'
                        },
                        title: {
                            text: ''
                        },
                        subtitle: {
                            text: ''
                        },
                        xAxis: {
                            categories: fechas
                        },
                        yAxis: {
                            title: {
                                text: 'Total de registros'
                            }
                        },
                        plotOptions: {
                            line: {
                                dataLabels: {
                                    enabled: true
                                },
                                enableMouseTracking: false
                            }
                        },
                        series: [{
                            name: 'Fecha de trabajo',
                            data: total
                        }]
                    });

                },
                error: function(xhr, status, error) {
                    console.error('Error en getTotalUnitForDays:', error);
                }
            });
        }
    </script>
    <script>
        function getForTypeTruck(startDate, endDate, carrierid, statusid, selectedValuesRason,selectedValuesWarehouse) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '{{route('getForTypeTruck')}}',
                method: 'POST',
                data: {
                    startDate: startDate,
                    endDate: endDate,
                    carrierid: carrierid,
                    statusid: statusid,
                    razon: selectedValuesRason,
                    warehouseid:selectedValuesWarehouse
                },
                success: function (response) {
                    var data = JSON.parse(response)
                    var tipe = data.map(function (item) {
                        return item.name
                    });
                    var total = data.map(function (item) {
                        return parseInt(item.total)
                    });
                    Highcharts.chart('ForTypeTruck', {
                        colors: [
                            '#d0df00', '#63666a', '#b5bd00', '#97999b',
                            '#e3e935', '#d0d0ce', '#000000'
                        ],
                        chart: {
                            type: 'column'
                        },
                        title: {
                            text: '',
                            align: 'left'
                        },
                        subtitle: {
                            text: '',
                            align: 'left'
                        },
                        xAxis: {
                            categories: tipe,
                            crosshair: true,
                            accessibility: {
                                description: ''
                            }
                        },
                        yAxis: {
                            min: 0,
                            title: {
                                text: 'Tipo'
                            }
                        },
                        tooltip: {
                            valueSuffix: ''
                        },
                        plotOptions: {
                            column: {
                                pointPadding: 0.2,
                                borderWidth: 0
                            }
                        },
                        series: [
                            {
                                name: 'Total por tipo',
                                data: total
                            },
                        ]
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error en getForTypeTruck:', error);
                }
            });
        }
    </script>


    <script>
        function fetchDataAndCreateChart(startDate, endDate, carrierid, statusid, selectedValuesRason,selectedValuesWarehouse) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '{{route('getForTypeForDasboardYard')}}',
                method: 'POST',
                data: {
                    startDate: startDate,
                    endDate: endDate,
                    carrierid: carrierid,
                    statusid: statusid,
                    razon: selectedValuesRason,
                    warehouseid:selectedValuesWarehouse
                },
                success: function (response) {
                    var data = JSON.parse(response)
                    var arrays = data.map(function (item) {
                        return [item.name, parseInt(item.total)]
                    });
                    Highcharts.chart('ForType', {
                        colors: [
                            '#d0df00', '#63666a', '#b5bd00', '#97999b',
                            '#e3e935', '#d0d0ce', '#000000'
                        ],
                        chart: {
                            type: 'pie',
                            options3d: {
                                enabled: true,
                                alpha: 45,
                                beta: 0
                            }
                        },
                        title: {
                            text: '',
                        },
                        accessibility: {
                            point: {
                                valueSuffix: '%'
                            }
                        },
                        tooltip: {
                            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                        },
                        plotOptions: {
                            pie: {
                                allowPointSelect: true,
                                cursor: 'pointer',
                                depth: 35,
                                dataLabels: {
                                    enabled: true,
                                    format: '{point.name}'
                                }
                            }
                        },
                        series: [{
                            type: 'pie',
                            name: 'Total',
                            data: arrays
                        }]
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error en fetchDataAndCreateChart (ForType):', error);
                }
            });
        }
    </script>
    <script>
        function fetchDataFroCarrier(startDate, endDate, carrierid, statusid, selectedValuesRason,selectedValuesWarehouse) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '{{route('getForCarrierForDasboardYard')}}',
                method: 'POST',
                data: {
                    startDate: startDate,
                    endDate: endDate,
                    carrierid: carrierid,
                    statusid: statusid,
                    razon: selectedValuesRason,
                    warehouseid:selectedValuesWarehouse
                },
                success: function (response) {
                    var data = JSON.parse(response)
                    var arrays = data.map(function (item) {
                        return [item.name, parseInt(item.total)]
                    });
                    Highcharts.chart('ForCarrier', {
                        colors: [
                            '#d0df00', '#63666a', '#b5bd00', '#97999b',
                            '#e3e935', '#d0d0ce', '#000000'
                        ],
                        chart: {
                            type: 'pie',
                            options3d: {
                                enabled: true,
                                alpha: 45,
                                beta: 0
                            }
                        },
                        title: {
                            text: '',
                        },
                        accessibility: {
                            point: {
                                valueSuffix: '%'
                            }
                        },
                        tooltip: {
                            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                        },
                        plotOptions: {
                            pie: {
                                allowPointSelect: true,
                                cursor: 'pointer',
                                depth: 35,
                                dataLabels: {
                                    enabled: true,
                                    format: '{point.name}'
                                }
                            }
                        },
                        series: [{
                            type: 'pie',
                            name: 'Total',
                            data: arrays
                        }]
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error en fetchDataFroCarrier (ForCarrier):', error);
                }
            });
        }
    </script>
    <script>
        function fetchDataFroStatus(startDate, endDate, carrierid, statusid, selectedValuesRason,selectedValuesWarehouse) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '{{route('getForStatusForDasboardYard')}}',
                method: 'POST',
                data: {
                    startDate: startDate,
                    endDate: endDate,
                    carrierid: carrierid,
                    statusid: statusid,
                    razon: selectedValuesRason,
                    warehouseid:selectedValuesWarehouse
                },
                success: function (response) {
                    var data = JSON.parse(response)
                    var arrays = data.map(function (item) {
                        return [item.name, parseInt(item.total)]
                    });

                    Highcharts.chart('ForStatus', {
                        colors: [
                            '#d0df00', '#63666a', '#b5bd00', '#97999b',
                            '#e3e935', '#d0d0ce', '#000000'
                        ],
                        chart: {
                            plotBackgroundColor: null,
                            plotBorderWidth: 0,
                            plotShadow: false
                        },
                        title: {
                            text: '',

                        },
                        tooltip: {
                            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>',
                        },
                        accessibility: {
                            point: {
                                valueSuffix: '%'
                            }
                        },
                        plotOptions: {
                            pie: {
                                dataLabels: {
                                    enabled: true,
                                    distance: -50,
                                    style: {
                                        fontWeight: 'bold',
                                        color: 'white'
                                    }
                                },
                                startAngle: -90,
                                endAngle: 90,
                                center: ['50%', '75%'],
                                size: '110%'
                            }
                        },
                        series: [{
                            type: 'pie',
                            name: 'Total por estatus',
                            innerSize: '50%',
                            data: arrays,
                        }]
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error en fetchDataFroStatus (ForStatus):', error);
                }
            });
        }
    </script>

@endsection

@extends('layouts.app')

@section('content')
    @foreach($centers as $center)
        @php
            $centerZones = $zonesByCenter->get($center->centerid, collect());
            $centerCapacity = $centerZones->sum('capacity');
            $centerBusy = $centerZones->sum(fn($zone) => $locationfill->get($zone->id, 0));
            $centerFree = max(0, $centerCapacity - $centerBusy);
            $centerPercent = $centerCapacity > 0 ? round((100 * $centerBusy) / $centerCapacity) : 0;
        @endphp

        <div class="d-flex justify-content-between align-items-center mb-3 mt-2">
            <h4 class="mb-0 capacity-title">Ocupación de patio — {{$center->description}}</h4>
            <span class="text-muted small">Umbral de saturación: {{$center->capacityyard}}%</span>
        </div>

        <div class="row mb-1">
            <div class="col-6 col-lg-3 mb-3">
                <div class="card kpi-card kpi-capacity h-100">
                    <div class="card-body">
                        <div class="kpi-label">Capacidad de patio</div>
                        <div class="kpi-value text-capacity">{{$centerCapacity}}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 mb-3">
                <div class="card kpi-card kpi-busy h-100">
                    <div class="card-body">
                        <div class="kpi-label">Unidades en patio</div>
                        <div class="kpi-value text-busy">{{$centerBusy}}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 mb-3">
                <div class="card kpi-card kpi-free h-100">
                    <div class="card-body">
                        <div class="kpi-label">Espacios libres</div>
                        <div class="kpi-value text-free">{{$centerFree}}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 mb-3">
                <div class="card kpi-card kpi-occupation h-100">
                    <div class="card-body">
                        <div class="kpi-label">Ocupación</div>
                        <div class="kpi-value text-occupation">{{$centerPercent}} %</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                            <div class="type-filters" data-center="{{$center->centerid}}">
                                <span class="type-chip active" data-filter="ALL">Todas</span>
                                <span class="type-chip" data-filter="ok">Disponibles</span>
                                <span class="type-chip" data-filter="full">Saturadas</span>
                            </div>
                            <div class="capacity-search-wrap">
                                <input type="text" class="form-control capacity-search"
                                       data-center="{{$center->centerid}}" placeholder="Buscar zona...">
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table width="100%" id="table-capacity-{{$center->centerid}}"
                                   class="table table-hover capacity-table mb-0">
                                <thead>
                                    <tr>
                                        <th>C. Distribución</th>
                                        <th>Zona</th>
                                        <th class="text-center">Capacidad</th>
                                        <th class="text-center">Ocupado</th>
                                        <th class="text-center">Libre</th>
                                        <th class="text-center">Estatus</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($centerZones as $zone)
                                    @php
                                        $ocupado = $locationfill->get($zone->id, 0);
                                        $libre = $zone->capacity > 0 ? max(0, $zone->capacity - $ocupado) : 0;
                                        $porcentaje = $zone->capacity > 0 ? round((100 / $zone->capacity) * $ocupado) : 0;
                                        $rowState = ($zone->capacity > 0 && $porcentaje > $center->capacityyard) ? 'full' : 'ok';
                                    @endphp
                                    <tr class="capacity-row" data-state="{{$rowState}}"
                                        data-zone="{{ strtolower($zone->name) }}">
                                        <td>{{$zone->center->description}}</td>
                                        <td><span class="zona-name">{{$zone->name}}</span></td>
                                        <td class="text-center text-capacity font-weight-bold">{{$zone->capacity}}</td>
                                        <td class="text-center text-busy font-weight-bold">{{$ocupado}}</td>
                                        <td class="text-center text-free font-weight-bold">{{$libre}}</td>
                                        <td class="text-center">
                                            @if($zone->capacity > 0)
                                                @if($porcentaje > $center->capacityyard)
                                                    <span class="badge-estatus badge-blocked">{{$porcentaje}}%</span>
                                                @else
                                                    <span class="badge-estatus badge-ok">{{$porcentaje}}%</span>
                                                @endif
                                            @else
                                                <span class="badge-estatus badge-idle">0%</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <hr class="my-4">

                        <div class="row">
                            @foreach($centerZones as $zone)
                                <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                                    <div class="card h-100">
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
    @endforeach
@endsection

@section('css')
    <style>
        /* Paleta de colores Traxión */
        :root {
            --traxion-primary: #CCFF00;      /* Pantone 389 C */
            --traxion-secondary: #B3D900;    /* Pantone 387 C */
            --traxion-accent: #8BC000;       /* Pantone 390 C */
            --traxion-dark: #2B2F33;         /* Gray oscuro */
            --traxion-gray-light: #E8E8E8;   /* Cool Gray 2 C */
            --traxion-gray-medium: #5A5A5A;  /* Cool Gray 7 C */
            --traxion-white: #FFFFFF;        /* Blanco */
            --traxion-black: #000000;        /* Negro */
        }

        .highcharts-credits {
            display: none !important;
        }

        /* Encabezado con acento de marca */
        .capacity-title {
            position: relative;
            padding-left: .75rem;
            font-weight: 700;
            color: var(--traxion-dark);
        }
        .capacity-title::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 5px;
            height: 1.4em;
            background: var(--traxion-primary);
            border-radius: 3px;
        }

        /* KPI cards modernizadas con paleta Traxión */
        .kpi-card .card-body {
            padding: 1.5rem;
        }
        .kpi-card {
            border: 0;
            border-radius: 8px;
            background: linear-gradient(135deg, var(--traxion-white) 0%, #f9f9f9 100%);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            position: relative;
            overflow: hidden;
        }
        .kpi-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--traxion-primary);
        }
        .kpi-card.kpi-capacity::before {
            background: var(--traxion-primary);
        }
        .kpi-card.kpi-busy::before {
            background: var(--traxion-secondary);
        }
        .kpi-card.kpi-free::before {
            background: var(--traxion-accent);
        }
        .kpi-card.kpi-occupation::before {
            background: var(--traxion-dark);
        }
        .kpi-card .kpi-label {
            font-size: .72rem;
            color: var(--traxion-gray-medium);
            text-transform: uppercase;
            letter-spacing: .03em;
            margin-bottom: .5rem;
            font-weight: 600;
        }
        .kpi-card .kpi-value {
            font-size: 2.5rem;
            font-weight: 700;
            line-height: 1;
            color: var(--traxion-dark);
        }
        /* Tonos legibles sobre fondo blanco (la paleta verde brillante se usa en barras/acentos) */
        .text-capacity {
            color: var(--traxion-dark);
        }
        .text-busy {
            color: var(--traxion-gray-medium);
        }
        .text-free {
            color: #6f8a00;
        }
        .text-occupation {
            color: var(--traxion-dark);
        }

        /* Chips de filtro */
        .type-filters {
            display: flex;
            flex-wrap: wrap;
            gap: .4rem;
        }
        .type-chip {
            display: inline-flex;
            align-items: center;
            padding: .35rem .9rem;
            border-radius: 999px;
            background: var(--traxion-gray-light);
            color: var(--traxion-dark);
            cursor: pointer;
            font-size: .82rem;
            font-weight: 600;
            border: 1px solid transparent;
            transition: all .15s ease;
        }
        .type-chip:hover {
            background: #d8d8d8;
        }
        .type-chip.active {
            background: var(--traxion-dark);
            color: var(--traxion-primary);
        }
        .capacity-search-wrap {
            min-width: 220px;
        }
        .capacity-search:focus {
            border-color: var(--traxion-accent);
            box-shadow: 0 0 0 .2rem rgba(139, 192, 0, 0.25);
        }

        /* Badges de estatus */
        .badge-estatus {
            display: inline-block;
            padding: .3rem .75rem;
            border-radius: 999px;
            font-size: .78rem;
            font-weight: 600;
            white-space: nowrap;
        }
        .badge-ok {
            background: #eef8c2;
            color: #556b00;
        }
        .badge-blocked {
            background: var(--traxion-dark);
            color: var(--traxion-primary);
        }
        .badge-idle {
            background: var(--traxion-gray-light);
            color: var(--traxion-gray-medium);
        }

        /* Tabla limpia estilo nuevo diseño */
        .zona-name {
            font-weight: 600;
            color: var(--traxion-dark);
        }
        .capacity-table thead th {
            font-size: .72rem;
            letter-spacing: .03em;
            color: var(--traxion-gray-medium);
            text-transform: uppercase;
            font-weight: 600;
            border: 0 !important;
            border-bottom: 2px solid var(--traxion-gray-light) !important;
        }
        .capacity-table td {
            vertical-align: middle;
            border: 0 !important;
            border-bottom: 1px solid var(--traxion-gray-light) !important;
        }
        .capacity-table tbody tr:hover {
            background-color: #faffe0;
        }
    </style>
@endsection

@section('script')
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
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
                $libre = $zone->capacity > 0 ? max(0, $zone->capacity - $ocupado) : 0;
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
                colors: ['#2B2F33', '#D0DF00'],
                title: {
                    text: '{{$zone->name}}',
                    style: {
                        fontSize: '14px',
                        fontWeight: 'bold',
                        color: '#2B2F33'
                    }
                },
                subtitle: {
                    text: 'Capacidad: {{$zone->capacity}} | Ocupación: {{$porcentaje}}%',
                    style: {
                        fontSize: '11px',
                        color: '#5A5A5A'
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
                        color: '#2B2F33',
                        sliced: {{$porcentaje > $center->capacityyard ? 'true' : 'false'}},
                        selected: {{$porcentaje > $center->capacityyard ? 'true' : 'false'}}
                    }, {
                        name: 'Libre',
                        y: {{$libre}},
                        color: '#D0DF00'
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
            @endforeach

            // Filtros de la tabla (chips + búsqueda por zona)
            function applyCapacityFilters(centerid) {
                var $chips = $('.type-filters[data-center="' + centerid + '"]');
                var state = $chips.find('.type-chip.active').data('filter');
                var term = ($('.capacity-search[data-center="' + centerid + '"]').val() || '').toLowerCase();
                var $rows = $('#table-capacity-' + centerid + ' tbody tr.capacity-row');
                var visible = 0;

                $rows.each(function () {
                    var $row = $(this);
                    var matchState = state === 'ALL' || $row.data('state') === state;
                    var matchTerm = ($row.data('zone') + '').indexOf(term) > -1;
                    var show = matchState && matchTerm;
                    $row.toggle(show);
                    if (show) {
                        visible++;
                    }
                });

                var $empty = $('#table-capacity-' + centerid + ' tbody tr.capacity-empty');
                if (visible === 0) {
                    if ($empty.length === 0) {
                        $('#table-capacity-' + centerid + ' tbody').append(
                            '<tr class="capacity-empty"><td colspan="6" class="text-center text-muted py-4">Sin resultados</td></tr>'
                        );
                    }
                } else {
                    $empty.remove();
                }
            }

            $('.type-filters').on('click', '.type-chip', function () {
                var centerid = $(this).closest('.type-filters').data('center');
                $(this).closest('.type-filters').find('.type-chip').removeClass('active');
                $(this).addClass('active');
                applyCapacityFilters(centerid);
            });

            $('.capacity-search').on('keyup', function () {
                applyCapacityFilters($(this).data('center'));
            });
        });
    </script>
@endsection

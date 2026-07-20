@extends('layouts.app')

@section('css')
    <style>
        .kpi-card .card-body {
            padding: 1rem 1.25rem;
        }
        .kpi-card .kpi-label {
            font-size: .78rem;
            color: #78909c;
            text-transform: uppercase;
            letter-spacing: .02em;
            margin-bottom: .35rem;
        }
        .kpi-card .kpi-value {
            font-size: 2rem;
            font-weight: 700;
            line-height: 1.1;
            color: #2b2f33;
        }
        .kpi-value.text-asignados {
            color: #1b7a4b;
        }
        .kpi-value.text-bloqueados {
            color: #c02a4d;
        }

        #type-filters {
            display: flex;
            flex-wrap: wrap;
            gap: .4rem;
        }
        .type-chip {
            display: inline-flex;
            align-items: center;
            padding: .35rem .9rem;
            border-radius: 999px;
            background: #f1f3f5;
            color: #37474f;
            cursor: pointer;
            font-size: .82rem;
            font-weight: 600;
            border: 1px solid transparent;
            transition: all .15s ease;
        }
        .type-chip:hover {
            background: #e4e7ea;
        }
        .type-chip.active {
            background: #2b2f33;
            color: #ffffff;
        }

        .badge-estatus {
            display: inline-block;
            padding: .3rem .75rem;
            border-radius: 999px;
            font-size: .78rem;
            font-weight: 600;
            white-space: nowrap;
        }
        .badge-ok {
            background: #d7f5e5;
            color: #1b7a4b;
        }
        .badge-warning {
            background: #aed581;
            color: #33691e;
        }
        .badge-blocked {
            background: #fbdfe6;
            color: #c02a4d;
        }
        .badge-idle {
            background: #eceff1;
            color: #607d8b;
        }

        .equipo-num {
            font-weight: 700;
            color: #2b2f33;
        }
        .equipo-serie {
            font-size: .78rem;
            color: #90a4ae;
        }

        .time-wrap {
            display: flex;
            align-items: center;
            gap: .6rem;
        }
        .time-track {
            position: relative;
            flex: 1 1 auto;
            max-width: 150px;
            min-width: 70px;
            height: 6px;
            background: #eceff1;
            border-radius: 4px;
            overflow: hidden;
        }
        .time-fill {
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            border-radius: 4px;
        }
        .time-fill.ok {
            background: #2e9e7b;
        }
        .time-fill.warning {
            background: #f0a13a;
        }
        .time-text {
            font-size: .82rem;
            color: #455a64;
            white-space: nowrap;
            min-width: 46px;
            text-align: right;
        }

        #table-lifttruck thead th {
            font-size: .72rem;
            letter-spacing: .03em;
            color: #90a4ae;
            text-transform: uppercase;
            font-weight: 600;
            border: 0 !important;
            border-bottom: 2px solid #eceff1 !important;
        }
        #table-lifttruck td {
            vertical-align: middle;
            border: 0 !important;
            border-bottom: 1px solid #eceff1 !important;
        }
    </style>
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Asignación de equipos</h4>
        <span class="text-muted small">Última actualización: <span id="lastupdate"></span></span>
    </div>

    <div class="row mb-1">
        <div class="col-6 col-lg-3 mb-3">
            <div class="card kpi-card h-100">
                <div class="card-body">
                    <div class="kpi-label">Total de equipos</div>
                    <div class="kpi-value" id="kpi-total">0</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3 mb-3">
            <div class="card kpi-card h-100">
                <div class="card-body">
                    <div class="kpi-label">Asignados</div>
                    <div class="kpi-value text-asignados" id="kpi-asignados">0</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3 mb-3">
            <div class="card kpi-card h-100">
                <div class="card-body">
                    <div class="kpi-label">Bloqueados</div>
                    <div class="kpi-value text-bloqueados" id="kpi-bloqueados">0</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3 mb-3">
            <div class="card kpi-card h-100">
                <div class="card-body">
                    <div class="kpi-label">Tiempo promedio asignado</div>
                    <div class="kpi-value" id="kpi-promedio">0 h</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div id="type-filters" class="mb-3"></div>

                    <div class="row mb-3">
                        <div class="col-lg-4 col-md-6 mb-2">
                            <input type="text" id="serialnumber" class="form-control"
                                   placeholder="Número de serie">
                        </div>
                        <div class="col-lg-4 col-md-6 mb-2">
                            <input type="text" id="numbertruck" class="form-control"
                                   placeholder="Número de equipo">
                        </div>
                        <div class="col-lg-4 col-md-6 mb-2">
                            <input type="text" id="userassigned" class="form-control"
                                   placeholder="Nombre del operador">
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table width="100%" id="table-lifttruck" class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Equipo</th>
                                    <th>Tipo</th>
                                    <th>Estatus</th>
                                    <th>Asignado a</th>
                                    <th>F. Asignación</th>
                                    <th>Tiempo transcurrido</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function () {
            // Referencia de horas para la barra (100%) y umbral de advertencia (ámbar).
            // Ajustables según la regla de negocio de tiempo de asignación.
            const MAX_HOURS = 12;
            const WARN_HOURS = 8;

            let allData = [];
            let activeType = 'ALL';
            let currentDate;

            function elapsedHours(startdate) {
                if (!startdate) {
                    return null;
                }
                let diff = new Date(currentDate) - new Date(startdate);
                if (isNaN(diff) || diff < 0) {
                    return null;
                }
                return diff / 3600000;
            }

            function stateFor(value, hours) {
                if (value.statusid === 'MT-B') {
                    return 'blocked';
                }
                if (value.statusid === 'MT-C') {
                    return (hours !== null && hours < WARN_HOURS) ? 'warning' : 'ok';
                }
                return 'idle';
            }

            function updateKpis() {
                let total = allData.length;
                let asignados = allData.filter(v => v.statusid === 'MT-C').length;
                let bloqueados = allData.filter(v => v.statusid === 'MT-B').length;
                let times = allData.map(v => elapsedHours(v.startdate)).filter(h => h !== null);
                let avg = times.length ? (times.reduce((a, b) => a + b, 0) / times.length) : 0;

                $('#kpi-total').text(total);
                $('#kpi-asignados').text(asignados);
                $('#kpi-bloqueados').text(bloqueados);
                $('#kpi-promedio').text(avg.toFixed(1) + ' h');
            }

            function renderChips() {
                let counts = {};
                allData.forEach(function (v) {
                    let type = v.typetruck || 'Sin tipo';
                    counts[type] = (counts[type] || 0) + 1;
                });

                let html = '<span class="type-chip ' + (activeType === 'ALL' ? 'active' : '') + '" data-type="ALL">Todos</span>';
                Object.keys(counts).forEach(function (type) {
                    html += '<span class="type-chip ' + (activeType === type ? 'active' : '') + '" data-type="' + type + '">' + type + ' (' + counts[type] + ')</span>';
                });
                $('#type-filters').html(html);
            }

            function renderTable(rows) {
                let html = '';
                rows.forEach(function (value) {
                    let hours = elapsedHours(value.startdate);
                    let state = stateFor(value, hours);
                    let badgeClass = {
                        ok: 'badge-ok',
                        warning: 'badge-warning',
                        blocked: 'badge-blocked',
                        idle: 'badge-idle'
                    }[state];

                    let userassigned = value.userassigned ? value.userassigned : '-';
                    let startdate = value.startdate ? value.startdate : '-';

                    let timeCell;
                    if (hours !== null) {
                        let width = Math.max(0, Math.min(100, (hours / MAX_HOURS) * 100));
                        let fillClass = state === 'warning' ? 'warning' : 'ok';
                        timeCell = '<div class="time-wrap">' +
                            '<span class="time-track"><span class="time-fill ' + fillClass + '" style="width:' + width + '%"></span></span>' +
                            '<span class="time-text">' + hours.toFixed(1).replace('.', ',') + ' h</span>' +
                            '</div>';
                    } else {
                        timeCell = '<div class="time-wrap">' +
                            '<span class="time-track"></span>' +
                            '<span class="time-text text-muted">—</span>' +
                            '</div>';
                    }

                    html += '<tr>';
                    html += '<td><div class="equipo-num">' + (value.numbertruck || '') + '</div>' +
                        '<div class="equipo-serie">' + (value.serialnumber || '') + '</div></td>';
                    html += '<td>' + (value.typetruck || '') + '</td>';
                    html += '<td><span class="badge-estatus ' + badgeClass + '">' + (value.statustruck || '') + '</span></td>';
                    html += '<td>' + userassigned + '</td>';
                    html += '<td>' + startdate + '</td>';
                    html += '<td>' + timeCell + '</td>';
                    html += '</tr>';
                });

                if (!html) {
                    html = '<tr><td colspan="6" class="text-center text-muted py-4">Sin resultados</td></tr>';
                }
                $('#table-lifttruck tbody').html(html);
            }

            function applyFilters() {
                let serie = $('#serialnumber').val().toLowerCase();
                let equipo = $('#numbertruck').val().toLowerCase();
                let operador = $('#userassigned').val().toLowerCase();

                let filtered = allData.filter(function (v) {
                    let matchType = activeType === 'ALL' || (v.typetruck || 'Sin tipo') === activeType;
                    let matchSerie = (v.serialnumber || '').toLowerCase().indexOf(serie) > -1;
                    let matchEquipo = (v.numbertruck || '').toLowerCase().indexOf(equipo) > -1;
                    let matchOperador = (v.userassigned || '').toLowerCase().indexOf(operador) > -1;
                    return matchType && matchSerie && matchEquipo && matchOperador;
                });
                renderTable(filtered);
            }

            function fetch_data() {
                $.ajax({
                    url: "{{url('getTruckAssigned')}}",
                    method: "GET",
                    success: function (data) {
                        currentDate = setDateInLabel();
                        allData = Array.isArray(data) ? data : [];
                        updateKpis();
                        renderChips();
                        applyFilters();
                    }
                });
            }

            $('#type-filters').on('click', '.type-chip', function () {
                activeType = $(this).data('type').toString();
                $('#type-filters .type-chip').removeClass('active');
                $(this).addClass('active');
                applyFilters();
            });

            $('#serialnumber, #numbertruck, #userassigned').on('keyup', function () {
                applyFilters();
            });

            fetch_data();
            setInterval(fetch_data, 60000);
        });

        function setDateInLabel() {
            let fechaActual = new Date();
            let fecha = fechaActual.getFullYear() + '-' + ("0" + (fechaActual.getMonth() + 1)).slice(-2) + '-' + ("0" + fechaActual.getDate()).slice(-2);
            let hora = ("0" + fechaActual.getHours()).slice(-2) + ":" + ("0" + fechaActual.getMinutes()).slice(-2) + ":" + ("0" + fechaActual.getSeconds()).slice(-2);
            let currentDate = fecha + ' ' + hora;
            document.getElementById('lastupdate').textContent = currentDate;
            return currentDate;
        }
    </script>
@endsection

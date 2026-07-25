@extends('layouts.app')

@section('css')
<style>
    /* ── Paleta Traxión ─────────────────────────────────────── */
    :root {
        --tx-primary : #D0DF00;
        --tx-dark    : #2B2F33;
        --tx-gray-md : #5A5A5A;
        --tx-gray-lt : #E8E8E8;
        --tx-white   : #FFFFFF;
    }

    /* ── Header ─────────────────────────────────────────────── */
    .monitor-title {
        font-weight: 700;
        color: var(--tx-dark);
        position: relative;
        padding-left: .75rem;
    }
    .monitor-title::before {
        content: '';
        position: absolute;
        left: 0; top: 50%;
        transform: translateY(-50%);
        width: 4px; height: 1.4em;
        background: var(--tx-primary);
        border-radius: 3px;
    }

    /* ── Toggle buttons ─────────────────────────────────────── */
    .btn-view-group { display: flex; gap: .4rem; }
    .btn-view {
        display: inline-flex; align-items: center; gap: .4rem;
        padding: .45rem .9rem;
        border-radius: 6px;
        font-size: .82rem; font-weight: 600;
        border: 1.5px solid var(--tx-gray-lt);
        background: var(--tx-white);
        color: var(--tx-gray-md);
        cursor: pointer;
        transition: all .15s ease;
    }
    .btn-view:hover { background: var(--tx-gray-lt); }
    .btn-view.active {
        background: var(--tx-dark);
        color: var(--tx-primary);
        border-color: var(--tx-dark);
    }
    .btn-export {
        display: inline-flex; align-items: center; gap: .4rem;
        padding: .45rem .9rem;
        border-radius: 6px;
        font-size: .82rem; font-weight: 600;
        background: #1565C0;
        color: #fff;
        border: none;
        cursor: pointer;
        transition: background .15s ease;
    }
    .btn-export:hover { background: #0d47a1; }

    /* ── Filter card ─────────────────────────────────────────── */
    .filter-card {
        border-radius: 8px;
        border: 1px solid var(--tx-gray-lt);
        box-shadow: 0 1px 4px rgba(0,0,0,.05);
    }
    .filter-card .form-control {
        border-radius: 6px;
        border-color: var(--tx-gray-lt);
        font-size: .84rem;
    }
    .filter-card .form-control:focus {
        border-color: var(--tx-dark);
        box-shadow: 0 0 0 .15rem rgba(43,47,51,.15);
    }
    .btn-search {
        padding: .45rem .85rem;
        background: var(--tx-dark);
        color: var(--tx-primary);
        border: none;
        border-radius: 6px;
        font-size: .82rem; font-weight: 600;
        cursor: pointer;
        transition: background .15s;
    }
    .btn-search:hover { background: #1a1e22; }
    .btn-clear {
        padding: .45rem .75rem;
        background: var(--tx-white);
        color: var(--tx-gray-md);
        border: 1.5px solid var(--tx-gray-lt);
        border-radius: 6px;
        font-size: .82rem; font-weight: 600;
        cursor: pointer;
    }
    .btn-clear:hover { background: var(--tx-gray-lt); }

    /* ── KPI mini-cards ─────────────────────────────────────── */
    .kpi-mini {
        border-radius: 10px;
        padding: 1.1rem 1.25rem;
        border: 1px solid transparent;
    }
    .kpi-mini .kpi-lbl {
        font-size: .72rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .03em;
        margin-bottom: .4rem;
    }
    .kpi-mini .kpi-val {
        font-size: 2.2rem; font-weight: 700; line-height: 1;
    }
    .kpi-total   { background: #fff; border-color: var(--tx-gray-lt); }
    .kpi-total   .kpi-lbl { color: var(--tx-gray-md); }
    .kpi-total   .kpi-val { color: var(--tx-dark); }

    .kpi-critical{ background: #FEE2E2; border-color: #FCA5A5; }
    .kpi-critical .kpi-lbl { color: #EF4444; }
    .kpi-critical .kpi-val { color: #DC2626; }

    .kpi-nokey   { background: #FEF3C7; border-color: #FCD34D; }
    .kpi-nokey   .kpi-lbl { color: #D97706; }
    .kpi-nokey   .kpi-val { color: #B45309; }

    .kpi-released{ background: #D1FAE5; border-color: #6EE7B7; }
    .kpi-released .kpi-lbl { color: #059669; }
    .kpi-released .kpi-val { color: #047857; }

    /* ── Stay badges ─────────────────────────────────────────── */
    .stay-badge {
        display: inline-block;
        padding: .28rem .75rem;
        border-radius: 999px;
        font-size: .76rem; font-weight: 600;
        white-space: nowrap;
    }
    .stay-red    { background: #FEE2E2; color: #DC2626; }
    .stay-orange { background: #FFEDD5; color: #EA580C; }
    .stay-yellow { background: #FEF9C3; color: #A16207; }
    .stay-green  { background: #D1FAE5; color: #059669; }

    /* ── Table view ─────────────────────────────────────────── */
    #view-table .card {
        border-radius: 8px;
        border: 1px solid var(--tx-gray-lt);
        box-shadow: 0 1px 4px rgba(0,0,0,.05);
    }
    .monitor-table thead th {
        font-size: .70rem;
        letter-spacing: .03em;
        color: var(--tx-gray-md);
        text-transform: uppercase;
        font-weight: 700;
        border: 0 !important;
        border-bottom: 2px solid var(--tx-gray-lt) !important;
        background: #fff;
        vertical-align: middle;
    }
    .monitor-table td {
        vertical-align: middle;
        border: 0 !important;
        border-bottom: 1px solid var(--tx-gray-lt) !important;
        font-size: .83rem;
    }
    .monitor-table tbody tr:hover { background: #f7f8e8; }
    .folio-num { font-weight: 700; color: var(--tx-dark); }
    .cell-dash { color: #bbb; }

    /* ── Card view ───────────────────────────────────────────── */
    .unit-card {
        border-radius: 10px;
        border: 1px solid var(--tx-gray-lt);
        box-shadow: 0 1px 4px rgba(0,0,0,.06);
        margin-bottom: .9rem;
        background: #fff;
        padding: 1rem 1.25rem;
    }
    .unit-card-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: .9rem;
        flex-wrap: wrap;
        gap: .5rem;
    }
    .unit-folio { font-weight: 700; font-size: .95rem; color: var(--tx-dark); }
    .unit-sub   { font-size: .78rem; color: var(--tx-gray-md); margin-top: .15rem; }

    /* Timeline */
    .unit-timeline {
        display: flex;
        align-items: flex-start;
        width: 100%;
    }
    .tl-step {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        min-width: 0;
    }
    /* connector line (right half of current step + left half of next) */
    .tl-step:not(:last-child)::after {
        content: '';
        position: absolute;
        left: 50%; top: 4px;
        width: 100%;
        height: 2px;
        background: var(--tx-gray-lt);
        z-index: 0;
    }
    .tl-step.done:not(:last-child)::after { background: var(--tx-dark); }
    .tl-dot {
        width: 10px; height: 10px;
        border-radius: 50%;
        background: var(--tx-gray-lt);
        position: relative; z-index: 1;
        margin-bottom: 5px;
        flex-shrink: 0;
    }
    .tl-step.done .tl-dot { background: var(--tx-dark); }
    .tl-name {
        font-size: .68rem; color: var(--tx-gray-md);
        text-align: center; line-height: 1.2;
        white-space: nowrap; overflow: hidden;
        text-overflow: ellipsis; max-width: 90%;
    }
    .tl-date {
        font-size: .68rem; color: var(--tx-dark);
        text-align: center; font-weight: 600;
        white-space: nowrap;
    }

    /* Cards pagination */
    #cards-pagination .page-item.active .page-link {
        background: var(--tx-dark);
        border-color: var(--tx-dark);
        color: var(--tx-primary);
    }
    #cards-pagination .page-link { color: var(--tx-dark); }

    /* ── DataTables pager override ───────────────────────────── */
    .dataTables_wrapper .dataTables_paginate .paginate_button.current,
    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
        background: var(--tx-dark) !important;
        color: var(--tx-primary) !important;
        border-color: var(--tx-dark) !important;
        border-radius: 4px;
    }
    .dataTables_wrapper .dataTables_info { font-size: .78rem; color: var(--tx-gray-md); }
    .dataTables_wrapper .dataTables_paginate { font-size: .82rem; }
    .dt-buttons { display: none; } /* hide DT export toolbar; triggered via header button */
</style>
@endsection

@section('content')

{{-- ── HEADER ──────────────────────────────────────────────────── --}}
<div class="d-flex justify-content-between align-items-start mb-3">
    <div>
        <h4 class="monitor-title mb-1">Monitor de cajas en patio</h4>
        <p class="text-muted small mb-0" id="monitor-subtitle">Cargando…</p>
    </div>
    <div class="btn-view-group flex-wrap">
        <button id="btn-cards" class="btn-view" onclick="setView('cards')">
            <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                <path d="M1 2h6v6H1zm8 0h6v6H9zM1 10h6v6H1zm8 0h6v6H9z"/>
            </svg>
            Tarjetas
        </button>
        <button id="btn-table" class="btn-view active" onclick="setView('table')">
            <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                <path d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm3 1v2h10V3H3zm0 3v2h10V6H3zm0 3v2h10V9H3zm0 3v2h10v-2H3z"/>
            </svg>
            Tabla
        </button>
        <button id="btn-export" class="btn-export" onclick="exportData()">
            <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                <path d="M8 0a8 8 0 1 1 0 16A8 8 0 0 1 8 0zm-.5 4v4.793L5.354 6.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 8.793V4a.5.5 0 0 0-1 0z"/>
            </svg>
            Exportar
        </button>
    </div>
</div>

{{-- ── FILTERS ─────────────────────────────────────────────────── --}}
<div class="card filter-card mb-3">
    <div class="card-body py-3">
        <div class="row align-items-center" style="row-gap:.5rem;">
            <div class="col-lg-4 col-md-12">
                <input type="text" id="search-text" class="form-control"
                       placeholder="Buscar por folio, placa o embarque"
                       onkeyup="if(event.key==='Enter') applyFilters()">
            </div>
            <div class="col-lg-2 col-md-4">
                <select id="filter-razon" class="form-control">
                    <option value="">Todos los estatus</option>
                    @foreach($razon as $r)
                        <option value="{{ $r->id }}">{{ $r->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2 col-md-4">
                <select id="filter-stay" class="form-control">
                    <option value="">Estadía: cualquiera</option>
                    <option value="15">Más de 15 días</option>
                    <option value="30">Más de 30 días</option>
                    <option value="60">Más de 60 días</option>
                    <option value="90">Más de 90 días</option>
                </select>
            </div>
            <div class="col-lg-2 col-md-4">
                <input type="date" id="filter-date" class="form-control" placeholder="dd/mm/aaaa">
            </div>
            <div class="col-lg-2 col-md-12 d-flex" style="gap:.4rem;">
                <button class="btn-search flex-grow-1" onclick="applyFilters()">
                    <svg width="12" height="12" fill="currentColor" viewBox="0 0 16 16" style="margin-right:3px;">
                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.099zm-5.242 1.156a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11z"/>
                    </svg>
                    Buscar
                </button>
                <button class="btn-clear" onclick="clearFilters()">
                    <svg width="12" height="12" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M11.354 4.646a.5.5 0 0 1 0 .708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 1 1 .708-.708L8 7.293l2.646-2.647a.5.5 0 0 1 .708 0z"/>
                    </svg>
                    Limpiar
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ── KPI CARDS ────────────────────────────────────────────────── --}}
<div class="row mb-3">
    <div class="col-6 col-lg-3 mb-2">
        <div class="kpi-mini kpi-total h-100">
            <div class="kpi-lbl">Unidades en patio</div>
            <div class="kpi-val" id="kpi-total">—</div>
        </div>
    </div>
    <div class="col-6 col-lg-3 mb-2">
        <div class="kpi-mini kpi-critical h-100">
            <div class="kpi-lbl">Estadía crítica (&gt;30 días)</div>
            <div class="kpi-val" id="kpi-critical">—</div>
        </div>
    </div>
    <div class="col-6 col-lg-3 mb-2">
        <div class="kpi-mini kpi-nokey h-100">
            <div class="kpi-lbl">Sin registro de llaves</div>
            <div class="kpi-val" id="kpi-nokey">—</div>
        </div>
    </div>
    <div class="col-6 col-lg-3 mb-2">
        <div class="kpi-mini kpi-released h-100">
            <div class="kpi-lbl">Liberadas hoy</div>
            <div class="kpi-val" id="kpi-released">—</div>
        </div>
    </div>
</div>

{{-- ── TABLE VIEW ───────────────────────────────────────────────── --}}
<div id="view-table">
    <div class="card">
        <div class="card-body p-3">
            <div class="table-responsive">
                <table id="table-monitorbox" class="table monitor-table mb-0">
                    <thead>
                        <tr>
                            <th>Folio</th>
                            <th>Línea de transporte</th>
                            <th>Placa de caja</th>
                            <th>Concepto</th>
                            <th>Llegada</th>
                            <th>Mesa Control</th>
                            <th>Enrampe</th>
                            <th>Llaves</th>
                            <th>Inicia Proceso</th>
                            <th>Termina Proceso</th>
                            <th>Liberación</th>
                            <th>Estadía total</th>
                        </tr>
                    </thead>
                    {{-- DataTables renders thead hidden cols too, visible:false hides them --}}
                </table>
            </div>
        </div>
    </div>
</div>

{{-- ── CARD VIEW (hidden by default) ───────────────────────────── --}}
<div id="view-cards" style="display:none;">
    <div id="cards-container">
        <div class="text-center text-muted py-5">Cargando tarjetas…</div>
    </div>
    <nav id="cards-pagination-wrap" class="mt-2" style="display:none;">
        <ul class="pagination justify-content-center" id="cards-pagination"></ul>
    </nav>
</div>

@endsection

@section('script')
{{-- Variables globales de filtro — deben estar en scope global para que
     el callback ajax.data del DataTable (partials/table.blade.php) las lea. --}}
<script>
var filterSearchText = '';
var filterRazon      = '';
var filterStayDays   = '';
var filterEntryDate  = '';
</script>
@include('Yards.MonitorUnits.partials.table')
<script>
$(document).ready(function () {

    /* ── State ──────────────────────────────────────────────────────── */
    var currentView = 'table';
    var lastUpdated = null;

    /* Page state for card view */
    var cardsPage   = 0;
    var cardsLength = 25;

    /* fmtDateTime, stayDays, stayBadgeClass son globales definidas en partials/table.blade.php */

    function formatLastUpdated() {
        if (!lastUpdated) { return ''; }
        var diff = Math.floor((new Date() - lastUpdated) / 60000);
        if (diff < 1)  { return 'actualizado hace unos segundos'; }
        if (diff === 1){ return 'actualizado hace 1 min'; }
        return 'actualizado hace ' + diff + ' min';
    }

    /* ── KPIs ───────────────────────────────────────────── */
    function loadKpis() {
        $.ajax({
            url: '{{ route("getMonitorKpis") }}',
            success: function (r) {
                $('#kpi-total').text(r.total);
                $('#kpi-critical').text(r.critical_stay);
                $('#kpi-nokey').text(r.no_key);
                $('#kpi-released').text(r.released_today);
                lastUpdated = new Date();
                updateSubtitle(r.total);
            }
        });
    }

    function updateSubtitle(total) {
        $('#monitor-subtitle').text(total + ' unidades activas · ' + formatLastUpdated());
    }

    /* ── View toggle ────────────────────────────────────── */
    window.setView = function (view) {
        currentView = view;
        if (view === 'table') {
            $('#view-table').show();
            $('#view-cards').hide();
            $('#btn-table').addClass('active');
            $('#btn-cards').removeClass('active');
            /* Recalculate columns in case table was hidden on init */
            oTable.columns.adjust().draw(false);
        } else {
            $('#view-table').hide();
            $('#view-cards').show();
            $('#btn-cards').addClass('active');
            $('#btn-table').removeClass('active');
            cardsPage = 0;
            loadCards();
        }
    };

    /* ── Filters ────────────────────────────────────────── */
    window.applyFilters = function () {
        filterSearchText = $('#search-text').val().trim();
        filterRazon      = $('#filter-razon').val();
        filterStayDays   = $('#filter-stay').val();
        filterEntryDate  = $('#filter-date').val();

        if (currentView === 'table') {
            oTable.ajax.reload();
        } else {
            cardsPage = 0;
            loadCards();
        }
        loadKpis();
    };

    window.clearFilters = function () {
        $('#search-text').val('');
        $('#filter-razon').val('');
        $('#filter-stay').val('');
        $('#filter-date').val('');
        filterSearchText = filterRazon = filterStayDays = filterEntryDate = '';
        if (currentView === 'table') {
            oTable.ajax.reload();
        } else {
            cardsPage = 0;
            loadCards();
        }
        loadKpis();
    };

    /* ── Export ──────────────────────────────────────────────────────── */
    window.exportData = function () {
        /* El botón Excel está en el índice 0 del array buttons del DataTable */
        oTable.button(0).trigger();
    };

    /* ── Card rendering ──────────────────────────────────── */
    function loadCards() {
        $('#cards-container').html(
            '<div class="text-center text-muted py-5"><span class="fa fa-spinner fa-spin mr-2"></span>Cargando tarjetas…</div>'
        );
        $.ajax({
            url: '{{ route("getDataStockBox") }}',
            data: {
                draw: 1,
                start: cardsPage * cardsLength,
                length: cardsLength,
                search_text : filterSearchText,
                razonstatusid: filterRazon,
                stay_days   : filterStayDays,
                entry_date  : filterEntryDate,
            },
            success: function (r) {
                renderCards(r.data, r.recordsFiltered, r.recordsTotal);
                lastUpdated = new Date();
                updateSubtitle(r.recordsTotal);
            }
        });
    }

    function renderCards(rows, total, grandTotal) {
        if (!rows || rows.length === 0) {
            $('#cards-container').html(
                '<div class="text-center text-muted py-5">Sin resultados para los filtros seleccionados</div>'
            );
            $('#cards-pagination-wrap').hide();
            return;
        }

        var html = '';
        rows.forEach(function (u) {
            var days = stayDays(u.entrydate);
            var badgeCls = stayBadgeClass(days);
            var stayTxt = u.staytime || (days + ' días');

            var steps = [
                { name: 'Llegada',       val: u.entrydate },
                { name: 'Mesa control',  val: u.deliverdate },
                { name: 'Enrampe',       val: u.ensnare },
                { name: 'Llaves',        val: u.keydate },
                { name: 'Proceso',       val: u.startdate },
                { name: 'Liberación',    val: u.autorizaoutputdate },
            ];

            var timelineHtml = '';
            steps.forEach(function (s) {
                var done = !!s.val;
                timelineHtml +=
                    '<div class="tl-step ' + (done ? 'done' : '') + '">' +
                        '<div class="tl-dot"></div>' +
                        '<div class="tl-name">' + s.name + '</div>' +
                        '<div class="tl-date">' + fmtDateTime(s.val) + '</div>' +
                    '</div>';
            });

            var carrierName = (u.carrier && u.carrier.name) ? u.carrier.name : '—';
            var eventName   = (u.event   && u.event.name)   ? u.event.name   : '—';
            var boxPart     = u.boxnumber ? ' · <span style="color:var(--tx-gray-md);font-weight:400">' + u.boxnumber + '</span>' : '';

            html +=
                '<div class="unit-card">' +
                    '<div class="unit-card-top">' +
                        '<div>' +
                            '<div class="unit-folio">Folio ' + u.id + boxPart + '</div>' +
                            '<div class="unit-sub">' + carrierName + ' · ' + eventName + '</div>' +
                        '</div>' +
                        '<span class="stay-badge ' + badgeCls + '">' + stayTxt + ' en patio</span>' +
                    '</div>' +
                    '<div class="unit-timeline">' + timelineHtml + '</div>' +
                '</div>';
        });

        $('#cards-container').html(html);

        /* Pagination */
        var totalPages = Math.ceil(total / cardsLength);
        if (totalPages > 1) {
            var pagHtml = '';
            if (cardsPage > 0) {
                pagHtml += '<li class="page-item"><a class="page-link" href="#" data-page="' + (cardsPage - 1) + '">‹</a></li>';
            }
            for (var p = 0; p < totalPages; p++) {
                pagHtml += '<li class="page-item ' + (p === cardsPage ? 'active' : '') + '">' +
                    '<a class="page-link" href="#" data-page="' + p + '">' + (p + 1) + '</a></li>';
            }
            if (cardsPage < totalPages - 1) {
                pagHtml += '<li class="page-item"><a class="page-link" href="#" data-page="' + (cardsPage + 1) + '">›</a></li>';
            }
            $('#cards-pagination').html(pagHtml);
            $('#cards-pagination-wrap').show();
        } else {
            $('#cards-pagination-wrap').hide();
        }
    }

    $('#cards-pagination').on('click', 'a.page-link', function (e) {
        e.preventDefault();
        cardsPage = parseInt($(this).data('page'));
        loadCards();
        window.scrollTo(0, 0);
    });

    /* ── Boot ───────────────────────────────────────────── */
    loadKpis();
    /* Refresh KPIs every 2 minutes */
    setInterval(loadKpis, 120000);
});
</script>
@endsection

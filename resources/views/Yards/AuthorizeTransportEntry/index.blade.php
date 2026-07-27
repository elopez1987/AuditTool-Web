@extends('layouts.app')

@section('css')
<style>
    /* ── Paleta Traxín ────────────────────────────────── */
    :root {
        --tx-dark    : #2B2F33;
        --tx-gray-md : #6B7280;
        --tx-gray-lt : #E5E7EB;
        --tx-white   : #FFFFFF;
        --blue       : #2563EB;
        --blue-lt    : #EFF6FF;
    }

    /* ── Barra de filtros ─────────────────────────────── */
    .pnl-filter-card {
        border-radius: 10px;
        border: 1px solid var(--tx-gray-lt);
        box-shadow: 0 1px 4px rgba(0,0,0,.06);
    }
    .pnl-filter-label {
        font-size: .72rem; font-weight: 600;
        color: var(--tx-gray-md);
        text-transform: uppercase; letter-spacing: .04em;
        display: block; margin-bottom: .2rem;
    }
    .pnl-filter-card .form-control {
        border-radius: 6px;
        border: 1px solid var(--tx-gray-lt);
        font-size: .84rem;
    }
    .pnl-filter-card .form-control:focus {
        border-color: #93C5FD;
        box-shadow: 0 0 0 3px rgba(59,130,246,.12);
    }
    .pnl-btn-clear {
        display: inline-flex; align-items: center; gap: .35rem;
        padding: .45rem .85rem; border-radius: 6px;
        border: 1.5px solid var(--tx-gray-lt);
        background: var(--tx-white); color: var(--tx-gray-md);
        font-size: .82rem; font-weight: 600;
        cursor: pointer; white-space: nowrap; transition: all .12s;
    }
    .pnl-btn-clear:hover { background: var(--tx-gray-lt); }
    .pnl-btn-search {
        display: inline-flex; align-items: center; gap: .35rem;
        padding: .45rem .9rem; border-radius: 6px;
        border: none; background: var(--blue); color: #fff;
        font-size: .82rem; font-weight: 600;
        cursor: pointer; white-space: nowrap; transition: background .12s;
    }
    .pnl-btn-search:hover { background: #1d4ed8; }
    .pnl-btn-reload {
        display: inline-flex; align-items: center; gap: .35rem;
        padding: .45rem .85rem; border-radius: 6px;
        border: 1.5px solid var(--tx-gray-lt);
        background: var(--tx-white); color: var(--tx-gray-md);
        font-size: .82rem; font-weight: 600;
        cursor: pointer; white-space: nowrap; transition: all .12s;
    }
    .pnl-btn-reload:hover { background: var(--tx-gray-lt); }
    .pnl-sort-select {
        width: auto; min-width: 230px;
        border-radius: 6px; border: 1px solid var(--tx-gray-lt);
        font-size: .82rem; color: var(--tx-dark); padding: .35rem .75rem;
    }

    /* ── Cards ─────────────────────────────────────────── */
    .ship-card {
        background: var(--tx-white);
        border-radius: 10px;
        border-top   : 1px solid var(--tx-gray-lt);
        border-right : 1px solid var(--tx-gray-lt);
        border-bottom: 1px solid var(--tx-gray-lt);
        border-left  : 5px solid var(--blue);
        box-shadow   : 0 1px 4px rgba(0,0,0,.07);
        padding      : 1rem 1.1rem;
        height       : 100%;
        display      : flex; flex-direction: column; gap: .65rem;
    }
    .ship-card-top {
        display: flex; justify-content: space-between;
        align-items: flex-start; gap: .5rem;
    }
    .ship-title { font-size: .95rem; color: var(--tx-dark); margin-bottom: .15rem; }
    .ship-title .ship-em { font-weight: 400; }
    .ship-title .ship-id { font-weight: 700; margin-left: .25rem; }
    .ship-sub { font-size: .78rem; color: var(--tx-gray-md); }

    /* Badge de concepto */
    .concept-badge {
        display: inline-block; padding: .25rem .7rem;
        border-radius: 999px;
        background: var(--blue-lt); color: var(--blue);
        font-size: .74rem; font-weight: 600;
        white-space: nowrap; flex-shrink: 0;
    }

    /* Grid de metadatos 2 columnas */
    .ship-meta {
        display: grid; grid-template-columns: 1fr 1fr; gap: .6rem;
    }
    .meta-label {
        font-size: .64rem; font-weight: 700;
        letter-spacing: .06em; text-transform: uppercase;
        color: var(--tx-gray-md); margin-bottom: .15rem;
    }
    .meta-val { font-size: .85rem; font-weight: 700; color: var(--tx-dark); }

    /* Fila de extras (sello, barcode, origen) */
    .ship-extras {
        display: flex; flex-wrap: wrap; gap: .25rem .75rem;
        font-size: .78rem; color: var(--tx-gray-md);
        background: #F9FAFB; border-radius: 6px;
        padding: .45rem .6rem;
    }
    .ship-extras .ext-lbl { font-weight: 700; text-transform: uppercase; font-size: .63rem; letter-spacing:.04em; }
    .ship-extras .ext-val { color: var(--tx-dark); font-weight: 600; margin-left: .2rem; }

    /* Bloque de fecha */
    .ship-dates {
        display: flex; background: #F3F4F6;
        border-radius: 8px; overflow: hidden;
    }
    .date-cell { flex: 1; padding: .5rem .75rem; }
    .date-lbl {
        font-size: .63rem; font-weight: 700;
        letter-spacing: .06em; text-transform: uppercase;
        color: var(--tx-gray-md); margin-bottom: .15rem;
    }
    .date-val { font-size: .84rem; font-weight: 600; color: var(--tx-dark); }

    /* Footer con botones */
    .ship-footer {
        display: flex; justify-content: flex-end;
        align-items: center; margin-top: auto;
        gap: .35rem; flex-wrap: wrap;
    }
    /* Estilos básicos para los botones de acción del btn server-side */
    .ship-footer a {
        display: inline-flex; align-items: center;
        padding: .35rem .6rem;
        border-radius: 6px;
        text-decoration: none;
        transition: background .12s;
    }
    .ship-footer a:hover { background: var(--tx-gray-lt); }
    .ship-footer img { width: 18px; height: 18px; }

    /* Título */
    .ate-title {
        font-weight: 700; color: var(--tx-dark);
        position: relative; padding-left: .75rem;
    }
    .ate-title::before {
        content: ''; position: absolute; left: 0; top: 50%;
        transform: translateY(-50%);
        width: 4px; height: 1.4em;
        background: var(--blue); border-radius: 3px;
    }

    /* Estado vacío */
    .pnl-empty {
        text-align: center; padding: 3rem 1rem;
        color: var(--tx-gray-md); font-size: .9rem;
    }
</style>
@endsection

@section('content')

{{-- ── HEADER ────────────────────────────────────── --}}
<div class="d-flex justify-content-between align-items-start mb-3">
    <div>
        <h4 class="ate-title mb-1">Pendientes de check list de entrada</h4>
        <p class="text-muted small mb-0">Unidades en espera de autorización de ingreso al patio</p>
    </div>
</div>

{{-- ── BARRA DE FILTROS ────────────────────────────── --}}
<div class="card pnl-filter-card mb-3">
    <div class="card-body py-3">
        <div class="row align-items-end" style="row-gap:.5rem;">
            <div class="col-lg-3 col-md-12">
                <label class="pnl-filter-label">Buscar</label>
                <input type="text" class="form-control" id="filterText"
                       placeholder="Folio, placa, barcode u operador…">
            </div>
            <div class="col-lg-2 col-md-6">
                <label class="pnl-filter-label">Línea de transporte</label>
                <select id="filterCarrier" class="form-control">
                    <option value="">Todas</option>
                </select>
            </div>
            <div class="col-lg-2 col-md-6">
                <label class="pnl-filter-label">Concepto de entrada</label>
                <select id="filterReason" class="form-control">
                    <option value="">Todos</option>
                </select>
            </div>
            <div class="col-lg-2 col-md-6">
                <label class="pnl-filter-label">C. Distribución</label>
                <select id="filterCenter" class="form-control">
                    <option value="">Todas</option>
                </select>
            </div>
            <div class="col-lg-3 col-md-12 d-flex" style="gap:.4rem; flex-wrap:wrap;">
                <button class="pnl-btn-clear" id="clearBtn">
                    <svg width="13" height="13" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                        <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                    </svg>
                    Limpiar filtros
                </button>
                <button class="pnl-btn-search" id="filterBtn">
                    <svg width="13" height="13" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398l3.85 3.85a1 1 0 0 0 1.415-1.414zm-5.242 1.156a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11"/>
                    </svg>
                    Buscar
                </button>
                <button class="pnl-btn-reload" id="reloaddata">
                    <svg width="13" height="13" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2z"/>
                        <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466"/>
                    </svg>
                    Recargar
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ── CONTROLES SECUNDARIOS ──────────────────────────── --}}
<div class="d-flex justify-content-between align-items-center mb-3">
    <p class="text-muted small mb-0">
        Última actualización: <span id="lastupdate">—</span>
        &nbsp;&bull;&nbsp;
        <span id="result-count" class="text-muted"></span>
    </p>
    <select id="sortSelect" class="form-control pnl-sort-select">
        <option value="date-desc">Ordenar: más reciente primero</option>
        <option value="date-asc">Ordenar: más antiguo primero</option>
        <option value="folio-asc">Ordenar: folio A → Z</option>
    </select>
</div>

{{-- ── CARDS ──────────────────────────────────────────── --}}
<div id="cards-container">
    <div class="pnl-empty">Cargando datos…</div>
</div>

@endsection

@section('script')
<script>
(function () {
    /* ── Estado global ──────────────────────── */
    var allData  = [];
    var sortMode = 'date-desc';

    /* ── Helpers ──────────────────────────── */
    function esc(str) {
        if (str == null) { return ''; }
        return String(str)
            .replace(/&/g, '&amp;').replace(/</g, '&lt;')
            .replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    /* "YYYY-MM-DD HH:mm:ss" → "DD/MM/YYYY HH:mm" */
    function fmtDate(val) {
        if (!val) { return '—'; }
        var s = String(val).replace(' ', 'T');
        try {
            var d = new Date(s);
            if (isNaN(d.getTime())) { return esc(val); }
            var z = function (n) { return n < 10 ? '0' + n : n; };
            return z(d.getDate()) + '/' + z(d.getMonth() + 1) + '/' + d.getFullYear() +
                   ' ' + z(d.getHours()) + ':' + z(d.getMinutes());
        } catch (e) { return esc(val); }
    }

    /* Convierte entrydate a timestamp numérico para ordenar */
    function dateTs(val) {
        if (!val) { return 0; }
        try { return new Date(String(val).replace(' ', 'T')).getTime(); } catch (e) { return 0; }
    }

    function setLastUpdated() {
        var now = new Date();
        var z   = function (n) { return n < 10 ? '0' + n : n; };
        document.getElementById('lastupdate').textContent =
            now.getFullYear() + '-' + z(now.getMonth() + 1) + '-' + z(now.getDate()) +
            ' ' + z(now.getHours()) + ':' + z(now.getMinutes()) + ':' + z(now.getSeconds());
    }

    /* ── Poblar dropdowns ─────────────────────── */
    function populateDropdowns() {
        var prev = {
            carrier: $('#filterCarrier').val(),
            reason : $('#filterReason').val(),
            center : $('#filterCenter').val(),
        };
        fillSelect('#filterCarrier',
            uniq(allData.map(function (d) { return d.carrier && d.carrier.name; })),
            prev.carrier);
        fillSelect('#filterReason',
            uniq(allData.map(function (d) { return d.reason && d.reason.name; })),
            prev.reason);
        fillSelect('#filterCenter',
            uniq(allData.map(function (d) { return d.center && d.center.description; })),
            prev.center);
    }

    function uniq(arr) {
        return [...new Set(arr.filter(Boolean))].sort();
    }

    function fillSelect(sel, values, selected) {
        var $s = $(sel).empty().append('<option value="">Todas / Todos</option>');
        values.forEach(function (v) {
            $s.append('<option value="' + esc(v) + '"' +
                (selected === v ? ' selected' : '') + '>' + esc(v) + '</option>');
        });
    }

    /* ── Filtros ────────────────────────────── */
    function applyFilters() {
        var term    = ($('#filterText').val()     || '').toLowerCase().trim();
        var carrier = $('#filterCarrier').val()   || '';
        var reason  = $('#filterReason').val()    || '';
        var center  = $('#filterCenter').val()    || '';

        var filtered = allData.filter(function (d) {
            var cn  = (d.carrier && d.carrier.name)     || '';
            var rs  = (d.reason  && d.reason.name)      || '';
            var ct  = (d.center  && d.center.description) || '';
            var op  = (d.tracto  && d.tracto.operatorname) || '';
            var trk = (d.tracto  && d.tracto.trucknumber) || '';

            var matchText = !term ||
                String(d.id        || '').indexOf(term) > -1 ||
                (d.boxnumber || '').toLowerCase().indexOf(term) > -1 ||
                (d.barcode   || '').toLowerCase().indexOf(term) > -1 ||
                (d.containerseal || '').toLowerCase().indexOf(term) > -1 ||
                op.toLowerCase().indexOf(term) > -1 ||
                cn.toLowerCase().indexOf(term) > -1 ||
                trk.toLowerCase().indexOf(term) > -1;

            var matchCarrier = !carrier || cn === carrier;
            var matchReason  = !reason  || rs === reason;
            var matchCenter  = !center  || ct === center;

            return matchText && matchCarrier && matchReason && matchCenter;
        });

        /* Ordenar */
        filtered.sort(function (a, b) {
            var tA = dateTs(a.entrydate);
            var tB = dateTs(b.entrydate);
            if (sortMode === 'date-desc') { return tB - tA; }
            if (sortMode === 'date-asc')  { return tA - tB; }
            /* folio-asc */
            return (a.id || 0) - (b.id || 0);
        });

        renderCards(filtered);
    }

    /* ── Renderizar cards ──────────────────────── */
    function renderCards(data) {
        $('#result-count').text(data.length + ' unidad' + (data.length !== 1 ? 'es' : ''));

        if (!data || data.length === 0) {
            $('#cards-container').html(
                '<div class="pnl-empty">No hay unidades pendientes con los filtros seleccionados</div>'
            );
            return;
        }

        var html = '<div class="row">';
        data.forEach(function (d) {
            var carrierName = (d.carrier && d.carrier.name)          || '—';
            var operName    = (d.tracto  && d.tracto.operatorname)   || 'Sin operador';
            var operPhone   = (d.tracto  && d.tracto.phone)          || '';
            var truckNum    = (d.tracto  && d.tracto.trucknumber)    || '—';
            var centerDesc  = (d.center  && d.center.description)    || '—';
            var warehName   = (d.warehouse && d.warehouse.name)      || '—';
            var reasonName  = (d.reason  && d.reason.name)           || '—';
            var unitDesc    = (d.unittype && d.unittype.description)  || '—';

            /* Sub-título */
            var sub = esc(carrierName) + ' &middot; ' + esc(operName);
            if (operPhone) { sub += ' &middot; ' + esc(operPhone); }

            html +=
                '<div class="col-lg-3 col-md-6 col-sm-12 mb-3">' +
                  '<div class="ship-card">' +

                    /* Encabezado */
                    '<div class="ship-card-top">' +
                      '<div class="flex-grow-1">' +
                        '<div class="ship-title">' +
                          '<span class="ship-em">Folio</span>' +
                          '<span class="ship-id">' + esc(d.id) + '</span>' +
                        '</div>' +
                        '<div class="ship-sub">' + sub + '</div>' +
                      '</div>' +
                      '<span class="concept-badge">' + esc(reasonName) + '</span>' +
                    '</div>' +

                    /* Meta 2x2: Centro / Bodega / Placa caja / Placa tracto */
                    '<div class="ship-meta">' +
                      '<div><div class="meta-label">C. Distribución</div>' +
                            '<div class="meta-val">' + esc(centerDesc) + '</div></div>' +
                      '<div><div class="meta-label">Bodega</div>' +
                            '<div class="meta-val">' + esc(warehName) + '</div></div>' +
                      '<div><div class="meta-label">Placa de caja</div>' +
                            '<div class="meta-val">' + esc(d.boxnumber || '—') + '</div></div>' +
                      '<div><div class="meta-label">Placa de tracto</div>' +
                            '<div class="meta-val">' + esc(truckNum) + '</div></div>' +
                    '</div>' +

                    /* Extras: sello, barcode, origen */
                    '<div class="ship-extras">' +
                      '<span><span class="ext-lbl">Sello</span>' +
                            '<span class="ext-val">' + esc(d.containerseal || '—') + '</span></span>' +
                      '<span>&middot;</span>' +
                      '<span><span class="ext-lbl">C. Barra</span>' +
                            '<span class="ext-val">' + esc(d.barcode || '—') + '</span></span>' +
                      '<span>&middot;</span>' +
                      '<span><span class="ext-lbl">Origen</span>' +
                            '<span class="ext-val">' + esc(unitDesc) + '</span></span>' +
                    '</div>' +

                    /* Fecha de registro */
                    '<div class="ship-dates">' +
                      '<div class="date-cell">' +
                        '<div class="date-lbl">Fecha de registro</div>' +
                        '<div class="date-val">' + fmtDate(d.entrydate) + '</div>' +
                      '</div>' +
                    '</div>' +

                    /* Footer con botones de acción (HTML renderizado por el servidor) */
                    '<div class="ship-footer">' +
                      (d.btn || '') +
                    '</div>' +

                  '</div>' +
                '</div>';
        });
        html += '</div>';
        $('#cards-container').html(html);
    }

    /* ── Carga de datos ────────────────────────── */
    function fetchData() {
        $.ajax({
            url   : '{{ url("getDataTransportES") }}',
            method: 'GET',
            data  : { draw: 1, start: 0, length: 1000 },
            success: function (resp) {
                allData = (resp && resp.data) ? resp.data : [];
                setLastUpdated();
                populateDropdowns();
                applyFilters();
            },
            error: function () {
                $('#cards-container').html(
                    '<div class="pnl-empty">Error al cargar los datos. Intente recargar.</div>'
                );
            }
        });
    }

    /* ── Eventos ─────────────────────────────── */
    $('#filterBtn').on('click', applyFilters);
    $('#filterText').on('keyup', function (e) {
        if (e.key === 'Enter') { applyFilters(); }
    });
    $('#filterCarrier, #filterReason, #filterCenter').on('change', applyFilters);
    $('#sortSelect').on('change', function () {
        sortMode = $(this).val(); applyFilters();
    });
    $('#clearBtn').on('click', function () {
        $('#filterText').val('');
        $('#filterCarrier, #filterReason, #filterCenter').val('');
        applyFilters();
    });
    $('#reloaddata').on('click', function () {
        $('#cards-container').html('<div class="pnl-empty">Actualizando…</div>');
        fetchData();
    });

    /* ── Arranque + auto-recarga cada 3 min ────────── */
    fetchData();
    setInterval(fetchData, 180000);
}());
</script>
@endsection

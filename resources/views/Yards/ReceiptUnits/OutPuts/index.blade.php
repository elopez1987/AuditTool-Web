@extends('layouts.app')

@section('css')
<style>
    /* ── Paleta Traxín ────────────────────────────────── */
    :root {
        --tx-dark    : #2B2F33;
        --tx-gray-md : #6B7280;
        --tx-gray-lt : #E5E7EB;
        --tx-white   : #FFFFFF;
        --amber      : #F59E0B;
        --amber-lt   : #FEF3C7;
        --orange     : #EA580C;
        --orange-lt  : #FFEDD5;
        --red        : #EF4444;
        --red-lt     : #FEE2E2;
        --green      : #16A34A;
        --green-lt   : #D1FAE5;
        --blue-link  : #2563EB;
    }

    /* ── Barra de filtros ────────────────────────────── */
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
        border: none; background: #2563EB; color: #fff;
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

    /* ── Cards de caja autorizada ─────────────────────────── */
    .ship-card {
        background: var(--tx-white);
        border-radius: 10px;
        border-top   : 1px solid var(--tx-gray-lt);
        border-right : 1px solid var(--tx-gray-lt);
        border-bottom: 1px solid var(--tx-gray-lt);
        border-left  : 5px solid var(--amber);
        box-shadow   : 0 1px 4px rgba(0,0,0,.07);
        padding      : 1rem 1.1rem;
        height       : 100%;
        display      : flex; flex-direction: column; gap: .7rem;
    }
    /* border rojo cuando lleva más de 2 días */
    .ship-card.urgent { border-left-color: var(--red); }

    .ship-card-top {
        display: flex; justify-content: space-between;
        align-items: flex-start; gap: .5rem;
    }
    .ship-title { font-size: .95rem; color: var(--tx-dark); margin-bottom: .15rem; }
    .ship-title .ship-em { font-weight: 400; }
    .ship-title .ship-id { font-weight: 700; margin-left: .25rem; }
    .ship-sub   { font-size: .78rem; color: var(--tx-gray-md); }

    /* Badge de tiempo de espera */
    .wait-badge {
        display: inline-block; padding: .25rem .7rem;
        border-radius: 999px; font-size: .76rem; font-weight: 600;
        white-space: nowrap; flex-shrink: 0;
    }
    .wait-green  { background: var(--green-lt);  color: var(--green); }
    .wait-amber  { background: var(--amber-lt);  color: #92400E; }
    .wait-orange { background: var(--orange-lt); color: var(--orange); }
    .wait-red    { background: var(--red-lt);    color: var(--red); }

    /* Meta grid */
    .ship-meta {
        display: grid; grid-template-columns: 1fr 1fr; gap: .75rem;
    }
    .meta-label {
        font-size: .66rem; font-weight: 700;
        letter-spacing: .06em; text-transform: uppercase;
        color: var(--tx-gray-md); margin-bottom: .18rem;
    }
    .meta-val { font-size: .88rem; font-weight: 700; color: var(--tx-dark); }

    /* Fechas */
    .ship-dates {
        display: flex; background: #F3F4F6;
        border-radius: 8px; overflow: hidden;
    }
    .date-cell   { flex: 1; padding: .55rem .75rem; }
    .date-div    { width: 1px; background: #D1D5DB; margin: .35rem 0; }
    .date-lbl {
        font-size: .65rem; font-weight: 700;
        letter-spacing: .06em; text-transform: uppercase;
        color: var(--tx-gray-md); margin-bottom: .18rem;
    }
    .date-val    { font-size: .84rem; font-weight: 600; color: var(--tx-dark); }
    .date-output { color: var(--amber); }

    /* Footer */
    .ship-footer {
        display: flex; justify-content: space-between;
        align-items: center; margin-top: auto;
    }
    .status-pill {
        display: inline-block; padding: .25rem .75rem;
        border-radius: 999px; background: #F3F4F6; color: #374151;
        font-size: .7rem; font-weight: 700;
        letter-spacing: .05em; text-transform: uppercase;
    }
    .ship-detail-link {
        font-size: .82rem; color: var(--blue-link);
        font-weight: 500; text-decoration: none;
    }
    .ship-detail-link:hover { text-decoration: underline; color: #1d4ed8; }

    /* Estado vacío */
    .pnl-empty {
        text-align: center; padding: 3rem 1rem;
        color: var(--tx-gray-md); font-size: .9rem;
    }

    /* Título de sección */
    .out-title {
        font-weight: 700; color: var(--tx-dark);
        position: relative; padding-left: .75rem;
    }
    .out-title::before {
        content: ''; position: absolute; left: 0; top: 50%;
        transform: translateY(-50%);
        width: 4px; height: 1.4em;
        background: var(--amber); border-radius: 3px;
    }
</style>
@endsection

@section('content')

{{-- ── HEADER ──────────────────────────────────────── --}}
<div class="d-flex justify-content-between align-items-start mb-3">
    <div>
        <h4 class="out-title mb-1">Cajas autorizadas para salida</h4>
        <p class="text-muted small mb-0">Unidades con salida aprobada pendientes de retiro</p>
    </div>
</div>

{{-- ── BARRA DE FILTROS ─────────────────────────────── --}}
<div class="card pnl-filter-card mb-3">
    <div class="card-body py-3">
        <div class="row align-items-end" style="row-gap:.5rem;">
            <div class="col-lg-3 col-md-12">
                <label class="pnl-filter-label">Buscar</label>
                <input type="text" class="form-control" id="filterText"
                       placeholder="Folio, placa, embarque o cód. de barra…">
            </div>
            <div class="col-lg-2 col-md-6">
                <label class="pnl-filter-label">Línea de transporte</label>
                <select id="filterCarrier" class="form-control">
                    <option value="">Todas</option>
                </select>
            </div>
            <div class="col-lg-2 col-md-6">
                <label class="pnl-filter-label">Concepto de entrada</label>
                <select id="filterConcept" class="form-control">
                    <option value="">Todos</option>
                </select>
            </div>
            <div class="col-lg-2 col-md-6">
                <label class="pnl-filter-label">Bodega</label>
                <select id="filterWarehouse" class="form-control">
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

{{-- ── CONTROLES SECUNDARIOS ───────────────────────────── --}}
<div class="d-flex justify-content-between align-items-center mb-3">
    <p class="text-muted small mb-0">
        Última actualización: <span id="lastupdate">—</span>
        &nbsp;&bull;&nbsp;
        <span id="result-count" class="text-muted"></span>
    </p>
    <select id="sortSelect" class="form-control pnl-sort-select">
        <option value="wait-desc">Ordenar: mayor espera primero</option>
        <option value="wait-asc">Ordenar: menor espera primero</option>
        <option value="folio-asc">Ordenar: folio A → Z</option>
    </select>
</div>

{{-- ── CARDS ─────────────────────────────────────────── --}}
<div id="cards-container">
    <div class="pnl-empty">Cargando datos…</div>
</div>

@endsection

@section('script')
<script>
(function () {
    /* ── Estado global ─────────────────────────── */
    var allData   = [];
    var sortMode  = 'wait-desc';
    var detailBase = '{{ url("tracereceiptunit") }}/';

    /* ── Helpers ───────────────────────────── */
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

    /* Días transcurridos desde autorizaoutputdate */
    function waitDays(val) {
        if (!val) { return 0; }
        var s = String(val).replace(' ', 'T');
        return Math.max(0, Math.floor((new Date() - new Date(s)) / 86400000));
    }

    /* Parse el campo "stays" generado por el servidor ("X d HH:MM h/m") */
    function staysDays(staysStr) {
        if (!staysStr) { return 0; }
        var m = staysStr.match(/(\d+)\s*d/);
        return m ? parseInt(m[1]) : 0;
    }

    function waitBadgeClass(days) {
        if (days > 2) { return 'wait-red'; }
        if (days > 1) { return 'wait-orange'; }
        if (days > 0) { return 'wait-amber'; }
        return 'wait-green';
    }

    function setLastUpdated() {
        var now = new Date();
        var z   = function (n) { return n < 10 ? '0' + n : n; };
        document.getElementById('lastupdate').textContent =
            now.getFullYear() + '-' + z(now.getMonth() + 1) + '-' + z(now.getDate()) +
            ' ' + z(now.getHours()) + ':' + z(now.getMinutes()) + ':' + z(now.getSeconds());
    }

    /* ── Poblar dropdowns ───────────────────────── */
    function populateDropdowns() {
        var prev = {
            carrier  : $('#filterCarrier').val(),
            concept  : $('#filterConcept').val(),
            warehouse: $('#filterWarehouse').val(),
        };

        var carriers   = uniq(allData.map(function (d) { return d.carrier   && d.carrier.name;   }));
        var concepts   = uniq(allData.map(function (d) { return d.event     && d.event.name;     }));
        var warehouses = uniq(allData.map(function (d) { return d.warehouse && d.warehouse.name; }));

        fillSelect('#filterCarrier',   carriers,   prev.carrier);
        fillSelect('#filterConcept',   concepts,   prev.concept);
        fillSelect('#filterWarehouse', warehouses, prev.warehouse);
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

    /* ── Filtros ─────────────────────────────── */
    function applyFilters() {
        var term      = ($('#filterText').val()       || '').toLowerCase().trim();
        var carrier   = $('#filterCarrier').val()   || '';
        var concept   = $('#filterConcept').val()   || '';
        var warehouse = $('#filterWarehouse').val() || '';

        var filtered = allData.filter(function (d) {
            var cn  = (d.carrier   && d.carrier.name)   || '';
            var ev  = (d.event     && d.event.name)     || '';
            var wh  = (d.warehouse && d.warehouse.name) || '';
            var op  = (d.tracto    && d.tracto.operatorname) || '';

            var matchText = !term ||
                String(d.id        || '').toLowerCase().indexOf(term) > -1 ||
                (d.boxnumber || '').toLowerCase().indexOf(term) > -1 ||
                (d.shipmentid|| '').toLowerCase().indexOf(term) > -1 ||
                (d.barcode   || '').toLowerCase().indexOf(term) > -1 ||
                cn.toLowerCase().indexOf(term) > -1 ||
                op.toLowerCase().indexOf(term) > -1;

            var matchCarrier   = !carrier   || cn === carrier;
            var matchConcept   = !concept   || ev === concept;
            var matchWarehouse = !warehouse || wh === warehouse;

            return matchText && matchCarrier && matchConcept && matchWarehouse;
        });

        /* Ordenar */
        filtered.sort(function (a, b) {
            var dA = staysDays(a.stays || '');
            var dB = staysDays(b.stays || '');
            if (sortMode === 'wait-desc')  { return dB - dA; }
            if (sortMode === 'wait-asc')   { return dA - dB; }
            /* folio-asc */
            return (a.id || 0) - (b.id || 0);
        });

        renderCards(filtered);
    }

    /* ── Renderizar cards ─────────────────────────── */
    function renderCards(data) {
        $('#result-count').text(data.length + ' caja' + (data.length !== 1 ? 's' : ''));

        if (!data || data.length === 0) {
            $('#cards-container').html('<div class="pnl-empty">No hay cajas autorizadas con los filtros seleccionados</div>');
            return;
        }

        var html = '<div class="row">';
        data.forEach(function (d) {
            var days     = staysDays(d.stays || '');
            var urgent   = days > 2;
            var badgeCls = waitBadgeClass(days);
            var stayLbl  = d.stays
                ? esc(d.stays) + ' de espera'
                : 'Recién autorizada';

            var carrierName  = (d.carrier   && d.carrier.name)        || '—';
            var operatorName = (d.tracto    && d.tracto.operatorname)  || 'Sin operador asignado';
            var conceptName  = (d.event     && d.event.name)          || '—';
            var warehouseName= (d.warehouse && d.warehouse.name)       || '—';
            var phone        = (d.tracto    && d.tracto.phone)         || '';

            html +=
                '<div class="col-lg-3 col-md-6 col-sm-12 mb-3">' +
                  '<div class="ship-card' + (urgent ? ' urgent' : '') + '">' +

                    /* Encabezado */
                    '<div class="ship-card-top">' +
                      '<div class="flex-grow-1">' +
                        '<div class="ship-title">' +
                          '<span class="ship-em">Folio</span>' +
                          '<span class="ship-id">' + esc(d.id) + '</span>' +
                        '</div>' +
                        '<div class="ship-sub">' + esc(carrierName) + ' &middot; ' + esc(operatorName) +
                          (phone ? ' &middot; ' + esc(phone) : '') +
                        '</div>' +
                      '</div>' +
                      '<span class="wait-badge ' + badgeCls + '">' + stayLbl + '</span>' +
                    '</div>' +

                    /* Meta: Bodega / Embarque */
                    '<div class="ship-meta">' +
                      '<div>' +
                        '<div class="meta-label">Bodega</div>' +
                        '<div class="meta-val">' + esc(warehouseName) + '</div>' +
                      '</div>' +
                      '<div>' +
                        '<div class="meta-label">Embarque / Recibo</div>' +
                        '<div class="meta-val">' + esc(d.shipmentid || '—') + '</div>' +
                      '</div>' +
                    '</div>' +

                    /* Fechas */
                    '<div class="ship-dates">' +
                      '<div class="date-cell">' +
                        '<div class="date-lbl">F. Entrada</div>' +
                        '<div class="date-val">' + fmtDate(d.entrydate) + '</div>' +
                      '</div>' +
                      '<div class="date-div"></div>' +
                      '<div class="date-cell">' +
                        '<div class="date-lbl">Salida autorizada</div>' +
                        '<div class="date-val date-output">' + fmtDate(d.autorizaoutputdate) + '</div>' +
                      '</div>' +
                    '</div>' +

                    /* Footer */
                    '<div class="ship-footer">' +
                      '<span class="status-pill">' + esc(conceptName) + '</span>' +
                      '<a href="' + detailBase + d.id + '" class="ship-detail-link">Ver detalle →</a>' +
                    '</div>' +

                  '</div>' +
                '</div>';
        });
        html += '</div>';
        $('#cards-container').html(html);
    }

    /* ── Carga de datos ───────────────────────────── */
    function fetchData() {
        $.ajax({
            url   : '{{ url("getDataAuthorized") }}',
            method: 'GET',
            data  : { draw: 1, start: 0, length: 1000 },
            success: function (resp) {
                /* El endpoint devuelve formato DataTables: { data: [...] } */
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

    /* ── Eventos ────────────────────────────────── */
    $('#filterBtn').on('click', applyFilters);

    $('#filterText').on('keyup', function (e) {
        if (e.key === 'Enter') { applyFilters(); }
    });

    $('#filterCarrier, #filterConcept, #filterWarehouse').on('change', applyFilters);

    $('#sortSelect').on('change', function () {
        sortMode = $(this).val();
        applyFilters();
    });

    $('#clearBtn').on('click', function () {
        $('#filterText').val('');
        $('#filterCarrier, #filterConcept, #filterWarehouse').val('');
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

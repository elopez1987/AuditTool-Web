@extends('layouts.app')

@section('css')
<style>
    /* ── Paleta Traxíon ────────────────────────────────────── */
    :root {
        --tx-primary : #D0DF00;
        --tx-dark    : #2B2F33;
        --tx-gray-md : #6B7280;
        --tx-gray-lt : #E5E7EB;
        --tx-white   : #FFFFFF;
        --red        : #EF4444;
        --red-light  : #FEE2E2;
        --blue-link  : #2563EB;
    }

    /* ── Barra de filtros ───────────────────────────────── */
    .pnl-filter-card {
        border-radius: 10px;
        border: 1px solid var(--tx-gray-lt);
        box-shadow: 0 1px 4px rgba(0,0,0,.06);
    }
    .pnl-filter-label {
        font-size: .72rem;
        font-weight: 600;
        color: var(--tx-gray-md);
        text-transform: uppercase;
        letter-spacing: .04em;
        display: block;
        margin-bottom: .2rem;
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
    /* Botones de filtro */
    .pnl-btn-clear {
        display: inline-flex; align-items: center; gap: .35rem;
        padding: .45rem .85rem;
        border-radius: 6px;
        border: 1.5px solid var(--tx-gray-lt);
        background: var(--tx-white);
        color: var(--tx-gray-md);
        font-size: .82rem; font-weight: 600;
        cursor: pointer;
        white-space: nowrap;
        transition: all .12s;
    }
    .pnl-btn-clear:hover { background: var(--tx-gray-lt); }
    .pnl-btn-search {
        display: inline-flex; align-items: center; gap: .35rem;
        padding: .45rem .9rem;
        border-radius: 6px;
        border: none;
        background: #2563EB;
        color: #fff;
        font-size: .82rem; font-weight: 600;
        cursor: pointer;
        white-space: nowrap;
        transition: background .12s;
    }
    .pnl-btn-search:hover { background: #1d4ed8; }
    .pnl-btn-reload {
        display: inline-flex; align-items: center; gap: .35rem;
        padding: .45rem .85rem;
        border-radius: 6px;
        border: 1.5px solid var(--tx-gray-lt);
        background: var(--tx-white);
        color: var(--tx-gray-md);
        font-size: .82rem; font-weight: 600;
        cursor: pointer;
        white-space: nowrap;
        transition: all .12s;
    }
    .pnl-btn-reload:hover { background: var(--tx-gray-lt); }

    /* Selector de orden */
    .pnl-sort-select {
        width: auto;
        min-width: 230px;
        border-radius: 6px;
        border: 1px solid var(--tx-gray-lt);
        font-size: .82rem;
        color: var(--tx-dark);
        padding: .35rem .75rem;
    }

    /* ── Cards de embarque ─────────────────────────────── */
    .ship-card {
        background: var(--tx-white);
        border-radius: 10px;
        border-top   : 1px solid var(--tx-gray-lt);
        border-right : 1px solid var(--tx-gray-lt);
        border-bottom: 1px solid var(--tx-gray-lt);
        border-left  : 5px solid var(--red);
        box-shadow   : 0 1px 4px rgba(0,0,0,.07);
        padding      : 1rem 1.1rem;
        height       : 100%;
        display      : flex;
        flex-direction: column;
        gap          : .7rem;
    }
    /* Encabezado de la card */
    .ship-card-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: .5rem;
    }
    .ship-title {
        font-size: .95rem;
        color: var(--tx-dark);
        margin-bottom: .15rem;
    }
    .ship-title .ship-em  { font-weight: 400; }
    .ship-title .ship-id  { font-weight: 700; margin-left: .25rem; }
    .ship-sub {
        font-size: .78rem;
        color: var(--tx-gray-md);
    }
    /* Badge de atraso */
    .delay-badge {
        display: inline-block;
        padding: .25rem .7rem;
        border-radius: 999px;
        background: var(--red-light);
        color: var(--red);
        font-size: .76rem;
        font-weight: 600;
        white-space: nowrap;
        flex-shrink: 0;
    }
    /* Campos cliente/destino */
    .ship-meta {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: .75rem;
    }
    .meta-label {
        font-size: .66rem;
        font-weight: 700;
        letter-spacing: .06em;
        text-transform: uppercase;
        color: var(--tx-gray-md);
        margin-bottom: .18rem;
    }
    .meta-val {
        font-size: .88rem;
        font-weight: 700;
        color: var(--tx-dark);
    }
    /* Fechas */
    .ship-dates {
        display: flex;
        background: #F3F4F6;
        border-radius: 8px;
        overflow: hidden;
    }
    .date-cell {
        flex: 1;
        padding: .55rem .75rem;
    }
    .date-div {
        width: 1px;
        background: #D1D5DB;
        margin: .35rem 0;
    }
    .date-lbl {
        font-size: .65rem;
        font-weight: 700;
        letter-spacing: .06em;
        text-transform: uppercase;
        color: var(--tx-gray-md);
        margin-bottom: .18rem;
    }
    .date-val {
        font-size: .84rem;
        font-weight: 600;
        color: var(--tx-dark);
    }
    .date-overdue { color: var(--red); }
    /* Footer de la card */
    .ship-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: auto;
    }
    .status-pill {
        display: inline-block;
        padding: .25rem .75rem;
        border-radius: 999px;
        background: #F3F4F6;
        color: #374151;
        font-size: .7rem;
        font-weight: 700;
        letter-spacing: .05em;
        text-transform: uppercase;
    }
    .ship-detail-link {
        font-size: .82rem;
        color: var(--blue-link);
        font-weight: 500;
        text-decoration: none;
    }
    .ship-detail-link:hover { text-decoration: underline; color: #1d4ed8; }

    /* ── Estado vacío ────────────────────────────────────── */
    .pnl-empty {
        text-align: center;
        padding: 3rem 1rem;
        color: var(--tx-gray-md);
        font-size: .9rem;
    }
</style>
@endsection

@section('content')

{{-- ── BARRA DE FILTROS ───────────────────────────────── --}}
<div class="card pnl-filter-card mb-3">
    <div class="card-body py-3">
        <div class="row align-items-end" style="row-gap:.5rem;">
            <div class="col-lg-4 col-md-12">
                <label class="pnl-filter-label">Número de embarque</label>
                <input type="text" class="form-control" id="filterText"
                       placeholder="Buscar por embarque, cliente o destino...">
            </div>
            <div class="col-lg-2 col-md-6">
                <label class="pnl-filter-label">Cliente</label>
                <select id="filterClient" class="form-control">
                    <option value="">Todos</option>
                </select>
            </div>
            <div class="col-lg-2 col-md-6">
                <label class="pnl-filter-label">Destino</label>
                <select id="filterCity" class="form-control">
                    <option value="">Todos</option>
                </select>
            </div>
            <div class="col-lg-4 col-md-12 d-flex" style="gap:.4rem; flex-wrap:wrap;">
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
                    Recargar datos
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ── CONTROLES SECUNDARIOS ─────────────────────────────── --}}
<div class="d-flex justify-content-between align-items-center mb-3">
    <p class="text-muted small mb-0">
        Última actualización: <span id="lastupdate">—</span>
        &nbsp;&bull;&nbsp;
        <span id="result-count" class="text-muted"></span>
    </p>
    <select id="sortSelect" class="form-control pnl-sort-select">
        <option value="delay-desc">Ordenar: mayor atraso primero</option>
        <option value="delay-asc">Ordenar: menor atraso primero</option>
        <option value="ship-asc">Ordenar: embarque A → Z</option>
    </select>
</div>

{{-- ── CARDS ─────────────────────────────────────────────────── --}}
<div id="cards-container">
    <div class="pnl-empty">Cargando datos…</div>
</div>

@endsection

@section('script')
<script>
(function () {
    /* ── Estado global ────────────────────────────── */
    var allData    = [];
    var sortMode   = 'delay-desc';
    var detailBase = '{{ url("orderdeliveries") }}/';

    /* ── Helpers ───────────────────────────────── */
    function esc(str) {
        if (str == null) { return ''; }
        return String(str)
            .replace(/&/g, '&amp;').replace(/</g, '&lt;')
            .replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    /* Formatea "YYYY-MM-DD HH:mm:ss" → "DD/MM/YYYY HH:mm" */
    function fmtDate(val) {
        if (!val) { return '—'; }
        var str = String(val).replace(' ', 'T');
        try {
            var d = new Date(str);
            if (isNaN(d.getTime())) { return esc(val); }
            var z = function (n) { return n < 10 ? '0' + n : n; };
            return z(d.getDate()) + '/' + z(d.getMonth() + 1) + '/' + d.getFullYear() +
                   ' ' + z(d.getHours()) + ':' + z(d.getMinutes());
        } catch (e) { return esc(val); }
    }

    /* Días de atraso respecto al loadingdate */
    function delayDays(loadingdate) {
        if (!loadingdate) { return 0; }
        var str = String(loadingdate).replace(' ', 'T');
        return Math.max(0, Math.floor((new Date() - new Date(str)) / 86400000));
    }

    /* Actualiza el label de última actualización */
    function setLastUpdated() {
        var now = new Date();
        var z   = function (n) { return n < 10 ? '0' + n : n; };
        var ts  = now.getFullYear() + '-' + z(now.getMonth() + 1) + '-' + z(now.getDate()) +
                  ' ' + z(now.getHours()) + ':' + z(now.getMinutes()) + ':' + z(now.getSeconds());
        document.getElementById('lastupdate').textContent = ts;
    }

    /* ── Filtros ───────────────────────────────── */
    function applyFilters() {
        var term   = ($('#filterText').val()  || '').toLowerCase().trim();
        var client = $('#filterClient').val() || '';
        var city   = $('#filterCity').val()   || '';

        var filtered = allData.filter(function (d) {
            var matchText =
                !term ||
                (d.shipmentid   || '').toLowerCase().indexOf(term) > -1 ||
                (d.customername || '').toLowerCase().indexOf(term) > -1 ||
                (d.city         || '').toLowerCase().indexOf(term) > -1;
            var matchClient = !client || d.customername === client;
            var matchCity   = !city   || d.city         === city;
            return matchText && matchClient && matchCity;
        });

        /* Ordenar */
        filtered.sort(function (a, b) {
            var dA = delayDays(a.loadingdate);
            var dB = delayDays(b.loadingdate);
            if (sortMode === 'delay-desc') { return dB - dA; }
            if (sortMode === 'delay-asc')  { return dA - dB; }
            /* ship-asc */
            return (a.shipmentid || '').localeCompare(b.shipmentid || '', 'es');
        });

        renderCards(filtered);
    }

    /* ── Poblar dropdowns desde los datos cargados ─────── */
    function populateDropdowns() {
        var prevClient = $('#filterClient').val();
        var prevCity   = $('#filterCity').val();

        var clients = [...new Set(allData.map(function (d) { return d.customername; }).filter(Boolean))].sort();
        var cities  = [...new Set(allData.map(function (d) { return d.city;         }).filter(Boolean))].sort();

        var $c = $('#filterClient').empty().append('<option value="">Todos</option>');
        clients.forEach(function (v) { $c.append('<option value="' + esc(v) + '">' + esc(v) + '</option>'); });
        if (prevClient) { $c.val(prevClient); }

        var $d = $('#filterCity').empty().append('<option value="">Todos</option>');
        cities.forEach(function (v)  { $d.append('<option value="' + esc(v) + '">' + esc(v) + '</option>'); });
        if (prevCity) { $d.val(prevCity); }
    }

    /* ── Renderizar tarjetas ──────────────────────────── */
    function renderCards(data) {
        var $c = $('#cards-container');
        $('#result-count').text(data.length + ' embarque' + (data.length !== 1 ? 's' : ''));

        if (!data || data.length === 0) {
            $c.html('<div class="pnl-empty">No hay embarques pendientes de arribo con los filtros seleccionados</div>');
            return;
        }

        var html = '<div class="row">';
        data.forEach(function (d) {
            var delay  = delayDays(d.loadingdate);
            var subTxt = esc(d.carriername || '—') + ' · ' +
                         (d.operatorname ? esc(d.operatorname) : 'Sin arribo de unidad');
            var loadFmt = fmtDate(d.loadingdate);
            var dlvFmt  = fmtDate(d.downloaddate);

            html +=
                '<div class="col-lg-3 col-md-6 col-sm-12 mb-3">' +
                  '<div class="ship-card">' +

                    /* Encabezado */
                    '<div class="ship-card-top">' +
                      '<div class="flex-grow-1">' +
                        '<div class="ship-title">' +
                          '<span class="ship-em">Embarque</span>' +
                          '<span class="ship-id">' + esc(d.shipmentid || '') + '</span>' +
                        '</div>' +
                        '<div class="ship-sub">' + subTxt + '</div>' +
                      '</div>' +
                      (delay > 0
                        ? '<span class="delay-badge">' + delay + ' día' + (delay !== 1 ? 's' : '') + ' de atraso</span>'
                        : '') +
                    '</div>' +

                    /* Cliente / Destino */
                    '<div class="ship-meta">' +
                      '<div>' +
                        '<div class="meta-label">Cliente</div>' +
                        '<div class="meta-val">' + esc(d.customername || '—') + '</div>' +
                      '</div>' +
                      '<div>' +
                        '<div class="meta-label">Destino</div>' +
                        '<div class="meta-val">' + esc(d.city || '—') + '</div>' +
                      '</div>' +
                    '</div>' +

                    /* Fechas */
                    '<div class="ship-dates">' +
                      '<div class="date-cell">' +
                        '<div class="date-lbl">Fecha de carga</div>' +
                        '<div class="date-val date-overdue">' + loadFmt + '</div>' +
                      '</div>' +
                      '<div class="date-div"></div>' +
                      '<div class="date-cell">' +
                        '<div class="date-lbl">Fecha de entrega</div>' +
                        '<div class="date-val">' + dlvFmt + '</div>' +
                      '</div>' +
                    '</div>' +

                    /* Footer */
                    '<div class="ship-footer">' +
                      '<span class="status-pill">' + esc(d.status || '—') + '</span>' +
                      '<a href="' + detailBase + d.id + '" class="ship-detail-link">Ver detalle →</a>' +
                    '</div>' +

                  '</div>' +  /* .ship-card */
                '</div>';    /* .col */
        });
        html += '</div>';
        $c.html(html);
    }

    /* ── Carga desde el servidor ─────────────────────── */
    function fetchData() {
        $.ajax({
            url    : '{{ route("getShipmentStatusWithoutArrival") }}',
            method : 'GET',
            success: function (raw) {
                allData = JSON.parse(raw);
                setLastUpdated();
                populateDropdowns();
                applyFilters();
            },
            error  : function () {
                $('#cards-container').html('<div class="pnl-empty">Error al cargar los datos. Intente recargar.</div>');
            }
        });
    }

    /* ── Eventos ───────────────────────────────── */
    $('#filterBtn').on('click', applyFilters);

    $('#filterText').on('keyup', function (e) {
        if (e.key === 'Enter') { applyFilters(); }
    });

    $('#filterClient, #filterCity').on('change', applyFilters);

    $('#sortSelect').on('change', function () {
        sortMode = $(this).val();
        applyFilters();
    });

    $('#clearBtn').on('click', function () {
        $('#filterText').val('');
        $('#filterClient').val('');
        $('#filterCity').val('');
        applyFilters();
    });

    $('#reloaddata').on('click', function () {
        $('#cards-container').html('<div class="pnl-empty">Actualizando…</div>');
        fetchData();
    });

    /* ── Arranque ───────────────────────────────── */
    fetchData();
    /* Auto-recarga cada 3 minutos */
    setInterval(fetchData, 180000);
}());
</script>
@endsection

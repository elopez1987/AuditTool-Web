@extends('layouts.app')

@section('css')
<style>
    /* ── Paleta Traxión ─────────────────────────────────────────── */
    :root {
        --tx-primary  : #D0DF00;
        --tx-dark     : #2B2F33;
        --tx-gray-md  : #5A5A5A;
        --tx-gray-lt  : #E8E8E8;
        --tx-white    : #FFFFFF;
    }

    /* ── Título de sección ──────────────────────────────────────── */
    .capacity-title {
        font-weight: 700;
        color: var(--tx-dark);
        position: relative;
        padding-left: .75rem;
    }
    .capacity-title::before {
        content: '';
        position: absolute;
        left: 0; top: 50%;
        transform: translateY(-50%);
        width: 4px; height: 1.4em;
        background: var(--tx-primary);
        border-radius: 3px;
    }
    .btn-detail-link {
        font-size: .78rem;
        color: var(--tx-gray-md);
        text-decoration: underline;
    }
    .btn-detail-link:hover { color: var(--tx-dark); }

    /* ── KPI cards ──────────────────────────────────────────────── */
    .kpi-c {
        background: var(--tx-white);
        border: 1px solid var(--tx-gray-lt);
        border-radius: 10px;
        padding: 1.1rem 1.25rem;
        box-shadow: 0 1px 3px rgba(0,0,0,.05);
    }
    .kpi-c-label {
        font-size: .72rem;
        color: var(--tx-gray-md);
        text-transform: uppercase;
        letter-spacing: .03em;
        margin-bottom: .35rem;
        font-weight: 600;
    }
    .kpi-c-value {
        font-size: 2rem;
        font-weight: 700;
        line-height: 1;
        color: var(--tx-dark);
    }
    /* Ocupación global: color semántico */
    .kpi-c-occ.occ-green { background: #f0fdf4; border-color: #bbf7d0; }
    .kpi-c-occ.occ-green .kpi-c-value { color: #16a34a; }
    .kpi-c-occ.occ-green .kpi-c-label { color: #16a34a; }
    .kpi-c-occ.occ-amber { background: #fffbeb; border-color: #fde68a; }
    .kpi-c-occ.occ-amber .kpi-c-value { color: #d97706; }
    .kpi-c-occ.occ-amber .kpi-c-label { color: #d97706; }
    .kpi-c-occ.occ-red   { background: #fef2f2; border-color: #fecaca; }
    .kpi-c-occ.occ-red   .kpi-c-value { color: #dc2626; }
    .kpi-c-occ.occ-red   .kpi-c-label { color: #dc2626; }

    /* ── Tarjeta contenedora de zonas ───────────────────────────── */
    .zone-card {
        background: var(--tx-white);
        border-radius: 10px;
        border: 1px solid var(--tx-gray-lt);
        box-shadow: 0 1px 4px rgba(0,0,0,.05);
        overflow: hidden;
        margin-bottom: 2rem;
    }

    /* ── Barra de filtros ───────────────────────────────────────── */
    .zone-filter-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: .9rem 1.25rem;
        border-bottom: 1px solid var(--tx-gray-lt);
        flex-wrap: wrap;
        gap: .6rem;
    }
    .zone-controls {
        display: flex;
        gap: .5rem;
        flex-wrap: wrap;
    }
    .zc-input   { min-width: 180px; max-width: 220px; font-size: .82rem !important; }
    .zc-select  { min-width: 230px; font-size: .82rem !important; }
    .type-filters { display: flex; gap: .35rem; flex-wrap: wrap; }
    .type-chip {
        display: inline-flex; align-items: center;
        padding: .3rem .85rem;
        border-radius: 999px;
        border: 1.5px solid var(--tx-gray-lt);
        background: var(--tx-white);
        color: var(--tx-dark);
        font-size: .8rem;
        font-weight: 600;
        cursor: pointer;
        transition: all .12s ease;
        user-select: none;
    }
    .type-chip:hover { background: var(--tx-gray-lt); }
    .type-chip.active {
        background: var(--tx-dark);
        color: var(--tx-primary);
        border-color: var(--tx-dark);
    }

    /* ── Cabecera de columnas ───────────────────────────────────── */
    .zl-header {
        display: grid;
        grid-template-columns: 3fr 70px 1fr 110px;
        gap: 1rem;
        padding: .55rem 1.25rem;
        background: #fafafa;
        border-bottom: 1px solid var(--tx-gray-lt);
        font-size: .67rem;
        font-weight: 700;
        letter-spacing: .06em;
        text-transform: uppercase;
        color: var(--tx-gray-md);
        align-items: center;
    }
    .zl-col-status { text-align: right; }

    /* ── Filas de zona ──────────────────────────────────────────── */
    .zl-row {
        display: grid;
        grid-template-columns: 3fr 70px 1fr 110px;
        gap: 1rem;
        align-items: center;
        padding: .85rem 1.25rem;
        border-bottom: 1px solid var(--tx-gray-lt);
        transition: background .1s;
    }
    .zl-row:last-child { border-bottom: 0; }
    .zl-row:hover      { background: #fbfff0; }

    .zl-zone-name   { font-weight: 700; color: var(--tx-dark); font-size: .88rem; }
    .zl-zone-center { font-size: .72rem; color: var(--tx-gray-md); margin-top: .1rem; }
    .zl-col-cap     { font-size: .9rem; color: var(--tx-dark); font-weight: 600; }

    /* ── Barra de ocupación ─────────────────────────────────────── */
    .occ-bar-track {
        position: relative;
        height: 8px;
        background: #efefef;
        border-radius: 4px;
        overflow: visible;
    }
    .occ-bar-fill {
        position: absolute;
        left: 0; top: 0;
        height: 100%;
        border-radius: 4px;
        min-width: 3px;
    }
    .bar-green { background: #22c55e; }
    .bar-amber { background: #f59e0b; }
    .bar-red   { background: #ef4444; }

    /* Marca de umbral de saturación */
    .occ-threshold-mark {
        position: absolute;
        top: -4px; bottom: -4px;
        width: 2px;
        background: rgba(0,0,0,.18);
        border-radius: 1px;
        z-index: 2;
    }

    /* ── Columna de estatus ─────────────────────────────────────── */
    .zl-col-status { text-align: right; }
    .status-pct-row {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: .35rem;
        font-weight: 700;
        font-size: .9rem;
    }
    .status-dot {
        width: 8px; height: 8px;
        border-radius: 50%;
        flex-shrink: 0;
    }
    .sc-green .status-dot { background: #22c55e; }
    .sc-amber .status-dot { background: #f59e0b; }
    .sc-red   .status-dot { background: #ef4444; }
    .sc-green .status-pct-num { color: #16a34a; }
    .sc-amber .status-pct-num { color: #d97706; }
    .sc-red   .status-pct-num { color: #dc2626; }
    .status-frac { font-size: .72rem; color: var(--tx-gray-md); text-align: right; }

    /* ── Estado vacío ───────────────────────────────────────────── */
    .zl-empty { padding: 2rem 1.25rem; }

    /* ── Nota al pie ────────────────────────────────────────────── */
    .zl-footer {
        padding: .7rem 1.25rem;
        border-top: 1px solid var(--tx-gray-lt);
        background: #fafafa;
        font-size: .73rem;
        color: var(--tx-gray-md);
    }

    /* ── Responsive ─────────────────────────────────────────────── */
    @media (max-width: 576px) {
        .zl-header,
        .zl-row {
            grid-template-columns: 2fr 50px 1fr 80px;
            gap: .5rem;
            padding: .75rem .75rem;
        }
        .zc-select { min-width: 170px; }
    }
</style>
@endsection

@section('content')
@foreach($centers as $center)
    @php
        $centerZones   = $zonesByCenter->get($center->centerid, collect());
        $centerCapacity = $centerZones->sum('capacity');
//        $centerBusy    = $centerZones->sum(fn($z) => $locationfill->get($z->id, 0));
        $centerBusy    = $centerBusy;
        $centerFree    = max(0, $centerCapacity - $centerBusy);
        $centerPercent = $centerCapacity > 0 ? round((100 * $centerBusy) / $centerCapacity) : 0;
        $threshold     = $center->capacityyard;
        $warnLevel     = $threshold * 0.7;

        if ($centerPercent >= $threshold)  { $occClass = 'occ-red'; }
        elseif ($centerPercent >= $warnLevel) { $occClass = 'occ-amber'; }
        else                               { $occClass = 'occ-green'; }

        /* Ordenar zonas por % ocupación descendente (predeterminado) */
        $sortedZones = $centerZones->sortByDesc(
            fn($z) => $z->capacity > 0
                ? round((100 / $z->capacity) * $locationfill->get($z->id, 0))
                : 0
        );
    @endphp

    {{-- ── HEADER ─────────────────────────────────────────────── --}}
    <div class="d-flex justify-content-between align-items-start mb-3 mt-2">
        <div>
            <h4 class="capacity-title mb-1">Ocupación de patio — {{ $center->description }}</h4>
            <p class="text-muted small mb-0">{{ $centerZones->count() }} zonas registradas</p>
        </div>
        <div class="d-flex align-items-center" style="gap:1rem; margin-top:.25rem">
            <span class="text-muted small">
                Umbral de saturación: <strong>{{ $center->capacityyard }}%</strong>
            </span>
            <a href="{{ route('layoutinventoryforzone') }}" class="btn-detail-link">Ver detalles</a>
        </div>
    </div>

    {{-- ── KPI CARDS ───────────────────────────────────────────── --}}
    <div class="row mb-3">
        <div class="col-6 col-lg-3 mb-2">
            <div class="kpi-c h-100">
                <div class="kpi-c-label">Capacidad de patio</div>
                <div class="kpi-c-value">{{ $centerCapacity }}</div>
            </div>
        </div>
        <div class="col-6 col-lg-3 mb-2">
            <div class="kpi-c h-100">
                <div class="kpi-c-label">Unidades en patio</div>
                <div class="kpi-c-value">{{ $centerBusy }}</div>
            </div>
        </div>
        <div class="col-6 col-lg-3 mb-2">
            <div class="kpi-c h-100">
                <div class="kpi-c-label">Espacios libres</div>
                <div class="kpi-c-value">{{ $centerFree }}</div>
            </div>
        </div>
        <div class="col-6 col-lg-3 mb-2">
            <div class="kpi-c kpi-c-occ {{ $occClass }} h-100">
                <div class="kpi-c-label">Ocupación global</div>
                <div class="kpi-c-value">{{ $centerPercent }}%</div>
            </div>
        </div>
    </div>

    {{-- ── ZONA CARD ────────────────────────────────────────────── --}}
    <div class="zone-card">

        {{-- Filtros y controles --}}
        <div class="zone-filter-bar">
            <div class="type-filters" data-center="{{ $center->centerid }}">
                <span class="type-chip active" data-filter="ALL">Todas</span>
                <span class="type-chip" data-filter="ok">Disponibles</span>
                <span class="type-chip" data-filter="full">Saturadas</span>
            </div>
            <div class="zone-controls">
                <input type="text"
                       class="form-control capacity-search zc-input"
                       data-center="{{ $center->centerid }}"
                       placeholder="Buscar zona...">
                <select class="form-control capacity-sort zc-select"
                        data-center="{{ $center->centerid }}">
                    <option value="occ-desc">Ordenar: mayor ocupación primero</option>
                    <option value="occ-asc">Ordenar: menor ocupación primero</option>
                    <option value="name-asc">Ordenar: A → Z</option>
                    <option value="cap-desc">Ordenar: mayor capacidad primero</option>
                </select>
            </div>
        </div>

        {{-- Cabecera de columnas --}}
        <div class="zl-header">
            <div>Zona</div>
            <div>Capacidad</div>
            <div>Ocupación</div>
            <div class="zl-col-status">Estatus</div>
        </div>

        {{-- Filas de zona --}}
        <div class="zl-body" id="zone-body-{{ $center->centerid }}">
            @foreach($sortedZones as $zone)
                @php
                    $ocupado = $locationfill->get($zone->id, 0);
                    $libre   = max(0, $zone->capacity - $ocupado);
                    $pct     = $zone->capacity > 0 ? round((100 / $zone->capacity) * $ocupado) : 0;
                    $state   = ($zone->capacity > 0 && $pct > $threshold) ? 'full' : 'ok';

                    if ($pct >= $threshold) {
                        $sc = 'sc-red';   $barCls = 'bar-red';
                    } elseif ($zone->capacity > 0 && $pct >= $warnLevel) {
                        $sc = 'sc-amber'; $barCls = 'bar-amber';
                    } else {
                        $sc = 'sc-green'; $barCls = 'bar-green';
                    }
                @endphp
                <div class="zl-row capacity-row"
                     data-state="{{ $state }}"
                     data-zone="{{ strtolower($zone->name) }}"
                     data-occ="{{ $pct }}"
                     data-cap="{{ $zone->capacity }}">

                    {{-- Zona --}}
                    <div>
                        <div class="zl-zone-name">{{ $zone->name }}</div>
                        <div class="zl-zone-center">{{ $zone->center->description }}</div>
                    </div>

                    {{-- Capacidad --}}
                    <div class="zl-col-cap">{{ $zone->capacity }}</div>

                    {{-- Barra de ocupación --}}
                    <div>
                        <div class="occ-bar-track">
                            <div class="occ-bar-fill {{ $barCls }}"
                                 style="width: {{ min(100, $pct) }}%"></div>
                            @if($threshold > 0 && $threshold < 100)
                                <div class="occ-threshold-mark"
                                     style="left: {{ $threshold }}%"></div>
                            @endif
                        </div>
                    </div>

                    {{-- Estatus --}}
                    <div class="zl-col-status">
                        <div class="status-pct-row {{ $sc }}">
                            <span class="status-dot"></span>
                            <span class="status-pct-num">{{ $pct }}%</span>
                        </div>
                        <div class="status-frac">{{ $ocupado }}/{{ $zone->capacity }}</div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Estado vacío (mostrado por JS cuando no hay resultados) --}}
        <div class="zl-empty" id="zone-empty-{{ $center->centerid }}" style="display:none;">
            <p class="text-center text-muted mb-0">Sin resultados para el filtro seleccionado</p>
        </div>

        {{-- Nota al pie --}}
        <div class="zl-footer">
            La línea vertical en cada barra marca el umbral de saturación ({{ $center->capacityyard }}%).
            <span style="color:#16a34a">●</span> Verde = ocupación baja &nbsp;
            <span style="color:#d97706">●</span> Ámbar = acercándose al umbral &nbsp;
            <span style="color:#dc2626">●</span> Rojo = saturada.
        </div>
    </div>

@endforeach
@endsection

@section('script')
<script>
$(document).ready(function () {

    /* ── Aplica filtros de chip + búsqueda de texto ──────── */
    function applyCapacityFilters(centerid) {
        var state = $('.type-filters[data-center="' + centerid + '"] .type-chip.active').data('filter');
        var term  = ($('.capacity-search[data-center="' + centerid + '"]').val() || '').toLowerCase();
        var $rows = $('#zone-body-' + centerid + ' .zl-row');
        var visible = 0;

        $rows.each(function () {
            var matchState = (state === 'ALL') || ($(this).data('state') === state);
            var matchTerm  = ($(this).data('zone') + '').indexOf(term) > -1;
            var show = matchState && matchTerm;
            $(this).toggle(show);
            if (show) { visible++; }
        });

        if (visible === 0) {
            $('#zone-empty-' + centerid).show();
        } else {
            $('#zone-empty-' + centerid).hide();
        }
    }

    /* ── Ordena las filas de zona ────────────────────────── */
    function sortZones(centerid) {
        var sortVal = $('.capacity-sort[data-center="' + centerid + '"]').val();
        var $body   = $('#zone-body-' + centerid);
        var rows    = $body.find('.zl-row').toArray();

        rows.sort(function (a, b) {
            var aOcc  = parseInt($(a).data('occ'))  || 0;
            var bOcc  = parseInt($(b).data('occ'))  || 0;
            var aCap  = parseInt($(a).data('cap'))  || 0;
            var bCap  = parseInt($(b).data('cap'))  || 0;
            var aName = $(a).find('.zl-zone-name').text().trim();
            var bName = $(b).find('.zl-zone-name').text().trim();

            switch (sortVal) {
                case 'occ-asc':  return aOcc - bOcc;
                case 'name-asc': return aName.localeCompare(bName, 'es');
                case 'cap-desc': return bCap - aCap;
                default:         return bOcc - aOcc; /* occ-desc */
            }
        });

        /* Re-insertar en el DOM en el nuevo orden */
        rows.forEach(function (row) { $body.append(row); });
        applyCapacityFilters(centerid);
    }

    /* ── Eventos ─────────────────────────────────────────── */
    $('.type-filters').on('click', '.type-chip', function () {
        var centerid = $(this).closest('.type-filters').data('center');
        $(this).closest('.type-filters').find('.type-chip').removeClass('active');
        $(this).addClass('active');
        applyCapacityFilters(centerid);
    });

    $('.capacity-search').on('keyup', function () {
        applyCapacityFilters($(this).data('center'));
    });

    $('.capacity-sort').on('change', function () {
        sortZones($(this).data('center'));
    });
});
</script>
@endsection

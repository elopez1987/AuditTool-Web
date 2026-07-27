@extends('layouts.app')

@section('css')
<style>
    /* ── Paleta Traxíon ───────────────────────────────────────── */
    :root {
        --tx-primary : #D0DF00;
        --tx-dark    : #2B2F33;
        --tx-gray-md : #5A5A5A;
        --tx-gray-lt : #E8E8E8;
        --tx-white   : #FFFFFF;
    }

    /* ── Header ───────────────────────────────────────────── */
    .stays-title {
        font-weight: 700;
        color: var(--tx-dark);
        position: relative;
        padding-left: .75rem;
    }
    .stays-title::before {
        content: '';
        position: absolute;
        left: 0; top: 50%;
        transform: translateY(-50%);
        width: 4px; height: 1.4em;
        background: var(--tx-primary);
        border-radius: 3px;
    }
    .btn-stays-export {
        display: inline-flex; align-items: center; gap: .4rem;
        padding: .45rem .9rem;
        border-radius: 6px;
        font-size: .82rem; font-weight: 600;
        background: #1565C0;
        color: #fff;
        border: none;
        cursor: pointer;
        transition: background .15s;
    }
    .btn-stays-export:hover { background: #0d47a1; }

    /* ── Filtros ──────────────────────────────────────────── */
    .filter-card {
        border-radius: 8px;
        border: 1px solid var(--tx-gray-lt);
        box-shadow: 0 1px 4px rgba(0,0,0,.05);
    }
    .filter-card .form-control {
        border-radius: 6px;
        border-color: var(--tx-gray-lt);
        font-size: .83rem;
    }
    .filter-card .form-control:focus {
        border-color: var(--tx-dark);
        box-shadow: 0 0 0 .15rem rgba(43,47,51,.15);
    }
    .btn-stays-search {
        padding: .45rem .85rem;
        background: var(--tx-dark);
        color: var(--tx-primary);
        border: none;
        border-radius: 6px;
        font-size: .82rem; font-weight: 600;
        cursor: pointer;
        white-space: nowrap;
    }
    .btn-stays-search:hover { background: #1a1e22; }
    .btn-stays-clear {
        padding: .45rem .75rem;
        background: var(--tx-white);
        color: var(--tx-gray-md);
        border: 1.5px solid var(--tx-gray-lt);
        border-radius: 6px;
        font-size: .82rem; font-weight: 600;
        cursor: pointer;
        white-space: nowrap;
    }
    .btn-stays-clear:hover { background: var(--tx-gray-lt); }

    /* ── Tabla ───────────────────────────────────────────── */
    .stays-table-card {
        border-radius: 8px;
        border: 1px solid var(--tx-gray-lt);
        box-shadow: 0 1px 4px rgba(0,0,0,.05);
    }
    .stays-table thead th {
        font-size: .70rem;
        letter-spacing: .03em;
        color: var(--tx-gray-md);
        text-transform: uppercase;
        font-weight: 700;
        border: 0 !important;
        border-bottom: 2px solid var(--tx-gray-lt) !important;
        background: #fff;
        vertical-align: middle;
        white-space: nowrap;
    }
    .stays-table td {
        vertical-align: middle;
        border: 0 !important;
        border-bottom: 1px solid var(--tx-gray-lt) !important;
        font-size: .83rem;
    }
    .stays-table tbody tr:hover { background: #f7f8e8; }
    .stays-folio { font-weight: 700; color: var(--tx-dark); }
    .cell-dash   { color: #bbb; }

    /* ── Badges de estadía ────────────────────────────────────── */
    .stay-badge {
        display: inline-block;
        padding: .25rem .7rem;
        border-radius: 999px;
        font-size: .76rem; font-weight: 600;
        white-space: nowrap;
    }
    .stay-red    { background: #FEE2E2; color: #DC2626; }
    .stay-amber  { background: #FFEDD5; color: #EA580C; }
    .stay-yellow { background: #FEF9C3; color: #A16207; }
    .stay-green  { background: #D1FAE5; color: #059669; }

    /* ── Paginación ─────────────────────────────────────────── */
    .dataTables_wrapper .dataTables_paginate .paginate_button.current,
    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
        background: var(--tx-dark) !important;
        color: var(--tx-primary) !important;
        border-color: var(--tx-dark) !important;
        border-radius: 4px;
    }
    .dataTables_wrapper .dataTables_info { font-size: .78rem; color: var(--tx-gray-md); }
    .dataTables_wrapper .dataTables_paginate { font-size: .82rem; }
    .dt-buttons { display: none; }

    /* ── Label de filtros y toggle ──────────────────────────── */
    .stays-filter-label {
        font-size: .72rem;
        font-weight: 600;
        color: var(--tx-gray-md);
        text-transform: uppercase;
        letter-spacing: .03em;
        display: block;
        margin-bottom: .2rem;
    }
    .btn-toggle-extra {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        padding: .2rem .5rem;
        border: none;
        background: none;
        font-size: .78rem;
        color: var(--tx-gray-md);
        cursor: pointer;
        border-radius: 4px;
        transition: color .12s;
    }
    .btn-toggle-extra:hover { color: var(--tx-dark); }
</style>
@endsection

@section('content')

{{-- ── HEADER ──────────────────────────────────────────────── --}}
<div class="d-flex justify-content-between align-items-start mb-3">
    <div>
        <h4 class="stays-title mb-1">Estadías de patio</h4>
        <p class="text-muted small mb-0">Permanencia activa de unidades en el patio</p>
    </div>
    <button class="btn-stays-export" onclick="oTable.button(0).trigger()">
        <svg width="13" height="13" fill="currentColor" viewBox="0 0 16 16">
            <path d="M8 0a8 8 0 1 1 0 16A8 8 0 0 1 8 0zm-.5 4v4.793L5.354 6.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 8.793V4a.5.5 0 0 0-1 0z"/>
        </svg>
        Exportar Excel
    </button>
</div>

{{-- ── FILTROS ────────────────────────────────────────────── --}}
<div class="card filter-card mb-3">
    <div class="card-header"></div>
    <div class="card-body py-3">
        <form method="GET" id="search" role="form">
            {{ csrf_field() }}
            <div class="row align-items-end" style="row-gap:.5rem;">
                <div class="col-lg-2 col-md-4">
                    <label class="stays-filter-label">Folio</label>
                    <input type="text" class="form-control" id="folionumber" name="folionumber"
                           placeholder="Número de folio"
                           onKeyUp="this.value=this.value.toUpperCase()">
                </div>
                <div class="col-lg-2 col-md-4">
                    <label class="stays-filter-label">Placa de caja</label>
                    <input type="text" class="form-control" id="boxnumber" name="boxnumber"
                           placeholder="Placa de caja"
                           onKeyUp="this.value=this.value.toUpperCase()">
                </div>
                <div class="col-lg-2 col-md-4">
                    <label class="stays-filter-label">Embarque</label>
                    <input type="text" class="form-control" id="shipmentid" name="shipmentid"
                           placeholder="Número de embarque"
                           onKeyUp="this.value=this.value.toUpperCase()">
                </div>
                <div class="col-lg-2 col-md-6">
                    <label class="stays-filter-label">Línea de transporte</label>
                    <select name="carrierid" id="carrierid" class="form-control select2">
                        <option value="">Todas las líneas</option>
                        @foreach($carriers as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-2 col-md-6">
                    <label class="stays-filter-label">Concepto de entrada</label>
                    <select name="rasonid" id="rasonid" class="form-control select2">
                        <option value="">Todos los conceptos</option>
                        @foreach($rason as $r)
                            <option value="{{ $r->id }}">{{ $r->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-2 col-md-12 d-flex" style="gap:.4rem;">
                    <button type="submit" class="btn-stays-search flex-grow-1">
                        <svg width="11" height="11" fill="currentColor" viewBox="0 0 16 16" style="margin-right:3px;">
                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.099zm-5.242 1.156a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11z"/>
                        </svg>
                        Buscar
                    </button>
                    <button type="button" class="btn-stays-clear" id="btn-clear">
                        <svg width="11" height="11" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M11.354 4.646a.5.5 0 0 1 0 .708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 1 1 .708-.708L8 7.293l2.646-2.647a.5.5 0 0 1 .708 0z"/>
                        </svg>
                        Limpiar
                    </button>
                </div>
            </div>

            {{-- Filtros secundarios (inicialmente ocultos) --}}
            <div id="extra-filters" style="display:none;">
                <div class="row align-items-end mt-2" style="row-gap:.5rem;">
                    <div class="col-lg-3 col-md-6">
                        <label class="stays-filter-label">Estatus de folio</label>
                        <select name="statusid" id="statusid" class="form-control select2">
                            <option value="">Todos</option>
                            @foreach($status as $s)
                                <option value="{{ $s->statusid }}">{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label class="stays-filter-label">Estatus de unidad</label>
                        <select name="unitstatusid" id="unitstatusid" class="form-control select2">
                            <option value="">Todos</option>
                            @foreach($unitstatus as $u)
                                <option value="{{ $u->id }}">{{ $u->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label class="stays-filter-label">Código de barra</label>
                        <input type="text" class="form-control" id="barcode" name="barcode"
                               placeholder="Código de barra"
                               onKeyUp="this.value=this.value.toUpperCase()">
                    </div>
                </div>
            </div>

            {{-- Toggle filtros extra --}}
            <div class="mt-2">
                <button type="button" class="btn-toggle-extra" id="btn-toggle-extra">
                    <svg id="toggle-icon" width="11" height="11" fill="currentColor" viewBox="0 0 16 16" style="transition:transform .2s;">
                        <path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z"/>
                    </svg>
                    Más filtros
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ── LEYENDA DE COLORES ─────────────────────────────────── --}}
<div class="d-flex align-items-center mb-2" style="gap:1rem;">
    <span style="font-size:.75rem;color:#5A5A5A;">
        Estadía:
        <span class="stay-badge stay-green" style="font-size:.7rem;">≤ 15 días</span>
        <span class="stay-badge stay-yellow" style="font-size:.7rem;">15 – 30 días</span>
        <span class="stay-badge stay-amber" style="font-size:.7rem;">30 – 60 días</span>
        <span class="stay-badge stay-red" style="font-size:.7rem;">&gt; 60 días</span>
    </span>
</div>

{{-- ── TABLA ──────────────────────────────────────────────── --}}
<div class="card stays-table-card">
    <div class="card-header">Detalles</div>
    <div class="card-body p-3">
        <div class="table-responsive">
            <table id="table-stays" class="table stays-table mb-0">
                <thead>
                    <tr>
                        <th>Folio</th>
                        <th>Embarque</th>
                        <th>Línea de transporte</th>
                        <th>Placa de caja</th>
                        <th>Concepto de entrada</th>
                        <th>Estatus de folio</th>
                        <th>Estatus de unidad</th>
                        <th>F. Entrada</th>
                        <th>Estadía total</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

@endsection

@section('script')
@include('Yards.ReportStays.partials.table')
<script>
$(document).ready(function () {

    /* Select2 sin dropdownParent (filtros inline, no modal) */
    $('#carrierid, #rasonid, #statusid, #unitstatusid').select2();

    /* Reload al cambiar selects */
    $('#carrierid, #rasonid, #statusid, #unitstatusid').on('change', function () {
        oTable.ajax.reload();
    });

    /* Limpiar filtros */
    $('#btn-clear').on('click', function () {
        $('#folionumber, #boxnumber, #shipmentid, #barcode').val('');
        $('#carrierid, #rasonid, #statusid, #unitstatusid').val('').trigger('change');
        oTable.ajax.reload();
    });

    /* Toggle filtros extra */
    var extraVisible = false;
    $('#btn-toggle-extra').on('click', function () {
        extraVisible = !extraVisible;
        $('#extra-filters').toggle(extraVisible);
        $('#toggle-icon').css('transform', extraVisible ? 'rotate(180deg)' : 'rotate(0deg)');
        $(this).find('span:last').text(extraVisible ? 'Ocultar filtros' : 'Más filtros');
    });
});
</script>
@endsection

@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Detalle del evento</span>
                    <span class="badge {{$eventBadgeClass}}">{{$eventLabel}}</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <a href="{{route('activity-logs.index')}}">
                              @include('buttons.cancel')
                            </a>
                        </div>
                    </div>
                    <hr class="mb-3">
                    <div class="row">
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <label class="d-block text-muted font-12 mb-0">Descripción</label>
                            <span class="font-bold">{{$register->description}}</span>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <label class="d-block text-muted font-12 mb-0">Modelo</label>
                            <span class="font-bold">{{$register->subject_type}}</span>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <label class="d-block text-muted font-12 mb-0">Modelo ID</label>
                            <span class="font-bold">{{$register->subject_id}}</span>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <label class="d-block text-muted font-12 mb-0">Usuario</label>
                            <span class="font-bold">{{$register->user->name ?? 'N/A'}}</span>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <label class="d-block text-muted font-12 mb-0">Fecha</label>
                            <span class="font-bold">{{$register->created_at}}</span>
                        </div>
                    </div>
                    <hr class="mb-3">
                    <div class="row align-items-center mb-3">
                        <div class="col-md-6">
                            <span class="badge {{$changedBadgeClass}} diff-summary-badge">
                                {{$changedCount}} de {{$totalCount}} campos modificados
                            </span>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <div class="form-check form-switch d-inline-block">
                                <input class="form-check-input" type="checkbox" role="switch" id="onlyChangedSwitch" {{$changedCount > 0 ? 'checked' : ''}}>
                                <label class="form-check-label" for="onlyChangedSwitch">Mostrar solo cambios</label>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive diff-table-wrapper">
                        <table class="table table-bordered table-hover table-condensed align-middle diff-table" id="table-diff">
                            <thead>
                            <tr>
                                <th style="width: 25%">Campo</th>
                                <th style="width: 30%">Valor anterior</th>
                                <th style="width: 30%">Valor nuevo</th>
                                <th style="width: 15%" class="text-center">Estatus</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($changes as $change)
                                <tr class="diff-row {{$change['row_class']}}" data-status="{{$change['status']}}">
                                    <td><code>{{$change['field']}}</code></td>
                                    <td class="diff-value">{{$change['old']}}</td>
                                    <td class="diff-value">{{$change['new']}}</td>
                                    <td class="text-center">
                                        <span class="badge {{$change['status_class']}}">{{$change['status_label']}}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No hay información de propiedades para este registro.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('css')
    <style>
        .diff-summary-badge {
            font-size: .8rem;
            padding: .5em .85em;
        }

        .diff-table-wrapper {
            max-height: 65vh;
            overflow-y: auto;
        }

        .diff-table thead th {
            position: sticky;
            top: 0;
            z-index: 2;
            background-color: #f4f6f9;
        }

        .diff-table td,
        .diff-table th {
            vertical-align: middle;
        }

        .diff-table code {
            color: #495057;
            background: transparent;
            font-size: .8125rem;
        }

        .diff-value {
            font-family: SFMono-Regular, Consolas, "Liberation Mono", Menlo, monospace;
            font-size: .8125rem;
            white-space: pre-wrap;
            word-break: break-word;
        }

        .diff-table tbody tr[data-status="changed"] td:first-child {
            border-left: 4px solid #ffc107;
        }

        .diff-table tbody tr[data-status="added"] td:first-child {
            border-left: 4px solid #198754;
        }

        .diff-table tbody tr[data-status="removed"] td:first-child {
            border-left: 4px solid #dc3545;
        }

        .diff-table tbody tr[data-status="changed"] td:nth-child(3) {
            font-weight: 600;
        }

        .diff-table tbody tr[data-status="unchanged"] {
            color: #8a8f98;
        }
    </style>
@endsection


@section('script')
    <script>
        $(function () {
            const $toggle = $('#onlyChangedSwitch');
            const $rows = $('#table-diff tbody tr.diff-row');

            function applyDiffFilter() {
                const onlyChanges = $toggle.is(':checked');
                $rows.each(function () {
                    const $row = $(this);
                    $row.toggleClass('d-none', onlyChanges && $row.data('status') === 'unchanged');
                });
            }

            $toggle.on('change', applyDiffFilter);
            applyDiffFilter();
        });
    </script>
@endsection

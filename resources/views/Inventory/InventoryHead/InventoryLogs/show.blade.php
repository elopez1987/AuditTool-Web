@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Detalle del log de conteo - {{ $inventoryHead->name }}
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <a href="{{ route('inventory-logs', $inventoryHead->inventory_uuid) }}">
                                @include('buttons.back')
                            </a>
                            @if(filled($log->file_path))
                                <a href="{{ route('inventory-logs.download-file', [$inventoryHead->inventory_uuid, $log->id]) }}">
                                    <button type="button" class="btn waves-effect waves-light btn-outline-danger btn-md">
                                        <i class="fa fa-download ml-1"></i>
                                        <span class="text-button ml-2 mr-2"> Descargar archivo JSON</span>
                                    </button>
                                </a>
                            @endif
                        </div>
                    </div>
                    <hr class="mt-4 m-b-40">

                    <div class="row">
                        <div class="col-lg-10">
                            <div class="card">
                                <div class="card-header">Datos generales</div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-4 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label>Transacción</label>
                                                <input type="text" class="form-control" value="{{ $log->transaction_id }}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label>Estatus</label>
                                                <input type="text" class="form-control" value="{{ $log->status }}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label>HTTP</label>
                                                <input type="text" class="form-control" value="{{ $log->http_code }}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label>Servicio</label>
                                                <input type="text" class="form-control" value="{{ $log->service_name }}" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label>Endpoint</label>
                                                <input type="text" class="form-control" value="{{ $log->endpoint }}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label>Método</label>
                                                <input type="text" class="form-control" value="{{ $log->http_method }}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label>Usuario</label>
                                                <input type="text" class="form-control" value="{{ $log->username }}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label>IP</label>
                                                <input type="text" class="form-control" value="{{ $log->ip_address }}" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label>Cédula ID</label>
                                                <input type="text" class="form-control" value="{{ $log->inventory_card_id }}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label>Ubicación</label>
                                                <input type="text" class="form-control" value="{{ $log->location_id }}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label>Número de parte</label>
                                                <input type="text" class="form-control" value="{{ $log->partnumber_name }}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-lg-1 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label>Conteo</label>
                                                <input type="text" class="form-control" value="{{ $log->number_count }}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label>Cantidad</label>
                                                <input type="text" class="form-control" value="{{ $log->quantity }}" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6 col-md-12">
                                            <div class="form-group">
                                                <label>Mensaje</label>
                                                <input type="text" class="form-control" value="{{ $log->message }}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label>Recibido</label>
                                                <input type="text" class="form-control"
                                                       value="{{ optional($log->received_at)?->format('Y-m-d H:i:s') }}"
                                                       disabled>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label>Procesado</label>
                                                <input type="text" class="form-control"
                                                       value="{{ optional($log->processed_at)?->format('Y-m-d H:i:s') }}"
                                                       disabled>
                                            </div>
                                        </div>
                                    </div>
                                    @if($log->error_detail)
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label>Detalle de error</label>
                                                    <textarea class="form-control" rows="3" disabled>{{ $log->error_detail }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="mt-2 mb-2">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card border-gray">
                                <div class="card-header bg-grey d-flex align-items-center justify-content-between">
                                    <h5 class="m-b-0">Solicitud</h5>
                                    <button type="button" class="btn btn-sm btn-danger pl-2 pr-2" id="btnDownloadRequestJSON">
                                        <i class="fa fa-download"></i> Descargar Json
                                    </button>
                                </div>
                                <div class="card-body">
                                    <pre style="font-size: 12px !important;">{{ json_encode($log->request_payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card border-gray">
                                <div class="card-header bg-grey d-flex align-items-center justify-content-between">
                                    <h5 class="m-b-0">Respuesta</h5>
                                    <button type="button" class="btn btn-sm btn-danger pl-2 pr-2" id="btnDownloadResponseJSON">
                                        <i class="fa fa-download"></i> Descargar Json
                                    </button>
                                </div>
                                <div class="card-body">
                                    <pre style="font-size: 12px !important;">{{ json_encode($log->response_payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function downloadJson(data, fileName) {
            const blob = new Blob([JSON.stringify(data ?? {}, null, 2)], {type: 'application/json'});
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = fileName;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        }

        document.getElementById('btnDownloadRequestJSON').addEventListener('click', function () {
            downloadJson(
                @json($log->request_payload),
                'solicitud_{{ $log->id }}_{{ date("Y-m-d_H-i-s") }}.json'
            );
        });

        document.getElementById('btnDownloadResponseJSON').addEventListener('click', function () {
            downloadJson(
                @json($log->response_payload),
                'respuesta_{{ $log->id }}_{{ date("Y-m-d_H-i-s") }}.json'
            );
        });
    </script>
@endsection

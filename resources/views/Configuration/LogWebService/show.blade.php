@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">Detalle de la interface</div>
                <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <a href="{{route('webservicelog.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                    <hr class="mt-4 m-b-40">

                    <div class="row">
                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-header">Datos Generales</div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-4 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="auxiliary01">Centro de distribución </label>
                                                <input type="text" class="form-control"  value="{{$log->center ? $log->center->description : ''}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="auxiliary01">Bodega</label>
                                                <input type="text" class="form-control"  value="{{$log->warehouse ? $log->warehouse->name : ''}}" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="auxiliary01">Referencia </label>
                                                <input type="text" class="form-control"  value="{{$log->folionumber}}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="auxiliary01">Servicio</label>
                                                <input type="text" class="form-control"  value="{{$log->servicename}}" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="auxiliary01">Mensaje </label>
                                                <input type="text" class="form-control"  value="{{$log->message}}" disabled>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="mt-2 mb-2">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="card border-gray">
                                <div class="card-header bg-grey d-flex align-items-center justify-content-between">
                                    <h5 class="m-b-0">Solicitud</h5>
                                    <button type="button" class="btn btn-sm btn-danger pl-2 pr-2" id="btnDownloadJSON">
                                        <i class="fa fa-download"></i> Descargar Json
                                    </button>
                                </div>
                                <div class="card-body">
                                        <pre style="font-size: 12px !important;">{{ json_encode($log->messagejson, JSON_PRETTY_PRINT) }}</pre>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="card border-gray">
                                <div class="card-header bg-grey">
                                    <h5 class="m-b-0 ">Respuesta</h5></div>
                                <div class="card-body">
                                    <pre style="font-size: 12px !important;">{{ json_encode($log->messagejsonresponse, JSON_PRETTY_PRINT) }}</pre>
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
        document.getElementById('btnDownloadJSON').addEventListener('click', function() {
            const jsonData = @json($log->messagejson);
            const blob = new Blob([JSON.stringify(jsonData, null, 2)], { type: 'application/json' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'solicitud_{{ $log->id }}_{{ date("Y-m-d_H-i-s") }}.json';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        });
    </script>
@endsection


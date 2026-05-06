@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Subir evidencia
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <a href="{{route('orderdeliveries.index')}}">
                                @include('buttons.back')
                            </a>
                        </div>
                    </div>
                    <hr class="hr-line">
                    <div class="row mt-3 mb-4">
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Embarque</label>
                                <input type="text" class="form-control" readonly value="{{$orderDelivery->shipmentid}}">
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Cliente</label>
                                <input type="text" class="form-control" readonly value="{{$orderDelivery->customer->name}}">
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Línea de Transporte</label>
                                <input type="text" class="form-control" readonly value="{{$orderDelivery->carrier->name}}">
                            </div>
                        </div>
                    </div>
                    <hr class="hr-line">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card-input">
                                <div class="col-lg-12">
                                    <div class="card-foto" style="border-radius: 10px">
                                        <form
                                            action="{{ url('loadevidenceorder/'. $orderDelivery->id .'/upload') }}"
                                            class="dropzone" id="myAwesomeDropzone">
                                            {{ csrf_field() }}
                                            <div class="dz-message" data-dz-message>
                                        <span style="font-size: 12px;color: #4db6ac;font-weight: 500">
                                            Toca para abrir la camara o el selector de imagenes. <br><br>
                                            <i class="fa fa-camera fa-4x"></i>
                                        </span>
                                            </div>
                                            <div class="fallback">
                                                <input name="file" type="file" multiple/>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('/theme/assets/libs/dropzone/dist/min/dropzone.min.css')}}">
@endsection

@section('script')
    <script src="{{asset('/theme/assets/libs/dropzone/dist/dropzone.js')}}"></script>
    <script>
        Dropzone.options.myAwesomeDropzone = {
            paramName: "file",
            maxFiles: 25,
            acceptedFiles: ".jpeg,.jpg,.png,.gif",
            maxFilesize: 8,
        };
    </script>
@endsection

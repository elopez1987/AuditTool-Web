@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Subir evidencia.
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <a href="{{route('storageevidenceprocess.index')}}">
                                @include('buttons.back')
                            </a>
                        </div>
                    </div>
                    <hr class="mb-2">
                    <div class="row mt-3">
                        <div class="col-lg-2 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label for="id">Embarque:</label>
                                <input type="text" class="form-control" readonly value="{{$storageevidenceprocess->shipmentid}}" id="id" name="id">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="turnid">Turno:</label>
                                <input type="text" class="form-control" readonly value="{{$storageevidenceprocess->turn->name}}" id="turnid" name="turnid">
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="workday">Fecha:</label>
                                <input type="text" class="form-control" readonly value="{{$storageevidenceprocess->workday}}" id="workday" name="workday">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="location">Cortina:</label>
                                <input type="text" class="form-control" readonly value="{{$storageevidenceprocess->location->name}}" id="location" name="location">
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="boxnumber">Placa de caja:</label>
                                <input type="text" class="form-control" readonly value="{{$storageevidenceprocess->boxnumber}}" id="boxnumber" name="boxnumber">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-lg-6 col-sm-12">
                            <span style="font-size: 11px;" class="mb-3">Fotografias de Evidencias</span>
                            <div class="card-foto mt-3">
                                <form action="{{ url('uploadphotoshipment/'. $storageevidenceprocess->recordid .'/uploadfromweb') }}"
                                      class="dropzone" id="myAwesomeDropzone">
                                    {{ csrf_field() }}
                                    <div class="dz-message" data-dz-message>
                                        <span style="font-size: 12px;color: #4db6ac;font-weight: 500">
                                            Toca para abrir el selector de imagenes, las imagenes deben de tener un maximo de 8 mb. <br><br>
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
                <div class="card-footer text-center">
                    Evidencias / Evidencias de Proceso / Subir evidencias
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
            maxFiles: 10,
            acceptedFiles: ".jpeg,.jpg,.png,.gif",
            maxFilesize: 8,
        };
    </script>
@endsection


@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    SALIDA DE UNIDAD
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <form method="POST" action="{{url('authorizetransportoutput/' . $receiptunit->id)}}"
                                  enctype="multipart/form-data">
                                {{ method_field('PUT') }}
                                @csrf
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <a href="">
                                            @include('buttons.save')
                                        </a>
                                        <a href="{{route('reportauthorizedoutputs')}}">
                                            @include('buttons.back')
                                        </a>
                                    </div>
                                </div>
                                <hr class="m-t-0">
                                @include('Yards.TransportOutPut.partials.formEdit')
                            </form>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6 col-sm-12">
                            <h4 class="card-title" style="font-size: 12px;">Fotografias de Evidencias</h4>
                            <div class="card-foto">
                                <form action="{{ url('uploadphotocheckout/'. $receiptunit->id .'/upload') }}"
                                      class="dropzone" id="myAwesomeDropzone">
                                    {{ csrf_field() }}
                                    <div class="dz-message" data-dz-message>
                                        <span style="font-size: 12px;color: #4db6ac;font-weight: 500">
                                            Toca para abrir la camara o el selector de imagenes, las imagenes deben de tener un maximo de 8 mb. <br><br>
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
@endsection

@section('css')
    <link rel="stylesheet"
          href="{{asset('/theme/assets/libs/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css')}}">
    <link rel="stylesheet" href="{{asset('/theme/assets/libs/dropzone/dist/min/dropzone.min.css')}}">
@endsection

@section('script')
    <script src="{{asset('/theme/assets/libs/bootstrap-switch/dist/js/bootstrap-switch.min.js')}}"></script>
    <script>
        $(".switch input[type='checkbox']").bootstrapSwitch();
    </script>
    <script src="{{asset('/theme/assets/libs/dropzone/dist/dropzone.js')}}"></script>
    <script>
        Dropzone.options.myAwesomeDropzone = {
            paramName: "file",
            maxFiles: 5,
            acceptedFiles: ".jpeg,.jpg,.png,.gif",
            maxFilesize: 8,
        };
    </script>
@endsection


@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    check list de entrada
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <form method="POST" action="{{url('authorizetransportentry/' . $receiptunit->id)}}"
                                  enctype="multipart/form-data">
                                {{ method_field('PUT') }}
                                @csrf
                                <div class="row">
                                    <div class="col-12">
                                        <a href="">
                                            @include('buttons.save')
                                        </a>
                                        <a href="{{route('authorizetransportentry.index')}}">
                                            @include('buttons.back')
                                        </a>
                                    </div>
                                </div>
                                <hr class="mt-3">
                                @include('Yards.AuthorizeTransportEntry.partials.formEdit')
                            </form>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <h4 class="card-title" style="font-size: 12px;">Fotografias de Evidencias</h4>
                            <div class="card-foto" style="border-radius: 10px">
                                <form action="{{ url('uploadphotocheckin/'. $receiptunit->id .'/upload') }}"
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
@endsection

@section('css')
    <link rel="stylesheet"
          href="{{asset('/theme/assets/libs/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css')}}">
    <link rel="stylesheet" href="{{asset('/theme/assets/libs/dropzone/dist/min/dropzone.min.css')}}">
@endsection

@section('script')
    <script src="{{asset('/theme/assets/libs/bootstrap-switch/dist/js/bootstrap-switch.min.js')}}"></script>
    <script>
        $(".switch input[type='radio']").bootstrapSwitch();
    </script>
    <script src="{{asset('/theme/assets/libs/dropzone/dist/dropzone.js')}}"></script>
    <script>
        Dropzone.options.myAwesomeDropzone = {
            paramName: "file",
            maxFiles: 4,
            acceptedFiles: ".jpeg,.jpg,.png,.gif",
            maxFilesize: 8,
        };
    </script>
@endsection


@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Datos del embarque y consulta de datos a Recurso Confiable
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card-input">
                                <div class="card">
                                    <div class="card-header">
                                        Consulta datos de operador en Recurso Confiable
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-6 col-sm-12">
                                                <div class="form-group">
                                                    <label for="licencerc">Número de credencial
                                                        @error("licencerc")
                                                        <span class="message-error">{{$message}}</span>
                                                        @enderror
                                                    </label>
                                                    <input type="text"
                                                           class="form-control  @error('licencerc') is-invalid @enderror"
                                                           id="licencerc" name="licencerc" minlength="20" maxlength="20"
                                                           aria-describedby="licencerc"
                                                           onKeyUp=this.value=this.value.toUpperCase();
                                                    >
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-6 col-sm-12">
                                                <div class="form-group">
                                                    <label for="rfcoperator">RFC de Operador
                                                        @error("rfcoperator")
                                                        <span class="message-error">{{$message}}</span>
                                                        @enderror
                                                    </label>
                                                    <input type="text"
                                                           class="form-control  @error('rfcoperator') is-invalid @enderror"
                                                           id="rfcoperator" name="rfcoperator" maxlength="50"
                                                           aria-describedby="rfcoperator"
                                                           onKeyUp=this.value=this.value.toUpperCase();
                                                    >
                                                </div>
                                            </div>
                                            <div class="col-lg-2 col- col-md-6 col-sm-12 align-self-center">
                                                <div class="btn-group btn-group-md" role="group"
                                                     aria-label="Basic example">
                                                    <button id="searchOperatorInRC" type="button"
                                                            class="btn waves-effect waves-light btn-outline-info btn-md">
                                                        <img src="{{asset('/ico/adbuscar.png')}}" alt=""
                                                             class="img-16 ml-1">
                                                        <span style="font-size: 11px;"> Buscar</span>
                                                    </button>
                                                    <button id="clearInputs" type="button"
                                                            class="btn waves-effect waves-light btn-outline-info btn-md">
                                                        <img src="{{asset('/ico/adcancelar.png')}}" alt=""
                                                             class="img-16 ml-1">
                                                        <span style="font-size: 11px;"> Limpiar</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Captura de datos
                </div>
                <div class="card-body">
                    <form method="POST" action="{{url('datatransport/' . $transport->id)}}"
                          enctype="multipart/form-data">
                        {{ method_field('PUT') }}
                        @csrf
                        <div class="row mb-4">
                            <div class="col-12">
                                <a href="">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('datatransport.index')}}">
                                    @include('buttons.back')
                                </a>
                            </div>
                        </div>
                        <hr class="m-b-5">
                        @include('Shipments.StorageShipmenTransport.partials.formEdit')
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            @include('layouts.Partials.Errors')
        </div>
    </div>
@endsection

@section('css')

@endsection

@section('script')


    <script>
        $('#searchOperatorInRC').click(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            let licencerc = $('#licencerc').val();
            let rfcoperator = $('#rfcoperator').val();
            $.ajax({
                url: "{{url('searchStatusOfOperator')}}",
                type: "POST",
                data: {
                    licencerc: licencerc,
                    rfcoperator: rfcoperator,
                },
                success: function (result) {
                    $("#operatorname").val(result.operator.operatorname);
                    $("#statusoperator").val(result.operator.statusoperator);
                    $("#carriername").val(result.operator.carriername);
                    $("#rfccarrier").val(result.operator.rfccarrier);
                    $("#rfcoperator").val(result.operator.rfcoperator);
                    $("#licencerc").val(result.operator.credential);
                    document.getElementById('validateoperator').checked = true;
                },
                error: function (xhr, status, error) {
                    Swal.fire({
                        type: 'error',
                        title: `Operador no encontrado`,
                        width: 350,
                    })
                }
            });
        });
    </script>
    <script>
        document.getElementById('clearInputs').addEventListener('click', function () {
            document.getElementById('operatorname').value = '';
            document.getElementById('statusoperator').value = '';
            document.getElementById('carriername').value = '';
            document.getElementById('rfccarrier').value = '';
            document.getElementById('rfcoperator').value = '';
            document.getElementById('licencerc').value = '';
            document.getElementById('validateoperator').checked = false;
        });
    </script>
@endsection


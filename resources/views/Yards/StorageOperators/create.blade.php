@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Agregar Operador
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card" style="border: 2px solid #0f6674">
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
                                                       id="licencerc" name="licencerc" minlength="20"
                                                       maxlength="20"
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
                                                       id="rfcoperator" name="rfcoperator"
                                                       maxlength="50"
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
                    <div class="row">
                        <div class="col-lg-12">
                            <form method="POST" action="{{url('storageoperators')}}" enctype="multipart/form-data">
                                @csrf
                                <hr class="b-3">
                                @include('Yards.StorageOperators.partials.formCreate')
                                <hr class="b-3">
                                <div class="row">
                                    <div class="col-12">
                                        <a href="{{route('storageoperators.create')}}">
                                            @include('buttons.save')
                                        </a>
                                        <a href="{{route('storageoperators.index')}}">
                                            @include('buttons.back')
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('/theme/assets/libs/sweetalert2/dist/sweetalert2.min.css')}}">
@endsection


@section('script')
    <script src="{{asset('/theme/assets/libs/sweetalert2/dist/sweetalert2.all.min.js')}}"></script>
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
                    $("#name").val(result.operator.operatorname);
                    $("#statusoperator").val(result.operator.statusoperator);
                    $("#carriername").val(result.operator.carriername);
                    $("#rfccarrier").val(result.operator.rfccarrier);
                    $("#rfcoperator").val(result.operator.rfcoperator);
                    $("#drivernumber").val(result.operator.credential);
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


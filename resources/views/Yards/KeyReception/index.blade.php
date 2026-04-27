@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-8 col-md-12">
            <div class="card">
                <div class="card-header">
                    Recepción de llaves.
                </div>
                <form method="POST" action="{{url('savekeyreception')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="folionumber">Número de Folio <span style="color: red">*</span>
                                        @error("folionumber")
                                        <span class="message-error">{{$message}}</span>
                                        @enderror
                                    </label>
                                    <div class="input-group">
                                        <input type="number"  required class="form-control numeric @error('folionumber') is-invalid @enderror"
                                               id="folionumber" name="folionumber" value="{{ old('folionumber') }}">
                                    </div>
                                    @error('folionumber')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="">Codigo de barra</label>
                                    <div class="input-group">
                                        <input type="number" readonly class="form-control numeric" id="barcode" name="barcode" value="{{ old('barcode') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="statusid">Estatus de caja</label>
                                    <input type="text" class="form-control"  readonly id="statusid" name="statusid" value="{{ old('statusid') }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="boxnumber">Placa de caja</label>
                                    <input type="text" class="form-control"  readonly id="boxnumber" name="boxnumber" value="{{ old('boxnumber') }}">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="containerseal">Sellos de caja</label>
                                    <input type="text" class="form-control"  readonly id="containerseal" name="containerseal" value="{{ old('containerseal') }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="econumber">Eco. De caja</label>
                                    <input type="text" class="form-control"  readonly id="econumber" name="econumber" value="{{ old('econumber') }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="originzoneid">Zona</label>
                                    <input type="text" class="form-control"  readonly id="originzoneid" name="originzoneid" value="">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="originlocationid">Ubicación</label>
                                    <input type="text" class="form-control"  readonly id="originlocationid" name="originlocationid" value="{{ old('originlocationid') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-md-12">
                                <a href="">
                                    @include('buttons.save')
                                </a>
                                <a href="{{route('home')}}" style="margin-left: 10px">
                                    @include('buttons.cancel')
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @include('layouts.Partials.Errors')
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('/theme/assets/libs/sweetalert2/dist/sweetalert2.min.css')}}">
@endsection

@section('script')
    <script src="{{asset('/theme/assets/libs/sweetalert2/dist/sweetalert2.all.min.js')}}"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function searchFolioData() {
            var folioValue = $("#folionumber").val().trim();

            if (folioValue.length === 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error...',
                    text: 'Ingrese el numero de Folio o Etiqueta!',
                    width: '300px'
                });
                return;
            }

            $.getJSON('./receipt-units/' + folioValue + '/search', function (values) {
                var data = values.Data;
                if (!data) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'No se encuentran registros del folio!',
                        width: '300px'
                    });
                    $("#boxnumber").val('');
                    $("#containerseal").val('');
                    $("#originzoneid").val('');
                    $("#originlocationid").val('');
                    $("#statusid").val('');
                    $("#econumber").val('');
                    $("#barcode").val('');
                    return;
                }

                $("#boxnumber").val(data.boxnumber);
                $("#containerseal").val(data.containerseal);
                $("#originzoneid").val(data.zoneorg);
                $("#originlocationid").val(data.locationorg);
                $("#statusid").val(data.statusname);
                $("#econumber").val(data.econumberbox);
                $("#barcode").val(data.barcode);
            }).fail(function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'No se encuentran registros del folio!',
                    width: '300px'
                });
                $("#boxnumber").val('');
                $("#containerseal").val('');
                $("#originzoneid").val('');
                $("#originlocationid").val('');
                $("#statusid").val('');
                $("#econumber").val('');
                $("#barcode").val('');
            });
        }

        $(document).on('blur', '#folionumber', function () {
            searchFolioData();
        });
    </script>
@endsection

@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-8 col-md-12">
            <div class="card">
                <div class="card-header">
                    Reubicación de caja.
                </div>
                <form method="POST" action="{{url('storeMoveBox')}}" enctype="multipart/form-data">
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
                                    <input type="text" class="form-control"  readonly id="statusid" name="statusid">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="boxnumber">Placa de caja</label>
                                    <input type="text" class="form-control"  readonly id="boxnumber" name="boxnumber">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="containerseal">Sellos de caja</label>
                                    <input type="text" class="form-control"  readonly id="containerseal" name="containerseal">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="econumber">Eco. De caja</label>
                                    <input type="text" class="form-control"  readonly id="econumber" name="econumber">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="originzoneid">Zona Origen</label>
                                    <input type="text" class="form-control"  readonly id="originzoneid" name="originzoneid">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="originlocationid">Ubicación Origen</label>
                                    <input type="text" class="form-control"  readonly id="originlocationid" name="originlocationid">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="zoneid">Zona Destino
                                        <span style="color: red">*</span>
                                        @error("zoneid")
                                        <span class="message-error">{{$message}}</span>
                                        @enderror
                                    </label>
                                    <select name="zoneid" id="zoneid" required class="form-control select2 @error('zoneid') is-invalid @enderror">
                                        <option value="" selected>Seleccione una opción</option>
                                        @foreach($zones as $model)
                                            <option value="{{$model->id}}" {{ old('zoneid') == $model->id ? 'selected' : '' }}> {{$model->name}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="locationid">Ubicación Destino
                                        <span style="color: red">*</span>
                                        @error("locationid")
                                        <span class="message-error">{{$message}}</span>
                                        @enderror
                                    </label>
                                    <select name="locationid" id="locationid" required class="form-control select2 @error('locationid') is-invalid @enderror">
                                        <option value="" selected>Seleccione una opción</option>
                                        @foreach($locations as $model)
                                            <option value="{{$model->id}}" {{ old('locationid') == $model->id ? 'selected' : '' }}> {{$model->name}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="unitstatusid">Estatus de unidad
                                        <span style="color: red">*</span>
                                        @error("unitstatusid")
                                        <span class="message-error">{{$message}}</span>
                                        @enderror
                                    </label>
                                    <select name="unitstatusid" required id="unitstatusid" class="form-control select2 @error('unitstatusid') is-invalid @enderror">
                                        <option value="" selected>Seleccione una opción</option>
                                        @foreach($unitstatus as $model)
                                            <option value="{{$model->id}}" {{ old('unitstatusid') == $model->id ? 'selected' : '' }}> {{$model->name}} </option>
                                        @endforeach
                                    </select>
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
        $("#zoneid").change(event => {
            $.get(`./getLocationForZone/${event.target.value}`, function (res) {
                $("#locationid").empty();
                res.forEach(element => {
                    $("#locationid").append(`<option value=${element.id}> ${element.name} </option>`);
                });
            });
        });
    </script>
    <script>
        $("#folionumber").blur(function () {
            if ($("#folionumber").val().length === 0) {
                Swal.fire({
                    type: 'error',
                    title: 'Error...',
                    text: 'Ingrese el numero de Folio o Etiqueta!',
                    width: '300px'
                })
            } else {
                $.getJSON('./getDataInvoiceForFolio/' + $("#folionumber").val(), function (values) {
                    if (0 === values.length) {
                        Swal.fire({
                            type: 'error',
                            title: 'Oops...',
                            text: 'No se encuentran registros del folio!',
                            width: '300px'
                        })
                    } else {
                        $("#boxnumber").attr("value", values[0].boxnumber);
                        $("#containerseal").attr("value", values[0].containerseal);
                        $("#originzoneid").attr("value", values[0].zona);
                        $("#originlocationid").attr("value", values[0].ubicacion);
                        $("#statusid").attr("value", values[0].status);
                        $("#econumber").attr("value", values[0].econumber);
                        $("#barcode").attr("value", values[0].barcode);
                    }
                });
            }
        });
    </script>
@endsection

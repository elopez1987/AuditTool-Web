@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-8 col-md-12">
            <div class="card">
                <div class="card-header">
                    Rechazar unidad.
                </div>
                <form method="POST" action="{{url('registerrefuseunit')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <hr class="m-t-1">
                                <div class="row">
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="folionumber">Folio de unidad <span style="color: red">*</span> </label>
                                            <div class="input-group">
                                                <input type="number" class="form-control numeric @error('folionumber') is-invalid @enderror" id="folionumber"
                                                       name="folionumber"
                                                      required
                                                       value="{{ old('folionumber') }}">
                                                @error('folionumber')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="barcode">Código de barra de la caja</label>
                                            <div class="input-group">
                                                <input type="text"  readonly class="form-control " id="barcode"
                                                       name="barcode"
                                                       aria-label=""
                                                        value="{{ old('barcode') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="operatorname">Nombre de operador</label>
                                            <div class="input-group">
                                                <input type="text" readonly class="form-control" id="operatorname" name="operatorname" value="{{old('operatorname')}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label for="shipmentid">Número de embarque</label>
                                            <div class="input-group">
                                                <input type="text"  readonly class="form-control" id="shipmentid" name="shipmentid" value="{{old('shipmentid')}}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-lg-4 col-md-6  col-xs-12">
                                        <div class="form-group">
                                            <label for="boxnumber">Placa de caja</label>
                                            <input type="text" class="form-control" readonly id="boxnumber"
                                                   name="boxnumber"
                                                   value="{{ old('boxnumber') }}"
                                            >
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6  col-xs-12">
                                        <div class="form-group">
                                            <label for="linea">Linea de transporte</label>
                                            <input type="text" class="form-control" readonly id="linea"
                                                   name="linea"
                                                   value="{{ old('linea') }}"
                                            >
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                <input type="submit" value="Rechazar Unidad" name="btn" id="2"
                                       class="btn btn-block btn-block btn-outline-info">
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
        $("#folionumber").blur(function () {
            if ($("#folionumber").val().length === 0) {
                Swal.fire({
                    type: 'error',
                    title: 'Error...',
                    text: 'Ingrese el código de barra!',
                })
            } else {
                $.getJSON('./getDataInvoiceForFolio/' + $("#folionumber ").val(), function (values) {
                    if (0 === values.length) {
                        Swal.fire({
                            type: 'error',
                            title: 'Oops...',
                            text: 'No se encuentran registros del folio!',
                        })
                    } else {
                        $("#boxnumber").attr("value", values[0].boxnumber);
                        $("#folionumber").attr("value", values[0].folio);
                        $("#linea").attr("value", values[0].linea);
                        $("#barcode").attr("value", values[0].barcode);
                        $("#operatorname").attr("value", values[0].operatorname);
                        $("#shipmentid").attr("value", values[0].shipmentid);
                    }
                });
            }
        });
    </script>
@endsection


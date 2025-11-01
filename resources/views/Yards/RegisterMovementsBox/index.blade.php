@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-8 col-md-12">
            <div class="card">
                <div class="card-header">
                    Registra la entrega de documentacion a mesa de control.
                </div>
                <form id="arriveUnitForm">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-xs-12">
                                <div class="form-group">
                                    <label for="folionumber">Número de Folio
                                        <span style="color: red">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('folionumber') is-invalid @enderror"  required id="folionumber" name="folionumber" value="{{ old('folionumber') }}">
                                    @error('folionumber')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="barcode">Código de barra de la caja</label>
                                    <div class="input-group">
                                        <input type="number" readonly class="form-control numeric" id="barcode" name="barcode" autofocus value="{{ old('barcode') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-lg-6 col-md-6  col-xs-12">
                                <div class="form-group">
                                    <label for="linea">Línea de Transporte</label>
                                    <input type="text" class="form-control" readonly  id="linea" name="linea" value="{{ old('linea') }}">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12">
                                <div class="form-group">
                                    <label for="operatorname">Nombre de Operador</label>
                                    <input type="text" class="form-control" readonly id="operatorname" name="operatorname" value="{{ old('operatorname') }}">
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-lg-6 col-md-6  col-xs-12">
                                <div class="form-group">
                                    <label for="boxnumber">Placa de caja</label>
                                    <input type="text" class="form-control" readonly  id="boxnumber" name="boxnumber" value="{{ old('boxnumber') }}">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12">
                                <div class="form-group">
                                    <label for="econumber">Eco. De caja</label>
                                    <input type="text" class="form-control" readonly id="econumber" name="econumber" value="{{ old('econumber') }}">
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                                <button type="submit" class="btn btn-block btn-block btn-outline-success btn-block" id="submitBtn">
                                    <i class="ti ti-check mr-2 "></i> Guardar
                                </button>
                            </div>
                            <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                                <a href="{{route('home')}}" class="btn btn-block btn-block btn-outline-danger" style="margin-left: 10px">
                                    <i class="fas fa-times mr-2"></i> Cancelar
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('css')

@endsection

@section('script')
    <script>
        $("#folionumber").blur(function () {
            if ($("#folionumber").val().length === 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error...',
                    text: 'Ingrese el código de barra!',
                });
            } else {
                $.getJSON('./getDataInvoiceForFolio/' + $("#folionumber").val(), function (values) {
                    if (0 === values.length) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'No se encuentran registros del folio!',
                        });
                    } else {
                        $("#boxnumber").attr("value", values[0].boxnumber);
                        $("#folionumber").attr("value", values[0].folio);
                        $("#econumber").attr("value", values[0].econumber);
                        $("#barcode").attr("value", values[0].barcode);
                        $("#linea").attr("value", values[0].linea);
                        $("#operatorname").attr("value", values[0].operatorname);
                    }
                });
            }
        });

        $("#arriveUnitForm").on('submit', function (e) {
            e.preventDefault();
            
            const folionumber = $("#folionumber").val();
            
            if (folionumber.length === 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Por favor ingrese el número de folio',
                });
                return;
            }

            // Deshabilitar botón de submit
            $("#submitBtn").prop('disabled', true);

            $.ajax({
                url: '{{url("saveArriveUnitToCenter")}}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    folionumber: folionumber,
                    barcode: $("#barcode").val(),
                    linea: $("#linea").val(),
                    operatorname: $("#operatorname").val(),
                    boxnumber: $("#boxnumber").val(),
                    econumber: $("#econumber").val()
                },
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: response.message,
                        showConfirmButton: true
                    }).then((result) => {
                        // Limpiar el formulario
                        $("#arriveUnitForm")[0].reset();
                        $("#boxnumber").val('');
                        $("#folionumber").val('');
                        $("#econumber").val('');
                        $("#barcode").val('');
                        $("#linea").val('');
                        $("#operatorname").val('');
                        $("#submitBtn").prop('disabled', false);
                    });
                },
                error: function (xhr) {
                    let errorMessage = 'Error al procesar la solicitud';
                    
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                        errorMessage = Object.values(xhr.responseJSON.errors).flat().join('\n');
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: errorMessage,
                    });
                    
                    $("#submitBtn").prop('disabled', false);
                }
            });
        });
    </script>
@endsection

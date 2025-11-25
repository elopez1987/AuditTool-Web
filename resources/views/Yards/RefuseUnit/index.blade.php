@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-8 col-md-12">
            <div class="card">
                <div class="card-header">
                    Rechazar unidad.
                </div>
                <form id="refuseUnitForm" method="POST" enctype="multipart/form-data">
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
                            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                                <button type="button" id="submitBtn" class="btn btn-block btn-block btn-outline-success btn-block">
                                    <i class="fa fa-check ml-2 mr-2"></i>Rechazar Unidad
                                </button>
                            </div>
                            <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                                <a href="{{route('home')}}" class="btn btn-block btn-block btn-outline-danger" style="margin-left: 10px">
                                    <i class="fas fa-times ml-2 mr-2"></i> Cancelar
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

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var shouldValidateBlur = true;
        function searchFolioData() {
            var folioValue = $("#folionumber").val().trim();
            
            if (folioValue.length === 0) {
                if (shouldValidateBlur) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error...',
                        text: 'Ingrese el folio de la unidad!',
                    });
                }
                return;
            }
            $.getJSON('./receipt-units/' + folioValue + '/search', function (values) {
                if (0 === values.length) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'No se encuentran registros del folio!',
                    });
                    $("#boxnumber").val('');
                    $("#linea").val('');
                    $("#barcode").val('');
                    $("#operatorname").val('');
                    $("#shipmentid").val('');
                } else {
                    $("#boxnumber").val(values.Data.boxnumber);
                    $("#folionumber").val(values.Data.folionumber);
                    $("#linea").val(values.Data.carriername);
                    $("#barcode").val(values.Data.barcode);
                    $("#operatorname").val(values.Data.operatorname);
                    $("#shipmentid").val(values.Data.shipmentid);
                }
            }).fail(function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al consultar los datos del folio',
                });
            });
        }

        $(document).on('blur', '#folionumber', function() {
            searchFolioData();
        });

        $("#submitBtn").click(function(e) {
            e.preventDefault();
            
            // Validar formulario
            let form = document.getElementById('refuseUnitForm');
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            // Confirmar acción
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¿Deseas rechazar esta unidad?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, rechazar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $("#submitBtn").prop('disabled', true);

                    let formData = new FormData(form);

                    $.ajax({
                        url: '{{url("registerrefuseunit")}}',
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Éxito!',
                                text: response.message || 'Unidad rechazada correctamente',
                                showConfirmButton: true,
                                timer: 3000
                            }).then(() => {
                                // Deshabilitar validación temporal
                                shouldValidateBlur = false;
                                
                                $('#folionumber').val('');
                                $('#barcode').val('');
                                $('#operatorname').val('');
                                $('#shipmentid').val('');
                                $('#boxnumber').val('');
                                $('#linea').val('');
                                
                                $('#folionumber').focus();
                                
                                // Reactivar validación después de un momento
                                setTimeout(function() {
                                    shouldValidateBlur = true;
                                }, 100);
                                
                                $("#submitBtn").prop('disabled', false);
                            });
                        },
                        error: function(xhr) {
                            let errorMessage = 'Error al procesar la solicitud';
                            
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            } else if (xhr.responseJSON && xhr.responseJSON.error) {
                                errorMessage = xhr.responseJSON.error;
                            } else if (xhr.responseText) {
                                try {
                                    let response = JSON.parse(xhr.responseText);
                                    errorMessage = response.message || response.error || errorMessage;
                                } catch(e) {
                                    errorMessage = xhr.statusText || errorMessage;
                                }
                            }

                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: errorMessage,
                                showConfirmButton: true
                            });
                            
                            $("#submitBtn").prop('disabled', false);
                        }
                    });
                }
            });
        });
    </script>
@endsection


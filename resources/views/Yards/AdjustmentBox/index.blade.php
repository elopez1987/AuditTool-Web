@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-6 col-md-12">
            <div class="card">
                <div class="card-header">
                    Salida de caja por ajuste de Inventario.
                </div>
                <form id="adjustmentBoxForm" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="folionumber">Número de folio<span style="color: red">*</span></label>
                                    <input type="text" class="form-control" required id="folionumber"
                                           name="folionumber">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="carriername">Linea de transporte<span
                                                style="color: red">*</span></label>
                                    <input type="text" readonly class="form-control" id="carriername"
                                           name="carriername">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="statusid">Estatus de Caja<span style="color: red">*</span></label>
                                    <input type="text" readonly class="form-control" id="statusid" name="statusid">
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="boxnumber">Placas de Caja<span style="color: red">*</span></label>
                                    <input type="text" readonly class="form-control" id="boxnumber" name="boxnumber">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="econumber">Eco. de caja<span style="color: red">*</span></label>
                                    <input type="text" readonly class="form-control" id="econumber" name="econumber">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="trucknumber">Placas de tracto<span style="color: red">*</span></label>
                                    <input type="text" readonly class="form-control" id="trucknumber"
                                           name="trucknumber">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="barcodetruck">Código de barra de tracto<span style="color: red">*</span></label>
                                    <input type="text" readonly class="form-control" id="barcodetruck"
                                           name="barcodetruck">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-8 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="barcodetruck">Comentario del ajuste<span
                                                style="color: red">*</span></label>
                                    <textarea id="comment" name="comment" required class="form-control" cols="100"
                                              rows="5"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                                <button type="button" id="btnSave"
                                        class="btn btn-block btn-block btn-outline-success btn-block">
                                    <i class="fas fa-check mr-2"></i> Guardar
                                </button>
                            </div>
                            <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                                <a href="{{route('home')}}" class="btn btn-block btn-block btn-outline-danger"
                                   style="margin-left: 10px">
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
                        text: 'Ingrese el número de etiqueta!',
                        width: '300px'
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
                        width: '300px'
                    });
                    // Limpiar campos si no se encuentra
                    $("#boxnumber").val('');
                    $("#trucknumber").val('');
                    $("#statusid").val('');
                    $("#econumber").val('');
                    $("#carriername").val('');
                    $("#barcodetruck").val('');
                } else {
                    $("#boxnumber").val(values.Data.boxnumber);
                    $("#trucknumber").val(values.Data.trucknumber);
                    $("#statusid").val(values.Data.statusname);
                    $("#econumber").val(values.Data.econumberbox);
                    $("#folionumber").val(values.Data.folionumber);
                    $("#carriername").val(values.Data.carriername);
                    $("#barcodetruck").val(values.Data.barcodetruck);
                }
            }).fail(function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al consultar los datos del folio',
                    width: '300px'
                });
            });
        }

        // Vincular evento blur al campo folionumber
        $(document).on('blur', '#folionumber', function () {
            searchFolioData();
        });

        // Evento del botón de guardar
        $("#btnSave").click(function (e) {
            e.preventDefault();

            // Validar formulario
            let form = document.getElementById('adjustmentBoxForm');
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            // Confirmar acción
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¿Deseas registrar esta salida por ajuste de inventario?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, registrar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Deshabilitar botón para evitar doble click
                    $("#btnSave").prop('disabled', true);

                    // Preparar datos del formulario
                    let formData = new FormData(form);

                    $.ajax({
                        url: '{{url("registeroutputunit")}}',
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Éxito!',
                                text: response.message || 'Ajuste realizado correctamente',
                                showConfirmButton: true,
                                timer: 3000
                            }).then(() => {
                                // Deshabilitar validación temporal
                                shouldValidateBlur = false;

                                // Limpiar todos los inputs del formulario
                                $('#folionumber').val('');
                                $('#carriername').val('');
                                $('#statusid').val('');
                                $('#boxnumber').val('');
                                $('#econumber').val('');
                                $('#trucknumber').val('');
                                $('#barcodetruck').val('');
                                $('#comment').val('');

                                // Enfocar el campo de folio para nueva captura
                                $('#folionumber').focus();

                                // Reactivar validación después de un momento
                                setTimeout(function () {
                                    shouldValidateBlur = true;
                                }, 100);

                                // Habilitar botón de guardar
                                $("#btnSave").prop('disabled', false);
                            });
                        },
                        error: function (xhr) {
                            let errorMessage = 'Error al procesar la solicitud';

                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            } else if (xhr.responseJSON && xhr.responseJSON.error) {
                                errorMessage = xhr.responseJSON.error;
                            } else if (xhr.responseText) {
                                try {
                                    let response = JSON.parse(xhr.responseText);
                                    errorMessage = response.message || response.error || errorMessage;
                                } catch (e) {
                                    errorMessage = xhr.statusText || errorMessage;
                                }
                            }

                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: errorMessage,
                                showConfirmButton: true
                            });

                            $("#btnSave").prop('disabled', false);
                        }
                    });
                }
            });
        });
    </script>
@endsection

@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-8 col-md-12">
            <div class="card">
                <div class="card-header">
                    ajuste de datos de unidad.
                </div>
                <form id="adjustmentUnitForm" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="folionumber">Número de folio</label>
                                    <input type="text"  class="form-control" id="folionumber" name="folionumber" >
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="carriername">Linea de transporte</label>
                                    <input type="text" readonly class="form-control" id="carriername"
                                           name="carriername">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="boxnumber">Placas de Caja</label>
                                    <input type="text" class="form-control" id="boxnumber" name="boxnumber">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="econumber">Eco. De caja</label>
                                    <input type="text" class="form-control" maxlength="19" id="econumber" name="econumber">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="barcode">Código de barra</label>
                                    <input type="text" class="form-control" id="barcode" readonly name="barcode">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="barcodenew">Nuevo código de barra</label>
                                    <input type="text" class="form-control" maxlength="19" id="barcodenew" name="barcodenew">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="econumber">Concepto de entrada</label>
                                    <input type="text" readonly class="form-control" id="razonid" >
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="razonstatusid">Concepto de entrada nuevo </label>
                                    <select name="razonstatusid" class="form-control select2">
                                        <option value="" selected>Seleccione un estatus</option>
                                        @foreach($concepts as $model)
                                            <option value="{{$model->id}}"> {{$model->name}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="statusidact">Estatus de proceso</label>
                                    <input type="text" readonly class="form-control" id="statusidact"
                                           name="statusidact">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="statusid">Estatus nuevo </label>
                                    <select name="statusid" class="form-control select2">
                                        <option value="" selected>Seleccione un estatus</option>
                                        @foreach($status as $model)
                                            <option value="{{$model->statusid}}"> {{$model->name}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="statusunit">Estatus de Caja</label>
                                    <input type="text" readonly class="form-control" id="statusunit"
                                           name="statusunit">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="unitstatus">Estatus nuevo </label>
                                    <select name="unitstatus" class="form-control select2">
                                        <option value="" selected>Seleccione un estatus</option>
                                        @foreach($unitstatus as $model)
                                            <option value="{{$model->id}}"> {{$model->name}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="shipmentid">Embarque</label>
                                    <input type="text" class="form-control" id="shipmentid"
                                           name="shipmentid">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="foliotruck">Folio de tracto</label>
                                    <input type="text" class="form-control" id="foliotruck" name="foliotruck">
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                                <button type="button" id="btnSave" class="btn btn-block btn-block btn-outline-success btn-block">
                                    <i class="fas fa-check mr-2"></i> Guardar
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
        // Configurar CSRF token para todas las peticiones AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Variable para controlar si debemos validar el blur
        var shouldValidateBlur = true;

        // Función para buscar datos del folio
        function searchFolioData() {
            var folioValue = $("#folionumber").val().trim();
            
            if (folioValue.length === 0) {
                // Solo mostrar error si debemos validar
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
                    $("#statusidact").val('');
                    $("#econumber").val('');
                    $("#carriername").val('');
                    $("#foliotruck").val('');
                    $("#operatorname").val('');
                    $("#razonid").val('');
                    $("#statusunit").val('');
                    $("#shipmentid").val('');
                    $("#barcode").val('');
                } else {
                    // Usar .val() en lugar de .attr("value") para actualizar correctamente
                    $("#boxnumber").val(values.Data.boxnumber);
                    $("#barcode").val(values.Data.barcode);
                    $("#trucknumber").val(values.Data.trucknumber);
                    $("#statusidact").val(values.Data.statusname);
                    $("#econumber").val(values.Data.econumberbox);
                    $("#folionumber").val(values.Data.folionumber);
                    $("#carriername").val(values.Data.carriername);
                    $("#foliotruck").val(values.Data.foliotruck);
                    $("#operatorname").val(values.Data.operatorname);
                    $("#razonid").val(values.Data.razonname);
                    $("#statusunit").val(values.Data.unitstatusname);
                    $("#shipmentid").val(values.Data.shipmentid);
                }
            }).fail(function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al consultar los datos del folio',
                    width: '300px'
                });
            });
        }

        // Vincular evento blur al campo folionumber
        $(document).on('blur', '#folionumber', function() {
            searchFolioData();
        });

        // Evento del botón de guardar
        $("#btnSave").click(function(e) {
            e.preventDefault();
            
            // Validar que se haya ingresado un folio
            if ($("#folionumber").val().trim().length === 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Debe ingresar un número de folio',
                    width: '300px'
                });
                return;
            }

            // Confirmar acción
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¿Deseas actualizar los datos de esta unidad?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, actualizar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Deshabilitar botón para evitar doble click
                    $("#btnSave").prop('disabled', true);

                    // Preparar datos del formulario
                    let form = document.getElementById('adjustmentUnitForm');
                    let formData = new FormData(form);

                    $.ajax({
                        url: '{{url("adjustmentunitstore")}}',
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Éxito!',
                                text: response.message || 'Registro actualizado correctamente',
                                showConfirmButton: true,
                                timer: 3000
                            }).then(() => {
                                // Deshabilitar validación temporal
                                shouldValidateBlur = false;
                                
                                // Limpiar todos los inputs del formulario
                                $('#folionumber').val('');
                                $('#carriername').val('');
                                $('#operatorname').val('');
                                $('#boxnumber').val('');
                                $('#econumber').val('');
                                $('#trucknumber').val('');
                                $('#foliotruck').val('');
                                $('#razonid').val('');
                                $('#statusidact').val('');
                                $('#statusunit').val('');
                                $('#shipmentid').val('');
                                $('#barcode').val('');
                                $('#barcodenew').val('');

                                // Resetear selects
                                $('select[name="razonstatusid"]').val('').trigger('change');
                                $('select[name="statusid"]').val('').trigger('change');
                                $('select[name="unitstatus"]').val('').trigger('change');
                                
                                // Enfocar el campo de folio para nueva captura
                                $('#folionumber').focus();
                                
                                // Reactivar validación después de un momento
                                setTimeout(function() {
                                    shouldValidateBlur = true;
                                }, 100);
                                
                                // Habilitar botón de guardar
                                $("#btnSave").prop('disabled', false);
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
                            
                            $("#btnSave").prop('disabled', false);
                        }
                    });
                }
            });
        });
    </script>
@endsection

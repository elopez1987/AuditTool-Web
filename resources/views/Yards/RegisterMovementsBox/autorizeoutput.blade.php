@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    Autoriza la salida de la unidad del patio.
                </div>
                <form id="autorizeOutputForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="card-input">
                                    <div class="row">
                                        <div class="col-lg-4 col-md-6 col-xs-12">
                                            <div class="form-group">
                                                <label for="folionumber">Número de Folio</label>
                                                <input type="text"
                                                       class="form-control @error('folionumber') is-invalid @enderror"
                                                       id="folionumber" required
                                                       name="folionumber" maxlength="20"
                                                       value="{{ old('folionumber') }}">
                                                @error('folionumber')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label for="barcode">Código de barra</label>
                                                <div class="input-group">
                                                    <input type="number" readonly class="form-control numeric"
                                                           id="barcode"
                                                           name="barcode"
                                                           value="{{ old('barcode') }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4 col-md-6  col-xs-12">
                                            <div class="form-group">
                                                <label for="linea">Línea de transporte</label>
                                                <input type="text" class="form-control" readonly id="linea"
                                                       name="linea"
                                                       value="{{ old('linea') }}"
                                                >
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6  col-xs-12">
                                            <div class="form-group">
                                                <label for="econumber">Eco. De caja</label>
                                                <input type="text" class="form-control" readonly id="econumber"
                                                       name="econumber"
                                                       value="{{ old('econumber') }}"
                                                >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-lg-4 col-md-6  col-xs-12">
                                            <div class="form-group">
                                                <label for="boxnumber">Placa De caja</label>
                                                <input type="text" class="form-control" readonly id="boxnumber"
                                                       name="boxnumber"
                                                       value="{{ old('boxnumber') }}"
                                                >
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6  col-xs-12">
                                            <div class="form-group">
                                                <label for="shipmentid">Embarque</label>
                                                <input type="text" class="form-control" readonly id="shipmentid"
                                                       name="shipmentid"
                                                       value="{{ old('shipmentid') }}"
                                                >
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @php
                                    $exists = !empty($parameter) && in_array('SWH-24', $parameter);
                                @endphp
                                @if ($exists)
                                    <div class="card-input">
                                        <div id="location">
                                            <div class="col-lg-12">
                                                <div class="row">
                                                    <div class="col-lg-4 col-md-6  col-xs-12 mt-2">
                                                        <div class="form-group">
                                                            <label for="zoneid">Zonas<span
                                                                        style="color: red">*</span></label>
                                                            <select name="zoneid" id='zoneid'
                                                                    class="form-control select2">
                                                                <option value="" selected>Seleccione una opción</option>
                                                                @foreach($zones as $model)
                                                                    <option
                                                                            value="{{$model->id}}" {{ old('zoneid') == $model->id ? 'selected' : '' }}> {{$model->name}} </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4 col-md-6  col-xs-12 mt-2">
                                                        <div class="form-group">
                                                            <label for="locationid">Ubicación<span
                                                                        style="color: red">*</span></label>
                                                            <select name="locationid" id='locationid'
                                                                    class="form-control select2">
                                                                <option value="" selected>Seleccione una opción</option>
                                                                @foreach($locations as $model)
                                                                    <option
                                                                            value="{{$model->id}}" {{ old('locationid') == $model->id ? 'selected' : '' }}> {{$model->name}} </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        @php
                            $exists = !empty($parameter) &&  in_array('SWH-40', $parameter);
                        @endphp
                        @if ($exists)
                            <div class="row mt-4">
                                <div class="col-lg-6">
                                    <div class="card-input">
                                        <table class="table table-condensed table-hover  table-bordered">
                                            <thead>
                                            <th>Descripción</th>
                                            <th>SI</th>
                                            <th>NO</th>
                                            </thead>
                                            <tbody>
                                                @foreach($checkAS as $item)
                                                    <tr>
                                                        <td class="hide"><input type="text"
                                                                                value="{{$item->checklistid}}"
                                                                                id="checklistid[]" name="checklistid[]"
                                                                                class="hide"></td>
                                                        <td class="hide"><input type="text" value="{{$item->id}}"
                                                                                id="checklitsdetailid[]"
                                                                                name="checklitsdetailid[]" class="hide">
                                                        </td>
                                                        <td>{{$item->description}}</td>
                                                        <td><input type="radio" id="response[]" @if($exists) required
                                                                   @endif value="1" name="response[]-{{$item->id}}">
                                                        </td>
                                                        <td><input type="radio" id="response[]" @if($exists) required
                                                                   @endif value="0" name="response[]-{{$item->id}}">
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="row mt-4">
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="">Comentario</label>
                                    <textarea class="form-control" rows="10" name="comment"
                                              placeholder="Ingrese un comentario corto..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="row">
                                <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                                    <button type="button" class="btn btn-block btn-block btn-outline-success btn-block"
                                            id="submitBtn">
                                        <i class="ti ti-check mr-2 "></i> Guardar
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
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('/theme/assets/libs/quill/dist/quill.snow.css')}}">
@endsection


@section('script')
    <script src="{{asset('/theme/assets/libs/quill/dist/quill.min.js')}}"></script>
    <script>
        // Configurar CSRF token para todas las peticiones AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
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
                        text: 'Ingrese el código de barra!',
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
                    // Limpiar campos si no se encuentra
                    $("#boxnumber").val('');
                    $("#shipmentid").val('');
                    $("#econumber").val('');
                    $("#linea").val('');
                    $("#barcode").val('');
                } else {

                    $("#boxnumber").val(values.Data.boxnumber);
                    $("#folionumber").val(values.Data.folionumber);
                    $("#shipmentid").val(values.Data.shipmentid);
                    $("#econumber").val(values.Data.econumberbox);
                    $("#linea").val(values.Data.carriername);
                    $("#barcode").val(values.Data.barcode);
                }
            }).fail(function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al consultar los datos del folio',
                });
            });
        }
        
        // Vincular evento blur al campo folionumber
        $(document).on('blur', '#folionumber', function() {
            searchFolioData();
        });

        $("#submitBtn").click(function(e) {
            e.preventDefault();
            
            // Validar formulario
            let form = document.getElementById('autorizeOutputForm');
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            // Deshabilitar botón para evitar doble click
            $("#submitBtn").prop('disabled', true);

            // Preparar datos del formulario
            let formData = new FormData(form);

            $.ajax({
                url: '{{url("registermovementbox")}}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: response.message || 'Datos guardados correctamente',
                        showConfirmButton: true,
                        timer: 3000
                    }).then(() => {
                        // Deshabilitar validación temporal
                        shouldValidateBlur = false;
                        
                        // Limpiar todos los inputs del formulario
                        $('#folionumber').val('');
                        $('#barcode').val('');
                        $('#linea').val('');
                        $('#econumber').val('');
                        $('#boxnumber').val('');
                        $('#shipmentid').val('');
                        $('textarea[name="comment"]').val('');
                        
                        // Limpiar radios buttons del checklist
                        $('input[type="radio"][id="response[]"]').prop('checked', false);
                        
                        // Resetear select de zonas
                        $('#zoneid').val('').trigger('change');
                        
                        // Resetear select de ubicaciones y limpiar opciones
                        $('#locationid').empty();
                        $('#locationid').append('<option value="" selected>Seleccione una opción</option>');
                        @foreach($locations as $model)
                            $('#locationid').append('<option value="{{$model->id}}">{{$model->name}}</option>');
                        @endforeach
                        $('#locationid').val('').trigger('change');
                        
                        // Enfocar el campo de folio para nueva captura
                        $('#folionumber').focus();
                        
                        // Reactivar validación después de un momento
                        setTimeout(function() {
                            shouldValidateBlur = true;
                        }, 100);
                        
                        // Habilitar botón de guardar
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
        });
    </script>
@endsection

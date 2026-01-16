@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-8 col-md-12">
            <div class="card">
                <div class="card-header">
                    Reubicación de caja.
                </div>
                <form id="moveBoxForm" method="POST" enctype="multipart/form-data">
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
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                                <button type="submit" id="btnSave" class="btn btn-block btn-block btn-outline-success btn-block">
                                    <i class="fas fa-save"></i> Guardar
                                </button>
                            </div>
                            <div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
                                <a href="{{route('home')}}" class="btn btn-block btn-block btn-outline-danger" style="margin-left: 10px">
                                    <i class="fas fa-times"></i> Cancelar
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

@endsection

@section('script')

    <script>
        $(document).ready(function() {
            // Evento para cargar ubicaciones según la zona seleccionada
            $(document).on('change', '#zoneid', function(event) {
                const zoneid = event.target.value;
                if (zoneid) {
                    $.get(`{{ url('storage-locations') }}/${zoneid}/search`, function (res) {
                        $("#locationid").empty();
                        $("#locationid").append('<option value="">Seleccione una opción</option>');
                        if (res.Data && Array.isArray(res.Data)) {
                            res.Data.forEach(element => {
                                $("#locationid").append(`<option value=${element.id}> ${element.name} </option>`);
                            });
                        }
                    }).fail(function(error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error al cargar las ubicaciones',
                            width: '400px',
                            confirmButtonText: 'Entendido'
                        });
                    });
                } else {
                    $("#locationid").empty();
                    $("#locationid").append('<option value="">Seleccione una opción</option>');
                }
            });
            // Evento para buscar datos del folio
            $(document).on('blur', '#folionumber', function () {
                const folioValue = $(this).val().trim();
                
                if (folioValue.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Atención',
                        text: 'Ingrese el numero de Folio o Etiqueta!',
                        width: '400px',
                        confirmButtonText: 'Entendido'
                    });
                } else {
                    $.getJSON('./receipt-units/' + folioValue + '/search', function (values) {
                        if (values.length === 0) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'No encontrado',
                                text: 'No se encuentran registros del folio!',
                                width: '400px',
                                confirmButtonText: 'Entendido'
                            });
                            // Limpiar campos si no se encuentra
                            $('#barcode').val('');
                            $('#statusid').val('');
                            $('#boxnumber').val('');
                            $('#containerseal').val('');
                            $('#econumber').val('');
                            $('#originzoneid').val('');
                            $('#originlocationid').val('');
                        } else {
                            // Llenar los campos con los datos obtenidos
                            $("#barcode").val(values.Data.barcode);
                            $("#boxnumber").val(values.Data.boxnumber);
                            $("#containerseal").val(values.Data.containerseal);
                            $("#originzoneid").val(values.Data.zoneorg);
                            $("#originlocationid").val(values.Data.locationorg);
                            $("#statusid").val(values.Data.statusname);
                            $("#econumber").val(values.Data.econumberbox);
                        }
                    }).fail(function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error al buscar el folio',
                            width: '400px',
                            confirmButtonText: 'Entendido'
                        });
                    });
                }
            });
        });
    </script>
    <script>
        const moveBoxForm = document.getElementById('moveBoxForm');
        
        moveBoxForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const btnSave = document.getElementById('btnSave');
            const originalContent = btnSave.innerHTML;
            
            // Deshabilitar botón durante el proceso
            btnSave.disabled = true;
            btnSave.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
            
            const formData = new FormData(this);
            
            try {
                const response = await fetch('{{ url("storeMoveBox") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });
                
                const data = await response.json();
                
                if (response.ok && data.success) {
                    // Mostrar mensaje de éxito
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: data.message,
                        timer: 2000,
                        width: '400px',
                        showConfirmButton: false,
                        iconColor: '#28a745'
                    });
                    
                    // Limpiar campos del formulario
                    $('#folionumber').val('');
                    $('#barcode').val('');
                    $('#statusid').val('');
                    $('#boxnumber').val('');
                    $('#containerseal').val('');
                    $('#econumber').val('');
                    $('#originzoneid').val('');
                    $('#originlocationid').val('');
                    $('#zoneid').val('').trigger('change');
                    $('#locationid').val('').trigger('change');
                    $('#unitstatusid').val('').trigger('change');
                    
                    // Enfocar en el campo de folio para siguiente captura
                    setTimeout(() => {
                        $('#folionumber').focus();
                    }, 2100);
                } else {
                    await Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Ocurrió un error al procesar la solicitud',
                        width: '400px'
                    });
                }
            } catch (error) {
                console.error('Error:', error);
                await Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error de conexión con el servidor',
                    width: '400px'
                });
            } finally {
                // Siempre rehabilitar el botón al final
                btnSave.disabled = false;
                btnSave.innerHTML = originalContent;
            }
        });
    </script>
@endsection

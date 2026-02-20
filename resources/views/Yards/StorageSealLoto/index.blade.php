@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Inventario de candados
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-lg-12">
                                @can('storagesealloto.create')
                                    <button type="button" id="btnOpenModal"
                                            class="btn waves-effect waves-light btn-outline-info btn-md">
                                        <img src="{{asset('/ico/save02.png')}}" alt="" class="img-16">
                                        <span > Agregar</span>
                                    </button>
                                @endcan
                                <a>
                                    @include('buttons.reloadajax')
                                </a>
                                <a href="{{route('home')}}">
                                    @include('buttons.cancel')
                                </a>
                            </div>
                        </div>
                        <hr class="mb-3">
                        <div class="row">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="name">Nombre</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                           aria-describedby="name">
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-12">
                            <table id="table-trucks" class="table table-bordered  table-condensed">
                                <thead>
                                <tr>
                                    <th class="bg-inverse text-white">C.Distribución</th>
                                    <th class="bg-inverse text-white">Nombre</th>
                                    <th class="bg-inverse text-white">Placa de caja</th>
                                    <th class="bg-inverse text-white">Auxiliar 01</th>
                                    <th class="bg-inverse text-white">Auxiliar 02</th>
                                    <th class="bg-inverse text-white">Auxiliar 03</th>
                                    <th class="bg-inverse text-white">Auxiliar 04</th>
                                    <th class="bg-inverse text-white">Auxiliar 05</th>
                                    <th class="bg-inverse text-white">Estatus</th>
                                    <th class="bg-inverse text-white">Opciones</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para crear candados -->
    <div class="modal fade" id="createSealModal" tabindex="-1" role="dialog" aria-labelledby="createSealModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createSealModalLabel">Crear candados</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="createSealForm">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="centerid">Centros de distribución <span
                                                style="color: red">*</span></label>
                                    <select name="centerid" id="centerid" class="form-control select2" required>
                                        <option value="">Seleccione un centro</option>
                                        @foreach($centers as $center)
                                            <option value="{{$center->centerid}}">{{$center->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="description">Prefijo <span style="color: red">*</span></label>
                                    <input type="text" class="form-control" required id="description" name="description"
                                           maxlength="50" style="text-transform: uppercase;">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="quantity">Cantidad de candados <span style="color: red">*</span></label>
                                    <input type="text" class="form-control numeric" required id="quantity"
                                           name="quantity" maxlength="3">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="value01">Auxiliar 01</label>
                                    <input type="text" class="form-control" id="value01" name="value01" maxlength="100">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="value02">Auxiliar 02</label>
                                    <input type="text" class="form-control" id="value02" name="value02" maxlength="100">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="value03">Auxiliar 03</label>
                                    <input type="text" class="form-control" id="value03" name="value03" maxlength="100">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="value04">Auxiliar 04</label>
                                    <input type="text" class="form-control" id="value04" name="value04" maxlength="100">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="value05">Auxiliar 05</label>
                                    <input type="text" class="form-control" id="value05" name="value05" maxlength="100">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn waves-effect waves-light btn-outline-secondary btn-sm " data-dismiss="modal">
                            <img src="{{asset('/ico/close.png')}}" alt="" class="img-16">
                            Cancelar
                        </button>
                        <button type="submit" id="btnSaveSeals" class="btn waves-effect waves-light btn-outline-secondary btn-sm ">
                            <img src="{{asset('/ico/accept.png')}}" alt="" class="img-16">
                            Aceptar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para editar candados -->
    <div class="modal fade" id="editSealModal" tabindex="-1" role="dialog" aria-labelledby="editSealModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editSealModalLabel">Editar candado</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editSealForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_seal_id" name="seal_id">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="edit_description">Nombre del sello <span style="color: red">*</span></label>
                                    <input type="text" class="form-control" required id="edit_description" name="description" maxlength="50" style="text-transform: uppercase;">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="edit_statusid">Estatus <span style="color: red">*</span></label>
                                    <select name="statusid" id="edit_statusid" class="form-control select2" required>
                                        @foreach($status as $statu)
                                            <option value="{{$statu->statusid}}">{{$statu->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="edit_value01">Auxiliar 01</label>
                                    <input type="text" class="form-control" id="edit_value01" name="value01" maxlength="100">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="edit_value02">Auxiliar 02</label>
                                    <input type="text" class="form-control" id="edit_value02" name="value02" maxlength="100">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="edit_value03">Auxiliar 03</label>
                                    <input type="text" class="form-control" id="edit_value03" name="value03" maxlength="100">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="edit_value04">Auxiliar 04</label>
                                    <input type="text" class="form-control" id="edit_value04" name="value04" maxlength="100">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="edit_value05">Auxiliar 05</label>
                                    <input type="text" class="form-control" id="edit_value05" name="value05" maxlength="100">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="edit_value06">Auxiliar 06</label>
                                    <input type="text" class="form-control" id="edit_value06" name="value06" maxlength="100">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn waves-effect waves-light btn-outline-secondary btn-sm" data-dismiss="modal">
                            <img src="{{asset('/ico/close.png')}}" alt="" class="img-16">
                            Cancelar
                        </button>
                        <button type="submit" id="btnUpdateSeal" class="btn waves-effect waves-light btn-outline-secondary btn-sm">
                            <img src="{{asset('/ico/accept.png')}}" alt="" class="img-16">
                            Aceptar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('script')
    @include('Yards.StorageSealLoto.partials.table')
    <script>
        // Configurar CSRF token para todas las peticiones AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function reloadtable() {
            $('#table-trucks').DataTable().ajax.reload();
        }

        // Abrir modal
        $('#btnOpenModal').click(function () {
            $('#createSealModal').modal('show');
        });

        // Convertir a mayúsculas el campo description
        $('#description').on('input', function () {
            this.value = this.value.toUpperCase();
        });

        // Enviar formulario por AJAX
        $('#createSealForm').submit(function (e) {
            e.preventDefault();

            // Validar formulario
            if (!this.checkValidity()) {
                this.reportValidity();
                return;
            }

            // Deshabilitar botón para evitar doble click
            $('#btnSaveSeals').prop('disabled', true);

            // Preparar datos del formulario
            let formData = new FormData(this);

            $.ajax({
                url: '{{url("storagesealloto")}}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    // Cerrar modal
                    $('#createSealModal').modal('hide');

                    // Mostrar mensaje de éxito
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: response.message || 'Sellos agregados correctamente',
                        timer: 3000
                    });

                    // Limpiar formulario
                    $('#createSealForm')[0].reset();
                    $('#centerid').val('').trigger('change');

                    // Recargar tabla
                    reloadtable();

                    // Habilitar botón
                    $('#btnSaveSeals').prop('disabled', false);
                },
                error: function (xhr) {
                    let errorMessage = 'Error al procesar la solicitud';

                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                        // Manejar errores de validación
                        let errors = xhr.responseJSON.errors;
                        errorMessage = Object.values(errors).flat().join('\n');
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
                        text: errorMessage
                    });

                    $('#btnSaveSeals').prop('disabled', false);
                }
            });
        });

        // Limpiar formulario al cerrar modal
        $('#createSealModal').on('hidden.bs.modal', function() {
            $('#createSealForm')[0].reset();
            $('#centerid').val('').trigger('change');
            $('#btnSaveSeals').prop('disabled', false);
        });

        // Abrir modal de edición y cargar datos
        $(document).on('click', '.btnEditSeal', function() {
            let sealId = $(this).data('id');
            
            // Mostrar loading
            Swal.fire({
                title: 'Cargando...',
                text: 'Obteniendo datos del candado',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });

            // Obtener datos del candado
            $.ajax({
                url: '{{url("storagesealloto")}}/' + sealId + '/edit',
                type: 'GET',
                success: function(response) {
                    Swal.close();
                    
                    // Llenar formulario con datos
                    $('#edit_seal_id').val(response.id);
                    $('#edit_description').val(response.description);
                    $('#edit_value01').val(response.value01 || '');
                    $('#edit_value02').val(response.value02 || '');
                    $('#edit_value03').val(response.value03 || '');
                    $('#edit_value04').val(response.value04 || '');
                    $('#edit_value05').val(response.value05 || '');
                    $('#edit_value06').val(response.value06 || '');
                    $('#edit_statusid').val(response.statusid).trigger('change');
                    
                    // Mostrar modal
                    $('#editSealModal').modal('show');
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudieron cargar los datos del candado'
                    });
                }
            });
        });

        // Convertir a mayúsculas el campo description en edición
        $('#edit_description').on('input', function() {
            this.value = this.value.toUpperCase();
        });

        // Enviar formulario de edición por AJAX
        $('#editSealForm').submit(function(e) {
            e.preventDefault();
            
            // Validar formulario
            if (!this.checkValidity()) {
                this.reportValidity();
                return;
            }

            let sealId = $('#edit_seal_id').val();
            
            // Deshabilitar botón para evitar doble click
            $('#btnUpdateSeal').prop('disabled', true);

            // Preparar datos del formulario
            let formData = new FormData(this);

            $.ajax({
                url: '{{url("storagesealloto")}}/' + sealId,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    // Cerrar modal
                    $('#editSealModal').modal('hide');
                    
                    // Mostrar mensaje de éxito
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: response.message || 'Registro actualizado correctamente',
                        timer: 3000
                    });
                    
                    // Recargar tabla
                    reloadtable();
                    
                    // Habilitar botón
                    $('#btnUpdateSeal').prop('disabled', false);
                },
                error: function(xhr) {
                    let errorMessage = 'Error al procesar la solicitud';
                    
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                        let errors = xhr.responseJSON.errors;
                        errorMessage = Object.values(errors).flat().join('\n');
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
                        text: errorMessage
                    });
                    
                    $('#btnUpdateSeal').prop('disabled', false);
                }
            });
        });

        // Limpiar formulario de edición al cerrar modal
        $('#editSealModal').on('hidden.bs.modal', function() {
            $('#editSealForm')[0].reset();
            $('#edit_statusid').val('').trigger('change');
            $('#btnUpdateSeal').prop('disabled', false);
        });
    </script>
@endsection

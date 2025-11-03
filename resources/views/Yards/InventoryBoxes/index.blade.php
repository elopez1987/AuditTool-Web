@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Inventario de cajas fisicas vs sistema
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-lg-12">
                                @can('inventorybox.create')
                                    <button type="button" id="btnOpenModal"
                                            class="btn waves-effect waves-light btn-outline-info btn-md">
                                        <img src="{{asset('/ico/save02.png')}}" alt="" class="img-16">
                                        <span style="font-size: 0.675rem"> Agregar</span>
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
                        <hr class="m-t-1">
                        <div class="row">
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="folio">Número de inventario</label>
                                    <input type="text" class="form-control numeric" id="folio" name="folio"
                                           onKeyUp=this.value=this.value.toUpperCase()
                                    >
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="statusid">Estatus</label>
                                    <select name="statusid" id="statusid" class="form-control select2">
                                        <option value="" selected>Seleccione una opción</option>
                                        @foreach($status as $model)
                                            <option value="{{$model->statusid}}"> {{$model->name}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-12">
                            <table id="table-boxes" class="table table-bordered table-condensed table-hover">
                                <thead >
                                <tr>
                                    <th>C.Distribución</th>
                                    <th>Folio de Inventario</th>
                                    <th>Tipo</th>
                                    <th>Descripción</th>
                                    <th>F.Inicio</th>
                                    <th>Cajas en patio</th>
                                    <th>Total contadas</th>
                                    <th>Estatus</th>
                                    <th>Opciones</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


{{--    Modal para crear un nuevo registro--}}
    <div class="modal fade" id="createInventoryModal" tabindex="-1" role="dialog" aria-labelledby="createSealModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createSealModalLabel">Crear inventario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="createInventoryForm">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="centerid">Centros de distribución <span style="color: red">*</span></label>
                                    <select name="centerid" id="centerid" class="form-control select2" required>
                                        <option value="">Seleccione un centro</option>
                                        @foreach($centers as $center)
                                            <option value="{{$center->centerid}}">{{$center->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="statustypeid">Tipo de inventario <span style="color: red">*</span></label>
                                    <select name="statustypeid" id="statustypeid" class="form-control select2" required>
                                        <option value="">Seleccione una opción</option>
                                        @foreach($types as $model)
                                            <option value="{{$model->statusid}}">{{$model->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="description">Nombre <span style="color: red">*</span></label>
                                    <input type="text" class="form-control" required id="description" name="description"
                                           maxlength="100" style="text-transform: uppercase;">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn waves-effect waves-light btn-outline-secondary btn-sm " data-dismiss="modal">
                            <img src="{{asset('/ico/close.png')}}" alt="" class="img-16">
                            Cancelar
                        </button>
                        <button type="submit" id="btnSave" class="btn waves-effect waves-light btn-outline-secondary btn-sm ">
                            <img src="{{asset('/ico/accept.png')}}" alt="" class="img-16">
                            Aceptar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection

@section('css')
@endsection

@section('script')
    @include('Yards.InventoryBoxes.partials.table')
    <script>
        function reloadtable() {
            $('#table-boxes').DataTable().ajax.reload();
        }
    </script>
    <script>
        $("#statusid").change(function () {
            reloadtable();
        });
    </script>

    <script>
        function activeInventory($id) {
            function reloadtable() {
                $('#table-boxes').DataTable().ajax.reload();
            }
            Swal.fire({
                icon: 'warning',
                title: '¿Estas seguro de realizar el cambio de estatus?',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#90a4ae',
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        data: {
                            id: $id,
                        },
                        url: '{!! route('activateInventory') !!}',
                        type: "POST",
                        dataType: 'json',
                        success: data => {
                            Swal.fire({
                                icon: 'success',
                                title: `Cambio de estatus realizado con exito.`,
                                width: 350,
                            })
                            reloadtable();
                        },
                    })

                }
            })
        }
    </script>

    <script>
        // Configurar CSRF token para todas las peticiones AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function reloadtable() {
            $('#table-boxes').DataTable().ajax.reload();
        }

        // Abrir modal
        $('#btnOpenModal').click(function () {
            $('#createInventoryModal').modal('show');
        });

        // Convertir a mayúsculas el campo description
        $('#description').on('input', function () {
            this.value = this.value.toUpperCase();
        });

        // Enviar formulario por AJAX
        $('#createInventoryForm').submit(function (e) {
            e.preventDefault();

            // Validar formulario
            if (!this.checkValidity()) {
                this.reportValidity();
                return;
            }

            // Deshabilitar botón para evitar doble click
            $('#btnSave').prop('disabled', true);

            // Preparar datos del formulario
            let formData = new FormData(this);

            $.ajax({
                url: '{{url("inventorybox")}}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    // Cerrar modal
                    $('#createInventoryModal').modal('hide');

                    // Mostrar mensaje de éxito
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: response.message || 'Sellos agregados correctamente',
                        timer: 3000
                    });

                    // Limpiar formulario
                    $('#createInventoryForm')[0].reset();
                    $('#centerid').val('').trigger('change');
                    $('#statustypeid').val('').trigger('change');

                    // Recargar tabla
                    reloadtable();

                    // Habilitar botón
                    $('#btnSave').prop('disabled', false);
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

                    $('#btnSave').prop('disabled', false);
                }
            });
        });

        // Limpiar formulario al cerrar modal
        $('#createInventoryModal').on('hidden.bs.modal', function() {
            $('#createInventoryForm')[0].reset();
            $('#centerid').val('').trigger('change');
            $('#statustypeid').val('').trigger('change');
            $('#btnSave').prop('disabled', false);
        });
    </script>

    <script>
        // SweetAlert2 delete handler
        $(document).on('click', '.btn-delete-inventory', function(e) {
            e.preventDefault();
            const inventoryId = $(this).data('id');

            Swal.fire({
                title: '¿Estás seguro?',
                text: '¿Deseas eliminar este registro?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Eliminando...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Send AJAX request
                    $.ajax({
                        url: `{{url('inventorybox')}}/${inventoryId}`,
                        type: 'DELETE',
                        data: {
                            _token: '{{csrf_token()}}'
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Eliminado!',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
                            // Reload DataTable
                            $('#table-boxes').DataTable().ajax.reload();
                        },
                        error: function(xhr) {
                            const message = xhr.responseJSON?.message || 'Error al eliminar el registro';
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: message
                            });
                        }
                    });
                }
            });
        });
    </script>

@endsection

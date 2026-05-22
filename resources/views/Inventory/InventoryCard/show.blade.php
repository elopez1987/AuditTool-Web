@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Detalle de ubicaciones
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form id="inventoryLocationForm" method="POST" action="{{url('create-location/' . $inventorycard->id)}}">
                                {{ method_field('PUT') }}
                                @csrf
                                <div class="row">
                                    <div class="col-12">
                                        @include('buttons.save')
                                        @include('buttons.reloadajax')
                                        <a href="{{route('inventorycards.index')}}">
                                            @include('buttons.back')
                                        </a>
                                    </div>
                                </div>
                                <hr class="mb-3">
                                <div class="row">
                                    <x-text-input
                                            name="name"
                                            label="Nombre de la ubicación"
                                            required
                                            uppercase
                                    />
                                    <x-select2
                                            name="inventoryunitsmeasure_id"
                                            label="Unidad de medida para conteo"
                                            :options="$unitmeasures"
                                            text-field="description"
                                            required
                                    />
                                </div>
                            </form>
                        </div>
                    </div>
                    <hr class="mb-3 mt-3">

                    <div class="row">
                        <div class="col-lg-12">
                                <table width="100%" id="tableInventoryLocation" class="table table-bordered table-condensed ">
                                    <thead>
                                        <tr>
                                            <th>Ubicación</th>
                                            <th>Unidad de medida de conteo</th>
                                            <th>Auxiliar 01</th>
                                            <th>Auxiliar 02</th>
                                            <th>Auxiliar 03</th>
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

    <div class="modal fade" id="inventoryLocationEditModal" tabindex="-1" role="dialog"
         aria-labelledby="inventoryLocationEditModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="inventoryLocationEditModalLabel">Editar ubicación</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="inventoryLocationEditForm" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_inventory_location_id" name="id">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_name">Nombre de la ubicación</label>
                                    <input type="text" class="form-control" id="edit_name" name="name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_inventoryunitsmeasure_id">Unidad de medida para conteo</label>
                                    <select class="form-control select2" id="edit_inventoryunitsmeasure_id" name="inventoryunitsmeasure_id" required>
                                        <option value="">Seleccione una opción</option>
                                        @foreach($unitmeasures as $unitmeasure)
                                            <option value="{{ $unitmeasure->id }}">{{ $unitmeasure->description }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_statusid">Estatus</label>
                                    <select class="form-control select2" id="edit_statusid" name="statusid">
                                        <option value="">Seleccione una opción</option>
                                        @foreach($status as $model)
                                            <option value="{{ $model->statusid }}">{{ $model->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="edit_auxiliary01">Auxiliar 01</label>
                                    <input type="text" class="form-control" id="edit_auxiliary01" name="auxiliary01">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="edit_auxiliary02">Auxiliar 02</label>
                                    <input type="text" class="form-control" id="edit_auxiliary02" name="auxiliary02">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="edit_auxiliary03">Auxiliar 03</label>
                                    <input type="text" class="form-control" id="edit_auxiliary03" name="auxiliary03">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" id="submitBtnUpdateLocation" class="btn btn-outline-info">Guardar cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @include('Inventory.InventoryLocation.partials.tableforcard')
    <script>
        const inventoryLocationBaseUrl = "{{ url('inventorylocations') }}";
        function reloadtable() {
            $('#tableInventoryLocation').DataTable().ajax.reload();
        }
        function getAjaxErrorMessage(xhr, defaultMessage) {
            let message = defaultMessage;

            if (xhr.responseJSON) {
                if (xhr.responseJSON.errors) {
                    const firstKey = Object.keys(xhr.responseJSON.errors)[0];
                    if (firstKey && xhr.responseJSON.errors[firstKey].length > 0) {
                        message = xhr.responseJSON.errors[firstKey][0];
                    }
                } else if (xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
            }

            return message;
        }

        function openEditLocationModal(locationId) {
            $.ajax({
                url: inventoryLocationBaseUrl + '/' + locationId,
                type: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function (response) {
                    const location = response.data || {};

                    $('#edit_inventory_location_id').val(location.id || '');
                    $('#edit_name').val(location.name || '');
                    $('#edit_inventoryunitsmeasure_id').val(location.inventoryunitsmeasure_id || '').trigger('change');
                    $('#edit_statusid').val(location.statusid || '').trigger('change');
                    $('#edit_auxiliary01').val(location.auxiliary01 || '');
                    $('#edit_auxiliary02').val(location.auxiliary02 || '');
                    $('#edit_auxiliary03').val(location.auxiliary03 || '');

                    $('#inventoryLocationEditModal').modal('show');
                },
                error: function (xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: getAjaxErrorMessage(xhr, 'No fue posible cargar la ubicación')
                    });
                }
            });
        }

        function openDestroyLocationModal(locationId, actionType = 'delete', sourceElement = null) {
            const isRestore = actionType === 'restore';
            const confirmText = isRestore ? '¿Deseas restaurar este registro?' : '¿Deseas eliminar este registro?';
            const successText = isRestore ? 'Ubicación restaurada con éxito' : 'Ubicación eliminada con éxito';
            const errorText = isRestore ? 'Error al restaurar la ubicación' : 'Error al eliminar la ubicación';

            if (sourceElement && sourceElement.dataset.processing === 'true') {
                return;
            }
            Swal.fire({
                text: confirmText,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (!result.isConfirmed) {
                    return;
                }
                if (sourceElement) {
                    sourceElement.dataset.processing = 'true';
                    sourceElement.style.pointerEvents = 'none';
                    sourceElement.style.opacity = '0.5';
                }

                $.ajax({
                    url: inventoryLocationBaseUrl + '/' + locationId,
                    type: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    success: function (response) {
                        Swal.fire({
                            icon: 'success',
                            text: response.message || successText,
                            timer: 2000,
                            showConfirmButton: false
                        });
                        reloadtable();
                    },
                    error: function (xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: getAjaxErrorMessage(xhr, errorText)
                        });
                    },
                    complete: function () {
                        if (sourceElement) {
                            sourceElement.dataset.processing = 'false';
                            sourceElement.style.pointerEvents = '';
                            sourceElement.style.opacity = '';
                        }
                    }
                });
            });
        }

        $('#inventoryLocationForm').on('submit', function (e) {
            e.preventDefault();

            const form = $(this);
            const submitBtn = $('#submitBtnSave');

            submitBtn.prop('disabled', true);

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(),
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        text: response.message || 'Ubicación creada con éxito',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    form.trigger('reset');
                    $('select[name=inventoryunitsmeasure_id]').val(null).trigger('change');
                    reloadtable();
                },
                error: function (xhr) {

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: getAjaxErrorMessage(xhr, 'Error al guardar la ubicación')
                    });
                },
                complete: function () {
                    submitBtn.prop('disabled', false);
                }
            });
        });

        $('#inventoryLocationEditForm').on('submit', function (e) {
            e.preventDefault();

            const form = $(this);
            const submitBtn = $('#submitBtnUpdateLocation');
            const locationId = $('#edit_inventory_location_id').val();

            submitBtn.prop('disabled', true);

            $.ajax({
                url: inventoryLocationBaseUrl + '/' + locationId,
                type: 'POST',
                data: form.serialize(),
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function (response) {
                    $('#inventoryLocationEditModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        text: response.message || 'Ubicación actualizada con éxito',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    reloadtable();
                },
                error: function (xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: getAjaxErrorMessage(xhr, 'Error al actualizar la ubicación')
                    });
                },
                complete: function () {
                    submitBtn.prop('disabled', false);
                }
            });
        });

        $('#inventoryLocationEditModal').on('hidden.bs.modal', function () {
            const form = $('#inventoryLocationEditForm');
            form.trigger('reset');
            $('#edit_inventory_location_id').val('');
            $('#edit_inventoryunitsmeasure_id').val(null).trigger('change');
            $('#edit_statusid').val(null).trigger('change');
        });
    </script>
@endsection


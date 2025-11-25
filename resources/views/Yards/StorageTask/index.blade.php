@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Tareas a operadores
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-md-12">
                                @can('storagetask.create')
                                    <a href="javascript:void(0)" data-toggle="modal" data-target="#modal-create-task">
                                        @include('buttons.add')
                                    </a>
                                @endcan
                                <a href="">
                                    @include('buttons.search')
                                </a>
                                <a >
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
                                    <label for="folionumber">Folio de patios</label>
                                    <input type="text" class="form-control" id="folionumber" name="folionumber"
                                           onKeyUp=this.value=this.value.toUpperCase()
                                    >
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="barcode">Código de barra</label>
                                    <input type="text" class="form-control" id="barcode" name="barcode"
                                           onKeyUp=this.value=this.value.toUpperCase()
                                    >
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="boxnumber">Placa de caja</label>
                                    <input type="text" class="form-control" id="boxnumber" name="boxnumber"
                                           onKeyUp=this.value=this.value.toUpperCase()
                                    >
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="statusid">Estatus <span style="color: red">*</span></label>
                                    <select name="statusid" class="form-control select2">
                                        @foreach($status as $model)
                                            <option value="{{$model->statusid}}"> {{$model->name}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        </div>
                    </form>

                    <div class="row">
                        <div class="col-lg-12">
                            <table id="table-trucks"  class="table table-bordered  table-condensed table-hover">
                                <thead>
                                <tr>
                                    <th class="bg-inverse text-white">C.Distribución</th>
                                    <th class="bg-inverse text-white">Bodega</th>
                                    <th class="bg-inverse text-white">Asignado a</th>
                                    <th class="bg-inverse text-white">Folio</th>
                                    <th class="bg-inverse text-white">C.Barra</th>
                                    <th class="bg-inverse text-white">Placa de Caja</th>
                                    <th class="bg-inverse text-white">Eco. de Caja</th>
                                    <th class="bg-inverse text-white">Operador</th>
                                    <th class="bg-inverse text-white">Tipo de Movimiento</th>
                                    <th class="bg-inverse text-white">Zona Destino</th>
                                    <th class="bg-inverse text-white">Ubicación Destino</th>
                                    <th class="bg-inverse text-white">Ubicación Actual</th>
                                    <th class="bg-inverse text-white">Estatus</th>
                                    <th class="bg-inverse text-white">F.Creación</th>
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

    @can('storagetask.create')
        @include('Yards.StorageTask.partials.modalCreate')
    @endcan

@endsection

@section('script')
    @include('Yards.StorageTask.partials.table')
    <script>
        $(document).ready(function() {
            const $modal = $('#modal-create-task');
            
            // Initialize when modal is shown
            $modal.on('shown.bs.modal', function() {
                // Initialize Select2 for modal selects
                $('#typeid_modal').select2({ dropdownParent: $modal });
                $('#patieroid_modal').select2({ dropdownParent: $modal });
                $('#zoneid_modal').select2({ dropdownParent: $modal });
                $('#locationid_modal').select2({ dropdownParent: $modal });
                
                // Load zones
                if ($('#zoneid_modal option').length <= 1) {
                    $.getJSON('{{ url('getZonesForCreateTask') }}', function(zones) {
                        const $zone = $('#zoneid_modal');
                        $zone.empty().append('<option value="">Seleccione una Zona</option>');
                        zones.forEach(z => $zone.append(`<option value="${z.id}">${z.name}</option>`));
                    }).fail(function() {
                        console.error('Error loading zones');
                    });
                }
                
                // Load patieros
                if ($('#patieroid_modal option').length <= 1) {
                    $.getJSON('{{ url('getPatierosForCreateTask') }}', function(users) {
                        const $patiero = $('#patieroid_modal');
                        $patiero.empty().append('<option value="">Seleccione una opción</option>');
                        users.forEach(u => $patiero.append(`<option value="${u.id}">${u.name}</option>`));
                    }).fail(function() {
                        console.error('Error loading patieros');
                    });
                }
            });

            // Toggle patiero visibility
            function togglePatiero() {
                const type = $('#typeid_modal').val();
                if (type === '2') {
                    $('#patiero_modal').show();
                } else {
                    $('#patiero_modal').hide();
                }
            }
            
            $(document).on('change', '#typeid_modal', togglePatiero);

            // Load locations on zone change
            $(document).on('change', '#zoneid_modal', function() {
                const zoneId = $(this).val();
                const $loc = $('#locationid_modal');
                $loc.empty().append('<option value="">Seleccione una ubicación</option>');
                
                if (!zoneId) return;
                
                $.getJSON(`{{ url('getLocationForZone') }}/${zoneId}`, function(locs) {
                    locs.forEach(l => $loc.append(`<option value="${l.id}">${l.name}</option>`));
                }).fail(function() {
                    console.error('Error loading locations');
                });
            });

            // Search invoice by folio
            $(document).on('click', '#searchInvoiceModal', function() {
                const folio = $('#folionumber_modal').val();
                
                if (!folio) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ingrese el número de Folio o Etiqueta!'
                    });
                    return;
                }

                $.getJSON('./receipt-units/' + folio + '/search', function (values) {
                    if (!values || values.length === 0) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'No se encuentran registros del folio!'
                        });
                        return;
                    }
                    
                    const v = values.Data;
                    $('#boxnumber_modal').val(v.boxnumber || '');
                    $('#containerseal_modal').val(v.containerseal || '');
                    $('#originzoneid_modal').val(v.zoneorg || '');
                    $('#originlocationid_modal').val(v.locationorg || '');
                    $('#operatorname_modal').val(v.operatorname || '');
                    $('#phone_modal').val(v.phone || '');
                    $('#econumber_modal').val(v.econumberbox || '');
                    $('#barcode_modal').val(v.barcode || '');
                    $('#linea_modal').val(v.carriername || '');
                }).fail(function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al buscar el folio'
                    });
                });
            });

            // Submit form via AJAX
            $(document).on('submit', '#form-create-task', function(e) {
                e.preventDefault();
                const formData = $(this).serialize();

                Swal.fire({
                    title: 'Guardando...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                $.ajax({
                    url: `{{ url('storagetask') }}`,
                    method: 'POST',
                    data: formData,
                    success: function(resp) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Éxito',
                            text: resp.message,
                            timer: 1800,
                            showConfirmButton: false
                        });
                        
                        $('#modal-create-task').modal('hide');
                        
                        // Reload table if present
                        if ($.fn.DataTable.isDataTable('#table-trucks')) {
                            $('#table-trucks').DataTable().ajax.reload();
                        }
                    },
                    error: function(xhr) {
                        const msg = xhr.responseJSON?.message || 'Error al crear la tarea';
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: msg
                        });
                    }
                });
            });

            // Reset form when modal is closed
            $modal.on('hidden.bs.modal', function() {
                $('#form-create-task')[0].reset();
                $('#locationid_modal').empty().append('<option value="">Seleccione una ubicación</option>');
                $('#patiero_modal').hide();
                
                // Destroy Select2 instances
                $('#typeid_modal').select2('destroy');
                $('#patieroid_modal').select2('destroy');
                $('#zoneid_modal').select2('destroy');
                $('#locationid_modal').select2('destroy');
            });
        });
    </script>

    <script>
        // SweetAlert2 delete handler
        $(document).on('click', '.btn-delete-task', function(e) {
            e.preventDefault();
            const taskId = $(this).data('id');

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
                        url: `{{url('storagetask')}}/${taskId}`,
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
                            $('#table-trucks').DataTable().ajax.reload();
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

@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card-header">
                <div class="card-title">
                    <h4 class="text-dark">Asignar facilitadores</h4>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <a href="{{url('inventoryheads/' . $inventoryhead->inventory_uuid)}}">
                        <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md">
                            <img src="{{asset('/ico/back.png')}}" alt="" class="img-16 ml-1">
                            <span class="text-button ml-3 mr-3"> Salir</span>
                        </button>
                    </a>
                </div>
            </div>

        </div>
    </div>

    <hr class="mb-3">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                  <div class="card-title">
                      Selecciona los usuarios para asignarlos como facilitadores.
                  </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <form method="POST" action="{{url('save-users-supervisors/' . $inventoryhead->id)}}" id="form-add-users-supervisors">
                                {{ method_field('PUT') }}
                                @csrf
                                <div class="row">
                                    <div class="col-2">
                                        <button type="submit" class="btn waves-effect waves-light btn-outline-success btn-block btn-md" id="btn-save-users">
                                            <img src="{{asset('/ico/save02.png')}}" alt="" class="img-16">
                                            <span class="text-button"> Guardar</span>
                                        </button>
                                    </div>
                                </div>
                                <hr class="mb-3">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <select multiple="multiple" size="30"  class="duallistbox-with-filter" name="user_id[]" id="user_id" required>
                                            @foreach($users as $user)
                                                <option value="{{$user->id}}" style="font-size: 14px !important;text-transform: uppercase">
                                                    {{$user->name}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-lg-6">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <table class="table table-bordered table-condensed " id="table-supervisors-users" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>Usuario</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

     @include('layouts.Partials.Errors')
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('theme/assets/libs/bootstrap-duallistbox/dist/bootstrap-duallistbox.min.css')}}">
@endsection

@section('script')
    <script src="{{asset('theme/assets/libs/bootstrap-duallistbox/dist/jquery.bootstrap-duallistbox.min.js')}}"></script>

    <script>

        // ── Duallistbox ───────────────────────────────────────────────────────
        $('.duallistbox-with-filter').bootstrapDualListbox({
            nonSelectedListLabel: 'Usuarios disponibles:',
            selectedListLabel: 'Seleccionados',
            preserveSelectionOnMove: 'Mover',
            moveOnSelect: false,
        });

        $(function () {
            $('.moveall i').removeClass().addClass('fas fa-angle-right');
            $('.removeall i').removeClass().addClass('fas fa-angle-left');
            $('.move i').removeClass().addClass('fas fa-angle-right');
            $('.remove i').removeClass().addClass('fas fa-angle-left');
        });

        // ── Helper de errores AJAX ────────────────────────────────────────────
        function getAjaxErrorMessage(xhr, defaultMessage) {
            var message = defaultMessage;
            if (xhr.responseJSON) {
                if (xhr.responseJSON.errors) {
                    var firstKey = Object.keys(xhr.responseJSON.errors)[0];
                    if (firstKey && xhr.responseJSON.errors[firstKey].length > 0) {
                        message = xhr.responseJSON.errors[firstKey][0];
                    }
                } else if (xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
            }
            return message;
        }

        // ── Refrescar duallistbox con usuarios disponibles ───────────────────
        function refreshSupervisorDualListbox() {
            $.ajax({
                url: '{{ route("getAvailableUsersForInventory", $inventoryhead->id) }}',
                type: 'GET',
                headers: {'Accept': 'application/json'},
                success: function (response) {
                    var select = $('#user_id');
                    select.empty();

                    (response.data || []).forEach(function (user) {
                        select.append(
                            $('<option>', {
                                value: user.id,
                                text:  user.name,
                                style: 'font-size:14px;text-transform:uppercase'
                            })
                        );
                    });

                    select.bootstrapDualListbox('refresh', true);
                },
                error: function (xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: getAjaxErrorMessage(xhr, 'Error al actualizar la lista de usuarios.')
                    });
                }
            });
        }

        // ── DataTable de facilitadores asignados ──────────────────────────────
        var supervisorTable = initDataTable('#table-supervisors-users', {
            serverSide: false,
            searching: false,
            ajax: {
                url: '{{ route("getSupervisorInventory", $inventoryhead->id) }}',
                type: 'GET',
            },
            columns: [
                {
                    data: 'user',
                    render: function (user) {
                        return user ? user.name : '-';
                    }
                },
                {data: 'btn', orderable: false, searchable: true}
            ]
        });

        // ── Guardar facilitadores desde el duallistbox ────────────────────────
        $('#form-add-users-supervisors').on('submit', function (e) {
            e.preventDefault();

            var form = $(this);
            var submitBtn = $('#btn-save-users');

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
                        text: response.message || 'Facilitadores asignados con éxito.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    supervisorTable.ajax.reload(null, false);
                    refreshSupervisorDualListbox();
                },
                error: function (xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: getAjaxErrorMessage(xhr, 'Error al guardar los facilitadores.')
                    });
                },
                complete: function () {
                    submitBtn.prop('disabled', false);
                }
            });
        });

        // ── Eliminar facilitador ──────────────────────────────────────────────
        function deleteSupervisorInventory(id) {
            Swal.fire({
                title: '¿Eliminar facilitador?',
                text: 'Esta acción no se puede deshacer.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then(function (result) {
                if (!result.isConfirmed) return;

                $.ajax({
                    url: '{{ url("deleteSupervisorInventory") }}/' + id,
                    type: 'DELETE',
                    data: {_token: '{{ csrf_token() }}'},
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    success: function (response) {
                        Swal.fire({
                            icon: 'success',
                            text: response.message || 'Facilitador eliminado con éxito.',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        supervisorTable.ajax.reload(null, false);
                        refreshSupervisorDualListbox();
                    },
                    error: function (xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: getAjaxErrorMessage(xhr, 'Error al eliminar el facilitador.')
                        });
                    }
                });
            });
        }

    </script>

@endsection


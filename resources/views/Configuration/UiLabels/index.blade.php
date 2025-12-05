@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Diccionario de labels
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-md-12">
                                @can('label-dictionary.create')
                                    <a href="javascript:void(0)" id="btn-create-label">
                                        @include('buttons.add')
                                    </a>
                                @endcan
                                <a>
                                    @include('buttons.reloadajax')
                                </a>
                                <a href="{{route('menuconfiguration')}}">
                                    @include('buttons.cancel')
                                </a>
                            </div>
                        </div>
                        <hr class="mb-3">
                    </form>
                    <div class="card-input">
                        <div class="row">
                            <div class="col-lg-12 col-xs-12">
                                <table id="table-label-dictionary" class="table table-bordered  table-condensed table-hover">
                                    <thead>
                                        <tr>
                                            <th>{{ ui_label('module_name','Modulo') }} </th>
                                            <th>{{ ui_label('field_name','Nombre del campo') }} </th>
                                            <th>{{ui_label('label_text','Nombre de etiqueta')}}</th>
                                            <th>{{ui_label('type_input','Tipo de dato')}}</th>
                                            <th>{{ui_label('visibled_input','¿Visible?')}}</th>
                                            <th>{{ui_label('required_input','¿Requerido?')}}</th>
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
    </div>

    <!-- Modal para Crear/Editar -->
    <div class="modal fade" id="labelModal" tabindex="-1" role="dialog" aria-labelledby="labelModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="labelModalLabel">Nuevo Label</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="modal-body-content">
                    <!-- El contenido se cargará dinámicamente -->
                </div>
            </div>
        </div>
    </div>

@endsection

@section('css')

@endsection

@section('script')
    @include('Configuration.UiLabels.partials.table')
    
    <script>
        function reloadtable() {
            $('#table-label-dictionary').DataTable().ajax.reload();
        }

        // Abrir modal para crear
        $('#btn-create-label').on('click', function() {
            $('#labelModalLabel').text('Crear Nueva Etiqueta');
            $.ajax({
                url: '{{ route('label-dictionary.create') }}',
                type: 'GET',
                success: function(response) {
                    $('#modal-body-content').html(response);
                    $('#labelModal').modal('show');
                },
                error: function() {
                    Swal.fire('Error', 'No se pudo cargar el formulario', 'error');
                }
            });
        });

        // Abrir modal para editar
        function editLabel(id) {
            $('#labelModalLabel').text('Editar Etiqueta');
            $.ajax({
                url: '/label-dictionary/' + id + '/edit',
                type: 'GET',
                success: function(response) {
                    $('#modal-body-content').html(response);
                    $('#labelModal').modal('show');
                },
                error: function() {
                    Swal.fire('Error', 'No se pudo cargar el formulario', 'error');
                }
            });
        }

        // Eliminar registro
        function deleteLabel(id) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Esta acción no se puede deshacer",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/label-dictionary/' + id,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire('Eliminado', response.message, 'success');
                                $('#table-label-dictionary').DataTable().ajax.reload();
                            } else {
                                Swal.fire('Error', response.message, 'error');
                            }
                        },
                        error: function(xhr) {
                            Swal.fire('Error', 'No se pudo eliminar el registro', 'error');
                        }
                    });
                }
            });
        }
    </script>
@endsection

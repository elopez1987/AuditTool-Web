@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    REPORTAR INCIDENCIA EN ENTREGA
                </div>
                <div class="card-body">
                    <form method="GET" id="search" role="form">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-md-12">
                                <a href="{{route('storageincidencesjourneys.create')}}">
                                    @include('buttons.add')
                                </a>
                                <a>
                                    @include('buttons.reloadajax')
                                </a>
                                <a href="{{route('menushipment')}}">
                                    @include('buttons.cancel')
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header"></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-10 col-xs-12">
                            <table id="table-monitorincidences"
                                   class="table table-bordered table-hover table-condensed">
                                <thead>
                                <tr>
                                    <th>Embarque</th>
                                    <th>Transporte</th>
                                    <th>Cliente</th>
                                    <th>Ciudad</th>
                                    <th>Cita de Carga</th>
                                    <th>Cita de descarga</th>
                                    <th>Fecha de alta</th>
                                    <th>Tiempo sin respuesta</th>
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

@endsection

@section('css')
    <link rel="stylesheet"
@endsection

@section('script')
    @include('Shipments.StorageIncidenceJourneys.partials.table')
    <script>
        function reloadtable() {
            $('#table-monitorincidences').DataTable().ajax.reload();
        }
    </script>
    <script>
        // SweetAlert2 delete handler
        $(document).on('click', '.btn-delete-location', function (e) {
            e.preventDefault();
            const locationId = $(this).data('id');

            Swal.fire({
                // title: '¿Estás seguro?',
                text: '¿Deseas cambiar el estatus de este registro?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, confirmar',
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
                        url: `{{url('storageincidencesjourneys')}}/${locationId}`,
                        type: 'DELETE',
                        data: {
                            _token: '{{csrf_token()}}'
                        },
                        success: function (response) {
                            Swal.fire({
                                icon: 'success',
                                // title: '¡Eliminado!',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
                            // Reload DataTable
                            $('#table-monitorincidences').DataTable().ajax.reload();
                        },
                        error: function (xhr) {
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

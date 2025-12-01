@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Contactos de lineas de transporte
                </div>

                <div class="card-body">
                    <div class="card-input">
                        <form id="formAddContact" method="POST"
                              action="{{url('shipmentcarriers-contacts/' . $model->id)}}"
                              enctype="multipart/form-data">
                            {{ method_field('PUT') }}
                            @csrf
                            <div class="row">
                                <div class="col-12">
                                    @include('buttons.save')
                                    <a href="{{route('shipmentcarriers.index')}}">
                                        @include('buttons.back')
                                    </a>
                                </div>
                            </div>
                            <hr class="mt-4">
                            <div class="row">
                                <div class="col-lg-2 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="name">Linea de transporte</label>
                                        <input type="text" class="form-control" readonly value="{{$model->name}}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-2 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="name">Nombre</label>
                                        <input type="text" class="form-control" id="name" name="name" maxlength="100">
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="email">Correo electronico</label>
                                        <input type="text" class="form-control" id="email" name="email" maxlength="100">
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="phone">Número de celular</label>
                                        <input type="text" class="form-control numeric" id="phone" name="phone"
                                               maxlength="10">
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="row">
                            <div class="col-md-10">
                                <table id="table-contacts-carriers" data-carrier-id="{{ $model->id }}"
                                       class="table  table-bordered table-condensed tablegeneral">
                                    <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Correo electronico</th>
                                        <th>Número de contacto</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">

                </div>
            </div>
        </div>
    </div>
    @include('layouts.Partials.Errors')
@endsection

@section('css')

@endsection

@section('script')
    <script>
        $(document).ready(function () {
            // Interceptar el submit del formulario para agregar contacto
            $("#formAddContact").on("submit", function (e) {
                e.preventDefault();

                const $form = $(this);
                const url = $form.attr("action");
                const formData = new FormData(this);

                $.ajax({
                    url: url,
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: 'Contacto agregado correctamente',
                            timer: 2000,
                            showConfirmButton: false
                        });

                        // Limpiar el formulario
                        $form[0].reset();

                        // Recargar la tabla
                        $('#table-contacts-carriers').DataTable().ajax.reload();
                    },
                    error: function (xhr) {
                        let errorMessage = 'Error al agregar el contacto';

                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            const errors = xhr.responseJSON.errors;
                            errorMessage = Object.values(errors).flat().join('<br>');
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            html: errorMessage
                        });
                    }
                });
            });
        });

        let carrierId = $("#table-contacts-carriers").data("carrier-id");
        let oTable = $("#table-contacts-carriers").DataTable({
            "paging": true,
            "searching": true,
            "ordering": true,
            "sScrollY": "450px",
            // "sScrollX": true,
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "colReorder": true,
            "stateSave": true,
            "stateDuration": 0,
            "ajax": {
                url: '{{ route("contacts-carriers") }}',
                data: function (d) {
                    d.carrierid = carrierId;
                }
            },
            "columns": [
                {data: 'name'},
                {data: 'email'},
                {data: 'phone'},
                {data: 'btn'},
            ],
            "dom": '<"row mb-3"<"col-sm-4"B><"col-sm-4 d-flex justify-content-center"l><"col-sm-4 d-flex justify-content-end"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',

            "lengthMenu": [[5, 10, 30, 50, 100, 150, 200, 250, -1], [5, 10, 30, 50, 100, 150, 200, 250, "Todos"]],

            buttons: [
                {
                    "extend": "excel",
                    "text": "<img src=\"{{asset('/pics/adexportar.png')}}\"  alt=''><span  style='font-size: 11px;'> Excel</span>",
                    "className": "btn btn-outline-info btn-md  btn-bold",
                }
            ],
            language: {
                "decimal": "",
                "emptyTable": "No hay información",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
                "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                "infoPostFix": "",
                "thousands": ",",
                "lengthMenu": "Mostrar _MENU_ Entradas",
                "loadingRecords": "Cargando...",
                "processing": '<i class="fa fa-spinner fa-spin fa-4x fa-fw text-info"></i><span class="sr-only">Loading...</span> ',
                "search": "Buscar:",
                "zeroRecords": "Sin resultados encontrados",
                "paginate": {
                    "first": "Primero",
                    "last": "Ultimo",
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            },
        });
        $('#search').on('submit', function (e) {
            oTable.draw();
            e.preventDefault();
        });
    </script>
    <script>
        // SweetAlert2 delete handler
        $(document).on('click', '.btn-delete-contact', function (e) {
            e.preventDefault();
            const Id = $(this).data('id');

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
                        url: `{{url('contacts-carriers')}}/${Id}`,
                        type: 'DELETE',
                        data: {
                            _token: '{{csrf_token()}}'
                        },
                        success: function (response) {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Eliminado!',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
                            // Reload DataTable
                            $('#table-contacts-carriers').DataTable().ajax.reload();
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


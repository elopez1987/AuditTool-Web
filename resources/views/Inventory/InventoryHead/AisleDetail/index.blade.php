@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Inventario Fiscal
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form method="GET" id="search" role="form">
                                {{csrf_field()}}
                                <div class="row">
                                    <div class="col-md-12">
                                        <a href="">
                                            @include('buttons.search')
                                        </a>
                                        <a>
                                            @include('buttons.reloadajax')
                                        </a>
                                        <a href="{{url('inventoryheads')}}">
                                            @include('buttons.cancel')
                                        </a>
                                    </div>
                                </div>
                                <hr class="m-t-3">
                                <div class="row">
                                    <x-text-input
                                            name="nameinventory"
                                            label="Nombre del inventario"
                                            uppercase
                                            readonly
                                            value="{{$inventoryHead->name}}"
                                    />
                                    <x-text-input
                                            name="name"
                                            label="Nombre del pasillo"
                                            uppercase
                                    />
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <table width="100%" id="table-aisles" class="table table-bordered  table-condensed table-hover">
                                <thead>
                                <tr>
                                    <th>C.Distribución</th>
                                    <th>Bodega</th>
                                    <th>ID Inventario</th>
                                    <th>Pasillo</th>
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

@section('script')

    <script>
        function reloadtable() {
            $('#table-aisles').DataTable().ajax.reload();
        }

        $(document).on('click', '.btn-delete', function () {
            var id = $(this).data('id');

            Swal.fire({
                title: '¿Eliminar pasillo?',
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
                    url: '{{ url("deleteAisleDetail") }}/' + id,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Eliminado',
                                text: response.message,
                                timer: 1800,
                                showConfirmButton: false
                            }).then(function () {
                                $('#table-aisles').DataTable().ajax.reload(null, false);
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message
                            });
                        }
                    },
                    error: function (xhr) {
                        var msg = xhr.responseJSON ? xhr.responseJSON.message : 'Error al eliminar el registro.';
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: msg
                        });
                    }
                });
            });
        });
    </script>

    <script>
        var oTable = $("#table-aisles").DataTable({
            "paging": true,
            "searching": false,
            "ordering": true,
            "sScrollY": "450px",
            "sScrollX": true,
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "colReorder": true,
            "stateSave": true,
            "stateDuration": 0,
            "ajax": {
                url: '{{ route("getAislesInventory", $inventoryHead->id) }}',
                data: function (d) {
                    d.name = $('input[name=name]').val();
                }
            },
            "columns": [
                {data: 'center',name: 'center'},
                {data: 'warehouse',name: 'warehouse'},
                {data: 'inventory_head_id'},
                {data: 'pasillo',name: 'pasillo'},
                {data: 'btn'},
            ],
            "dom": '<"row mb-3"<"col-sm-4"B><"col-sm-4 d-flex justify-content-center"l><"col-sm-4 d-flex justify-content-end"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],

            buttons: [
                {
                    "extend": "colvis",
                    "text": "<img src=\"{{asset('/pics/adbuscar.png')}}\"  alt=''><span  style='font-size: 11px;'> Mostar/Ocultar</span>",
                    "className": "btn btn-outline-info btn-md  btn-bold",
                    columns: ':not(:first):not(:last)'
                },
                {
                    "extend": "csv",
                    "text": "<img src=\"{{asset('/ico/csv.png')}}\"  alt='' class='img-16'><span  style='font-size: 11px;'> Csv</span>",
                    "className": "btn btn-outline-info btn-md  btn-bold",
                },
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
                "processing": "Procesando...",
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


@endsection

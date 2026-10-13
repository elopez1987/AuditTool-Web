@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Detalla de cédula
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
                                            <button type="button"
                                                    class="btn waves-effect waves-light btn-outline-info btn-md"
                                                    onclick="reloadtable();">
                                                <img src="{{asset('/ico/reloadpage.png')}}" alt="" class="img-16 ml-1">
                                                <span class="text-button"> Refrescar</span>
                                            </button>
                                        </a>
                                        <a href="{{url('inventoryhead-cards/' . $inventoryHead->inventory_uuid)}}">
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
                                            value="{{$inventoryCardCount->inventoryhead->name}}"
                                    />
                                    <x-text-input
                                            name="name"
                                            label="Ubicación"
                                            uppercase
                                    />
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="row" id="cards-locations">
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <div class="card border-info">
                                <div class="card-header bg-grey">
                                    <h5 class="m-b-0 text-black-50">Conteo 1 - Pendientes</h5>
                                </div>
                                <div class="card-body">
                                    <h2 class="text-red" id="pendientesCount01"></h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <div class="card border-info">
                                <div class="card-header bg-grey">
                                    <h5 class="m-b-0 text-black-50">Conteo 1 - Terminados</h5>
                                </div>
                                <div class="card-body">
                                    <h2 class="text-red" id="terminadosCount01"></h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <div class="card border-info">
                                <div class="card-header bg-grey">
                                    <h5 class="m-b-0 text-black-50">Conteo 2 - Pendientes</h5>
                                </div>
                                <div class="card-body">
                                    <h2 class="text-red" id="pendientesCount02"></h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <div class="card border-info">
                                <div class="card-header bg-grey">
                                    <h5 class="m-b-0 text-black-50">Conteo 2 - Terminados</h5>
                                </div>
                                <div class="card-body">
                                    <h2 class="text-red" id="terminadosCount02"></h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <div class="card border-info">
                                <div class="card-header bg-grey">
                                    <h5 class="m-b-0 text-black-50">Ubicaciones - Correctas</h5>
                                </div>
                                <div class="card-body">
                                    <h2 class="text-green" id="ubicacionesCorrectas"></h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <div class="card border-info">
                                <div class="card-header bg-grey">
                                    <h5 class="m-b-0 text-black-50">Ubicaciones - Con Error</h5>
                                </div>
                                <div class="card-body">
                                    <h2 class="text-red" id="ubicacionesConError"></h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <table width="100%" id="table-cards-locations"
                                   class="table table-bordered  table-condensed table-hover">
                                <thead>
                                <tr>
                                    <th>Pasillo</th>
                                    <th>Cédula</th>
                                    <th>Ubicación</th>
                                    <th>Número de parte 01</th>
                                    <th>Descripción 01</th>
                                    <th>Cantidad 01</th>
                                    <th>Contado por :</th>
                                    <th>Número de parte 02</th>
                                    <th>Descripción 02</th>
                                    <th>Cantidad 02</th>
                                    <th>Contado por :</th>
                                    <th>Estatus Conteo 01</th>
                                    <th>Estatus Conteo 02</th>
                                    <th>Estatus General</th>
                                    <th>Opciones</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-lg-12">
                            @can('inventoryheads.initializecount')
                                <a href="" data-target="#modal-initialize1-{{$inventoryCardCount->id}}"
                                   data-toggle="modal" data-placement="top">
                                    <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md"
                                            data-toggle="tooltip" data-placement="top" title="Cerrar Inventario">
                                        <img src="{{asset('/ico/adreload.png')}}" alt="" class="img-16">
                                        <span style="font-size: 11px;"> Reiniciar Conteo 1</span>
                                    </button>
                                </a>
                                @include('Inventory.InventoryHead.LocationCard.initialize-count1')

                                <a href="" data-target="#modal-initialize2-{{$inventoryCardCount->id}}"
                                   data-toggle="modal" data-placement="top">
                                    <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md"
                                            data-toggle="tooltip" data-placement="top" title="Cerrar Inventario">
                                        <img src="{{asset('/ico/adreload.png')}}" alt="" class="img-16">
                                        <span style="font-size: 11px;"> Reiniciar Conteo 2</span>
                                    </button>
                                </a>
                                @include('Inventory.InventoryHead.LocationCard.initialize-count2')

                                <a href="" data-target="#modal-validateCard-{{$inventoryCardCount->id}}"
                                   data-toggle="modal" data-placement="top">
                                    <button type="button" class="btn waves-effect waves-light btn-outline-info btn-md"
                                            data-toggle="tooltip" data-placement="top" title="Cerrar Inventario">
                                        <img src="{{asset('/ico/adreload.png')}}" alt="" class="img-16">
                                        <span style="font-size: 11px;"> Validar ubicaciones</span>
                                    </button>
                                </a>
                                @include('Inventory.InventoryHead.LocationCard.validataCard')
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script>
        function loadLocationCardKpis() {
            $.get('{{ route("getLocationCardKpis", $inventoryCardCount->id) }}', {
                name: $('input[name=name]').val(),
            })
                .done(function (data) {
                    $('#pendientesCount01').text(data.pendientesCount01);
                    $('#terminadosCount01').text(data.terminadosCount01);
                    $('#pendientesCount02').text(data.pendientesCount02);
                    $('#terminadosCount02').text(data.terminadosCount02);
                    $('#ubicacionesCorrectas').text(data.ubicacionesCorrectas);
                    $('#ubicacionesConError').text(data.ubicacionesConError);
                })
                .fail(function () {
                    $('#pendientesCount01, #terminadosCount01, #pendientesCount02, #terminadosCount02, #ubicacionesCorrectas, #ubicacionesConError').text('-');
                });
        }

        function reloadtable() {
            $('#table-cards-locations').DataTable().ajax.reload();
            loadLocationCardKpis();
        }
    </script>
    <script>
        var oTable = $("#table-cards-locations").DataTable({
            "paging": true,
            "searching": false,
            "ordering": true,
            "sScrollY": "650px",
            "sScrollX": true,
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "colReorder": true,
            "stateSave": true,
            "stateDuration": 0,
            "ajax": {
                url: '{{ route("getLocationCardInventory", $inventoryCardCount->id) }}',
                data: function (d) {
                    d.name = $('input[name=name]').val();
                }
            },
            "columns": [
                {data: 'pasillo', name: 'pasillo'},
                {data: 'cedula', name: 'cedula'},
                {data: 'ubicacion', name: 'ubicacion'},
                {data: 'sku01', name: 'sku01'},
                {data: 'description01', name: 'description01'},
                {data: 'quantity01'},
                {
                    data: 'user_count01',
                    name: 'user_count01',
                    render: function (data, type, rowData) {
                        return data ? data.toUpperCase() : '';
                    }
                },
                {data: 'sku02', name: 'sku02'},
                {data: 'description02', name: 'description02'},
                {data: 'quantity02'},
                {
                    data: 'user_count02',
                    name: 'user_count02',
                    render: function (data, type, rowData) {
                        return data ? data.toUpperCase() : '';
                    }
                },
                {
                    data: 'statusid01', name: 'statusid01',
                    render: function (data, type, row) {
                        switch (data) {
                            case 'C':
                                return '<span class="badge bg-green font-12 font-light text-black">Terminado</span>';
                            case 'F':
                                return '<span class="badge bg-yellow font-12 text-black font-light">Faltante</span>';
                            case 'I':
                                return '<span class="badge bg-blue font-12 font-light">En Proceso</span>';
                            default:
                                return '<span class="badge bg-red font-12 font-light">Pendiente</span>';
                        }
                    },
                },
                {
                    data: 'statusid02', name: 'statusid02',
                    render: function (data, type, row) {
                        switch (data) {
                            case 'C':
                                return '<span class="badge bg-green font-12 font-light text-black">Terminado</span>';
                            case 'F':
                                return '<span class="badge bg-yellow font-12 font-light text-black">Faltante</span>';
                            case 'I':
                                return '<span class="badge bg-blue font-12 font-light">En Proceso</span>';
                            default:
                                return '<span class="badge bg-red font-12 font-light">Pendiente</span>';
                        }
                    },
                },
                {
                    data: 'statusid_location',
                    render: function (data, type, row) {

                        if (row.statusid02 !== 'C') {
                            return '<span class="badge bg-secondary font-12 font-light"></span>';
                        }

                        return (data == 1 || data === true)
                            ? '<span class="badge bg-green font-12 font-light text-black">Correcto</span>'
                            : '<span class="badge bg-red font-12 font-light">Con Error</span>';
                    }
                },
                {data: 'btn', orderable: false, searchable: false},
            ],
            "dom": '<"row mb-3"<"col-sm-4"B><"col-sm-4 d-flex justify-content-center"l><"col-sm-4 d-flex justify-content-end"f>>rt<"row"<"col-sm-5"i><"col-sm-7"p>>',
            "lengthMenu": [[25, 50, 100, -1], [25, 50, 100, "Todos"]],

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
            loadLocationCardKpis();
            e.preventDefault();
        });

        // Enviar ubicación a 3er conteo sin recargar la página (Swal + AJAX)
        $(document).on('click', '.btn-markaserror', function () {
            var $btn = $(this);
            var id = $btn.data('id');

            if ($btn.data('processing') === true) {
                return;
            }

            Swal.fire({
                title: '¿Enviar a 3er conteo?',
                text: 'Esta ubicación se marcará con error y quedará lista para 3er conteo.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, enviar',
                cancelButtonText: 'Cancelar'
            }).then(function (result) {
                if (!result.isConfirmed) {
                    return;
                }

                $btn.data('processing', true).prop('disabled', true);

                $.ajax({
                    url: "{{ url('markaserror') }}/" + id,
                    type: 'POST',
                    data: {
                        _method: 'PUT',
                        _token: '{{ csrf_token() }}'
                    },
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    success: function (response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Listo',
                                text: response.message || 'Ubicación enviada a 3er conteo correctamente.',
                                timer: 1800,
                                showConfirmButton: false
                            });
                            reloadtable();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message || 'No se pudo enviar la ubicación a 3er conteo.'
                            });
                        }
                    },
                    error: function (xhr) {
                        var message = (xhr.responseJSON && xhr.responseJSON.message)
                            ? xhr.responseJSON.message
                            : 'Ocurrió un error al enviar la ubicación a 3er conteo.';

                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: message
                        });
                    },
                    complete: function () {
                        $btn.data('processing', false).prop('disabled', false);
                    }
                });
            });
        });

        $(document).ready(function () {
            loadLocationCardKpis();
        });
    </script>

@endsection
